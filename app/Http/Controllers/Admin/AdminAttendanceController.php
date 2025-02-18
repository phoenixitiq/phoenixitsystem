<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ExportService;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;

class AdminAttendanceController extends Controller
{
    public function export(Request $request)
    {
        $type = $request->type;
        $data = $this->getAttendanceData();

        $exportService = app(ExportService::class);
        
        if ($type === 'excel') {
            $path = $exportService->toExcel($data, 'attendance');
            return response()->download($path)->deleteFileAfterSend();
        } elseif ($type === 'pdf') {
            $path = $exportService->toPDF($data, 'attendance');
            return response()->download($path)->deleteFileAfterSend();
        }
    }

    protected function getAttendanceData()
    {
        $records = AttendanceRecord::with('employee')
            ->when(request('date'), function($query) {
                return $query->whereDate('check_in', request('date'));
            })
            ->when(request('employee'), function($query) {
                return $query->where('employee_id', request('employee'));
            })
            ->get();

        $summary = [
            'total_days' => $records->count(),
            'total_hours' => $records->sum('work_hours'),
            'average_hours' => $records->avg('work_hours')
        ];

        return [
            'period' => request('date', 'كل الفترات'),
            'records' => $records,
            'summary' => $summary
        ];
    }
} 