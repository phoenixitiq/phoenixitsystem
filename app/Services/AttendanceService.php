<?php

namespace App\Services;

use App\Models\AttendanceRecord;
use App\Models\TaskTracking;
use App\Models\PerformanceReport;
use App\Models\OvertimeRecord;
use App\Models\Holiday;
use App\Models\WeeklySchedule;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    public function recordCheckIn($employeeId, $data)
    {
        try {
            DB::beginTransaction();

            // التحقق من الدوام المحدد للموظف
            $shift = $this->getEmployeeShift($employeeId, now());
            
            // حساب التأخير
            $lateMinutes = $this->calculateLateMinutes($shift->start_time, now());

            $attendance = AttendanceRecord::create([
                'employee_id' => $employeeId,
                'shift_id' => $shift->id,
                'date' => now()->toDateString(),
                'check_in' => now(),
                'late_minutes' => $lateMinutes,
                'status' => $lateMinutes > $shift->grace_period ? 'late' : 'present',
                'location_check_in' => $data['location'] ?? null,
                'ip_address' => request()->ip()
            ]);

            DB::commit();
            return $attendance;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function recordCheckOut($employeeId, $data)
    {
        try {
            DB::beginTransaction();

            $attendance = AttendanceRecord::where('employee_id', $employeeId)
                ->where('date', now()->toDateString())
                ->firstOrFail();

            $shift = $this->getEmployeeShift($employeeId, now());
            
            // حساب الخروج المبكر
            $earlyLeaveMinutes = $this->calculateEarlyLeave($shift->end_time, now());
            
            // حساب ساعات العمل الفعلية
            $actualHours = $this->calculateActualHours(
                $attendance->check_in,
                now(),
                $shift->break_duration
            );

            $attendance->update([
                'check_out' => now(),
                'early_leave_minutes' => $earlyLeaveMinutes,
                'actual_hours' => $actualHours,
                'location_check_out' => $data['location'] ?? null
            ]);

            // تحديث تتبع المهام
            $this->updateTaskTracking($employeeId, $attendance);

            DB::commit();
            return $attendance;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function generatePerformanceReport($employeeId, $startDate, $endDate)
    {
        $attendanceStats = $this->calculateAttendanceStats($employeeId, $startDate, $endDate);
        $productivityStats = $this->calculateProductivityStats($employeeId, $startDate, $endDate);

        return PerformanceReport::create([
            'employee_id' => $employeeId,
            'period_start' => $startDate,
            'period_end' => $endDate,
            'attendance_score' => $this->calculateAttendanceScore($attendanceStats),
            'productivity_score' => $this->calculateProductivityScore($productivityStats),
            'quality_score' => $this->calculateQualityScore($employeeId, $startDate, $endDate),
            'tasks_completed' => $productivityStats['completed_tasks'],
            'actual_working_hours' => $attendanceStats['actual_hours'],
            'overtime_hours' => $attendanceStats['overtime_hours'],
            'late_count' => $attendanceStats['late_count'],
            'absence_count' => $attendanceStats['absence_count']
        ]);
    }

    public function requestOvertime($employeeId, $data)
    {
        try {
            DB::beginTransaction();

            // التحقق من صلاحية طلب الدوام الإضافي
            $this->validateOvertimeRequest($employeeId, $data);

            $overtime = OvertimeRecord::create([
                'employee_id' => $employeeId,
                'date' => $data['date'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'type' => $this->determineOvertimeType($data['date']),
                'reason' => $data['reason'],
                'hours' => $this->calculateOvertimeHours(
                    $data['start_time'],
                    $data['end_time']
                ),
                'rate' => $this->getOvertimeRate($data['date'])
            ]);

            // إرسال إشعار للمدير للموافقة
            $this->notifyManager($overtime);

            DB::commit();
            return $overtime;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function approveOvertime($overtimeId, $managerId)
    {
        try {
            DB::beginTransaction();

            $overtime = OvertimeRecord::findOrFail($overtimeId);
            
            $overtime->update([
                'status' => 'approved',
                'approved_by' => $managerId
            ]);

            // إضافة الساعات الإضافية إلى تقرير الأداء
            $this->updatePerformanceReport($overtime);

            // إشعار الموظف بالموافقة
            $this->notifyEmployee($overtime);

            DB::commit();
            return $overtime;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function calculateActualHours($checkIn, $checkOut, $breakDuration)
    {
        $checkIn = Carbon::parse($checkIn);
        $checkOut = Carbon::parse($checkOut);
        
        $totalMinutes = $checkOut->diffInMinutes($checkIn);
        $actualMinutes = $totalMinutes - $breakDuration;
        
        return round($actualMinutes / 60, 2);
    }

    protected function updateTaskTracking($employeeId, $attendance)
    {
        $tasks = TaskTracking::where('employee_id', $employeeId)
            ->where('date', $attendance->date)
            ->where('status', 'in_progress')
            ->get();

        foreach ($tasks as $task) {
            $duration = $this->calculateTaskDuration($task->start_time, now());
            
            $task->update([
                'end_time' => now(),
                'duration' => $duration,
                'status' => 'completed'
            ]);
        }
    }

    protected function calculateAttendanceScore($stats)
    {
        $score = 100;
        
        // خصم نقاط للتأخير
        $score -= ($stats['late_count'] * 5);
        
        // خصم نقاط للغياب
        $score -= ($stats['absence_count'] * 10);
        
        // خصم نقاط للخروج المبكر
        $score -= ($stats['early_leave_count'] * 3);
        
        return max(0, $score);
    }

    protected function calculateProductivityScore($stats)
    {
        $score = 0;
        
        // نقاط لإكمال المهام
        $score += ($stats['completed_tasks'] * 10);
        
        // نقاط لجودة العمل
        $score += ($stats['average_quality'] * 20);
        
        // نقاط للإنتاجية
        $score += ($stats['productivity_rate'] * 30);
        
        return min(100, $score);
    }

    protected function validateOvertimeRequest($employeeId, $data)
    {
        // التحقق من الحد الأقصى للساعات الإضافية في الشهر
        $monthlyHours = $this->getMonthlyOvertimeHours($employeeId, $data['date']);
        if ($monthlyHours >= config('attendance.max_monthly_overtime')) {
            throw new \Exception('تم تجاوز الحد الأقصى للساعات الإضافية في هذا الشهر');
        }

        // التحقق من وجود تداخل مع دوام آخر
        if ($this->hasOverlappingShift($employeeId, $data['date'], $data['start_time'], $data['end_time'])) {
            throw new \Exception('يوجد تداخل مع دوام آخر');
        }
    }

    protected function determineOvertimeType($date)
    {
        $date = Carbon::parse($date);
        
        // التحقق من العطل الرسمية
        if ($this->isHoliday($date)) {
            return 'holiday';
        }
        
        // التحقق من عطلة نهاية الأسبوع
        if ($this->isWeekend($date)) {
            return 'weekend';
        }
        
        return 'weekday';
    }

    protected function getOvertimeRate($date)
    {
        $type = $this->determineOvertimeType($date);
        
        switch ($type) {
            case 'holiday':
                return config('attendance.holiday_overtime_rate', 2.0);
            case 'weekend':
                return config('attendance.weekend_overtime_rate', 1.75);
            default:
                return config('attendance.weekday_overtime_rate', 1.5);
        }
    }

    protected function calculateOvertimeHours($startTime, $endTime)
    {
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);
        
        return round($end->diffInMinutes($start) / 60, 2);
    }

    protected function getMonthlyOvertimeHours($employeeId, $date)
    {
        $date = Carbon::parse($date);
        
        return OvertimeRecord::where('employee_id', $employeeId)
            ->whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->where('status', 'approved')
            ->sum('hours');
    }

    protected function updatePerformanceReport($overtime)
    {
        $report = PerformanceReport::firstOrCreate([
            'employee_id' => $overtime->employee_id,
            'period_start' => Carbon::parse($overtime->date)->startOfMonth(),
            'period_end' => Carbon::parse($overtime->date)->endOfMonth()
        ]);

        $report->increment('overtime_hours', $overtime->hours);
        $this->recalculateProductivityScore($report);
    }

    protected function isHoliday($date)
    {
        return Holiday::whereDate('date', $date)->exists();
    }

    protected function isWeekend($date)
    {
        $weekendDays = config('attendance.weekend_days', ['Friday', 'Saturday']);
        return in_array($date->format('l'), $weekendDays);
    }

    protected function getEmployeeShift($employeeId, $date)
    {
        // التحقق من الجدول الأسبوعي للموظف
        $weeklySchedule = WeeklySchedule::where('employee_id', $employeeId)
            ->where('day_of_week', $date->format('l'))
            ->first();

        if ($weeklySchedule) {
            return $weeklySchedule->shift;
        }

        // إرجاع الدوام الافتراضي للموظف
        return Employee::find($employeeId)->default_shift;
    }

    protected function calculateTaskEfficiency($employeeId, $date)
    {
        $tasks = TaskTracking::where('employee_id', $employeeId)
            ->whereDate('date', $date)
            ->get();

        $totalTasks = $tasks->count();
        $completedTasks = $tasks->where('status', 'completed')->count();
        $totalEstimatedHours = $tasks->sum('estimated_hours');
        $totalActualHours = $tasks->sum('duration');

        return [
            'completion_rate' => $totalTasks ? ($completedTasks / $totalTasks) * 100 : 0,
            'efficiency_rate' => $totalEstimatedHours ? ($totalEstimatedHours / $totalActualHours) * 100 : 0,
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks
        ];
    }

    protected function validateAttendance($employeeId, $date, $time)
    {
        $shift = $this->getEmployeeShift($employeeId, $date);
        
        // التحقق من وقت الدوام
        if ($time->format('H:i:s') < $shift->start_time) {
            throw new \Exception('لا يمكن تسجيل الحضور قبل موعد بداية الدوام');
        }

        // التحقق من التسجيل المزدوج
        $existingRecord = AttendanceRecord::where('employee_id', $employeeId)
            ->whereDate('date', $date)
            ->first();

        if ($existingRecord) {
            throw new \Exception('تم تسجيل الحضور مسبقاً لهذا اليوم');
        }
    }

    public function getEmployeeAttendanceSummary($employeeId, $month, $year)
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $records = AttendanceRecord::where('employee_id', $employeeId)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $workingDays = $this->getWorkingDays($startDate, $endDate);
        $attendanceDays = $records->count();
        $lateDays = $records->where('status', 'late')->count();
        $absentDays = $workingDays - $attendanceDays;
        $totalWorkHours = $records->sum('actual_hours');
        $overtimeHours = $this->getOvertimeHours($employeeId, $startDate, $endDate);

        return [
            'working_days' => $workingDays,
            'attendance_days' => $attendanceDays,
            'late_days' => $lateDays,
            'absent_days' => $absentDays,
            'total_work_hours' => $totalWorkHours,
            'overtime_hours' => $overtimeHours,
            'attendance_rate' => ($attendanceDays / $workingDays) * 100,
            'efficiency_score' => $this->calculateEfficiencyScore($employeeId, $startDate, $endDate)
        ];
    }

    protected function calculateEfficiencyScore($employeeId, $startDate, $endDate)
    {
        $taskStats = TaskTracking::where('employee_id', $employeeId)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $completedOnTime = $taskStats->where('status', 'completed')
            ->where('duration', '<=', 'estimated_hours')
            ->count();

        $totalTasks = $taskStats->count();

        return $totalTasks ? ($completedOnTime / $totalTasks) * 100 : 0;
    }

    // ... المزيد من الوظائف المساعدة ...
} 