<?php

namespace App\Services;

use App\Models\SalaryAdvance;
use App\Models\PaymentNotification;
use Carbon\Carbon;

class SystemHealthCheck
{
    public function checkHRHealth()
    {
        return [
            'work_shifts' => $this->checkWorkShiftsHealth(),
            'payroll' => $this->checkPayrollHealth(),
            'advances' => $this->checkAdvancesHealth(),
            'contracts' => $this->checkContractsHealth()
        ];
    }

    protected function checkPayrollHealth()
    {
        return [
            'database_integrity' => $this->checkPayrollTables(),
            'calculations_accuracy' => $this->testSalaryCalculations(),
            'payment_processing' => $this->testPaymentProcessing(),
            'advance_management' => $this->testAdvanceSystem()
        ];
    }

    public function checkPayrollSystem()
    {
        return [
            'database' => [
                'tables_exist' => $this->checkPayrollTables(),
                'data_integrity' => $this->checkPayrollData()
            ],
            'settings' => [
                'advance_settings' => $this->checkAdvanceSettings(),
                'loan_settings' => $this->checkLoanSettings()
            ],
            'calculations' => [
                'salary_calc' => $this->testSalaryCalculations(),
                'advance_calc' => $this->testAdvanceCalculations(),
                'loan_calc' => $this->testLoanCalculations()
            ]
        ];
    }

    private function checkPayrollData()
    {
        return [
            'salary_records' => $this->validateSalaryRecords(),
            'advance_records' => $this->validateAdvanceRecords(),
            'loan_records' => $this->validateLoanRecords(),
            'deduction_records' => $this->validateDeductionRecords()
        ];
    }

    private function validateAdvanceRecords()
    {
        $issues = [];
        
        // فحص السلف الشهرية
        $monthlyAdvances = SalaryAdvance::where('advance_type', 'monthly_advance')->get();
        foreach ($monthlyAdvances as $advance) {
            if ($advance->amount > ($advance->employee->salary * 0.5)) {
                $issues[] = "سلفة رقم {$advance->id} تتجاوز الحد المسموح";
            }
        }

        // فحص القروض
        $loans = SalaryAdvance::where('advance_type', 'loan')->get();
        foreach ($loans as $loan) {
            if (!$loan->repaymentPlan) {
                $issues[] = "قرض رقم {$loan->id} بدون خطة سداد";
            }
        }

        return [
            'status' => empty($issues),
            'issues' => $issues
        ];
    }

    private function checkNotificationSystem()
    {
        return [
            'providers' => [
                'whatsapp' => $this->checkWhatsAppProviderStatus(),
                'email' => $this->checkEmailServerStatus(),
                'sms' => $this->checkSMSProviderStatus()
            ],
            'templates' => $this->checkNotificationTemplates(),
            'logs' => $this->analyzeNotificationLogs(),
            'performance' => [
                'success_rate' => $this->calculateNotificationSuccessRate(),
                'average_delivery_time' => $this->calculateAverageDeliveryTime(),
                'failed_attempts' => $this->getFailedAttemptsCount()
            ]
        ];
    }

    private function checkWhatsAppProviderStatus()
    {
        try {
            // فحص اتصال مزود خدمة الواتساب
            $provider = app(config('payroll.notifications.channels.whatsapp.provider'));
            return $provider->checkConnection();
        } catch (\Exception $e) {
            return false;
        }
    }

    private function calculateNotificationSuccessRate()
    {
        $total = PaymentNotification::count();
        $success = PaymentNotification::where('status', 'sent')->count();
        
        return $total > 0 ? ($success / $total) * 100 : 0;
    }

    private function analyzeNotificationLogs()
    {
        $lastDay = Carbon::now()->subDay();
        
        return [
            'total_sent' => \DB::table('notification_logs')->where('sent_at', '>=', $lastDay)->count(),
            'success_count' => \DB::table('notification_logs')->where('sent_at', '>=', $lastDay)->where('status', 'success')->count(),
            'failed_count' => \DB::table('notification_logs')->where('sent_at', '>=', $lastDay)->where('status', 'failed')->count(),
            'by_channel' => \DB::table('notification_logs')
                ->where('sent_at', '>=', $lastDay)
                ->groupBy('channel')
                ->select('channel', \DB::raw('count(*) as total'))
                ->get()
        ];
    }
} 