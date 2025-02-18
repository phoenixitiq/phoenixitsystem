<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LoanInstallment;
use App\Models\PaymentNotification;
use App\Models\SalaryAdvance;
use Carbon\Carbon;

class ScheduleNotifications extends Command
{
    protected $signature = 'notifications:schedule';
    protected $description = 'جدولة إشعارات المدفوعات';

    public function handle()
    {
        $this->info('جاري جدولة الإشعارات...');

        // جدولة تذكيرات قبل موعد السداد
        $this->scheduleUpcomingPaymentReminders();
        
        // جدولة إشعارات المتأخرات
        $this->scheduleOverdueNotifications();
        
        // إعادة محاولة الإشعارات الفاشلة
        $this->retryFailedNotifications();

        $this->info('تم جدولة الإشعارات بنجاح');
    }

    private function scheduleUpcomingPaymentReminders()
    {
        $beforeDueDays = config('payroll.notifications.schedules.payment_reminder.before_due');
        
        foreach ($beforeDueDays as $days) {
            $dueDate = Carbon::now()->addDays($days);
            
            // إشعارات أقساط القروض
            $installments = LoanInstallment::where('due_date', $dueDate->format('Y-m-d'))
                ->where('status', 'pending')
                ->get();

            foreach ($installments as $installment) {
                $this->createPaymentReminder($installment, $days);
            }

            // إشعارات السلف الشهرية
            $advances = SalaryAdvance::where('deduction_type', 'current_month')
                ->whereDate('request_date', $dueDate)
                ->where('status', 'approved')
                ->get();

            foreach ($advances as $advance) {
                $this->createAdvanceReminder($advance, $days);
            }
        }
    }

    private function scheduleOverdueNotifications()
    {
        $afterDueDays = config('payroll.notifications.schedules.payment_reminder.after_due');

        foreach ($afterDueDays as $days) {
            $overdueDate = Carbon::now()->subDays($days);

            // إشعارات الأقساط المتأخرة
            $overdueInstallments = LoanInstallment::where('due_date', $overdueDate->format('Y-m-d'))
                ->where('status', 'overdue')
                ->get();

            foreach ($overdueInstallments as $installment) {
                $this->createOverdueNotification($installment);
            }
        }
    }

    private function retryFailedNotifications()
    {
        $maxRetries = config('payroll.notifications.schedules.max_retries');
        $retryAfter = config('payroll.notifications.schedules.retry_failed');

        $failedNotifications = PaymentNotification::where('status', 'failed')
            ->where('created_at', '<=', Carbon::now()->subMinutes($retryAfter))
            ->get();

        foreach ($failedNotifications as $notification) {
            if ($notification->attempts < $maxRetries) {
                $this->retryNotification($notification);
            }
        }
    }

    private function createPaymentReminder($installment, $daysBeforeDue)
    {
        $notificationId = 'PAY-' . uniqid();
        
        PaymentNotification::create([
            'notification_id' => $notificationId,
            'type' => 'payment_reminder',
            'recipient_id' => $installment->loan->employee_id,
            'loan_id' => $installment->loan_id,
            'channels' => $this->getActiveChannels(),
            'scheduled_at' => $this->getNextValidNotificationTime(),
            'data' => [
                'amount' => $installment->amount,
                'due_date' => $installment->due_date,
                'days_remaining' => $daysBeforeDue,
                'payment_link' => $this->generatePaymentLink($installment),
                'locale' => setting('notification_locale', 'ar'),
                'template_data' => [
                    'recipient_name' => $installment->loan->employee->name,
                    'payment_type' => 'قسط القرض',
                    'currency' => 'د.ع'
                ]
            ]
        ]);
    }

    private function getActiveChannels()
    {
        $channels = json_decode(setting('notification_channels', '[]'), true);
        return array_filter($channels, function($channel) {
            return config("payroll.notifications.channels.{$channel}.enabled", false);
        });
    }

    private function getNextValidNotificationTime()
    {
        $now = Carbon::now();
        $blackoutStart = Carbon::createFromTimeString(setting('notification_blackout_start', '22:00'));
        $blackoutEnd = Carbon::createFromTimeString(setting('notification_blackout_end', '08:00'));
        
        // إذا كان الوقت الحالي في فترة حظر الإشعارات
        if ($now->between($blackoutStart, $blackoutEnd)) {
            return $blackoutEnd->addHour(); // جدولة للساعة بعد انتهاء فترة الحظر
        }
        
        return $now->addHour(); // جدولة للساعة القادمة
    }

    private function logNotificationAttempt($notification, $channel, $status, $response = null)
    {
        \DB::table('notification_logs')->insert([
            'notification_id' => $notification->id,
            'attempt_number' => $notification->attempts + 1,
            'channel' => $channel,
            'status' => $status,
            'provider_response' => $response,
            'sent_at' => Carbon::now(),
            'created_at' => Carbon::now()
        ]);
    }

    private function createAdvanceReminder($advance, $daysBeforeDue)
    {
        $notificationId = 'ADV-' . uniqid();
        
        PaymentNotification::create([
            'notification_id' => $notificationId,
            'type' => 'advance_reminder',
            'recipient_id' => $advance->employee_id,
            'advance_id' => $advance->id,
            'channels' => $this->getActiveChannels(),
            'scheduled_at' => $this->getNextValidNotificationTime(),
            'data' => [
                'amount' => $advance->amount,
                'deduction_date' => $advance->deduction_date,
                'days_remaining' => $daysBeforeDue,
                'locale' => setting('notification_locale', 'ar'),
                'template_data' => [
                    'recipient_name' => $advance->employee->name,
                    'payment_type' => 'سلفة شهرية',
                    'currency' => 'د.ع',
                    'salary_period' => Carbon::parse($advance->deduction_date)->format('Y-m')
                ]
            ]
        ]);
    }

    private function createOverdueNotification($installment)
    {
        $notificationId = 'OVERDUE-' . uniqid();
        
        PaymentNotification::create([
            'notification_id' => $notificationId,
            'type' => 'payment_overdue',
            'recipient_id' => $installment->loan->employee_id,
            'loan_id' => $installment->loan_id,
            'channels' => $this->getActiveChannels(),
            'scheduled_at' => $this->getNextValidNotificationTime(),
            'data' => [
                'amount' => $installment->amount,
                'due_date' => $installment->due_date,
                'days_overdue' => Carbon::parse($installment->due_date)->diffInDays(Carbon::now()),
                'payment_link' => $this->generatePaymentLink($installment),
                'locale' => setting('notification_locale', 'ar'),
                'template_data' => [
                    'recipient_name' => $installment->loan->employee->name,
                    'payment_type' => 'قسط القرض المتأخر',
                    'currency' => 'د.ع',
                    'late_fees' => $this->calculateLateFees($installment)
                ]
            ]
        ]);
    }

    private function calculateLateFees($installment)
    {
        $daysLate = Carbon::parse($installment->due_date)->diffInDays(Carbon::now());
        $lateFeeRate = setting('late_fee_rate', 0.01); // 1% per day
        return $installment->amount * ($daysLate * $lateFeeRate);
    }

    private function retryNotification($notification)
    {
        $notification->update([
            'status' => 'pending',
            'attempts' => $notification->attempts + 1,
            'scheduled_at' => Carbon::now()->addMinutes(5),
            'error_message' => null
        ]);
    }

    private function generatePaymentLink($installment)
    {
        // توليد رابط الدفع حسب نظام الدفع المستخدم
        return route('payments.process', [
            'type' => 'loan_installment',
            'id' => $installment->id,
            'token' => encrypt($installment->id)
        ]);
    }
} 