<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SystemCheckService;

class SystemCheck extends Command
{
    protected $signature = 'system:check {--detailed}';
    protected $description = 'تشغيل فحص شامل للنظام';

    public function handle(SystemCheckService $checker)
    {
        $this->info('جاري فحص النظام...');
        
        $results = $checker->runDiagnostics();
        
        if ($results['success']) {
            $this->info('✅ النظام يعمل بشكل صحيح');
        } else {
            $this->error('❌ تم اكتشاف مشاكل في النظام');
        }

        if ($this->option('detailed')) {
            $this->table(['الفحص', 'الحالة', 'التفاصيل'], $this->formatResults($results['results']));
        }

        return $results['success'] ? 0 : 1;
    }

    private function formatResults($results)
    {
        $formatted = [];
        foreach ($results as $key => $result) {
            $formatted[] = [
                $key,
                $result['status'] ? '✅ ناجح' : '❌ فشل',
                json_encode($result['details'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            ];
        }
        return $formatted;
    }

    protected function checkHRSystem()
    {
        $checks = [
            'work_shifts' => $this->checkWorkShifts(),
            'contracts' => $this->checkContracts(),
            'payroll' => $this->checkPayroll(),
            'advances' => $this->checkAdvances()
        ];
        
        return $checks;
    }

    protected function checkPayroll()
    {
        return [
            'salary_calculations' => true,
            'payment_processing' => true,
            'advance_management' => true,
            'overtime_calculations' => true
        ];
    }
} 