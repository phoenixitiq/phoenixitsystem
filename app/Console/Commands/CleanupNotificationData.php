<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class CleanupNotificationData extends Command
{
    protected $signature = 'notifications:cleanup';
    protected $description = 'تنظيف البيانات القديمة من نظام الإشعارات';

    public function handle()
    {
        $this->info('جاري تنظيف بيانات الإشعارات...');

        $retentionPolicy = json_decode(setting('retention_policy'), true);
        
        // تنظيف التقارير اليومية
        $this->cleanupReports('daily', $retentionPolicy['daily_reports']);
        
        // تنظيف التقارير الأسبوعية
        $this->cleanupReports('weekly', $retentionPolicy['weekly_reports']);
        
        // تنظيف التقارير الشهرية
        $this->cleanupReports('monthly', $retentionPolicy['monthly_reports']);
        
        // تنظيف سجلات الإشعارات
        $this->cleanupLogs($retentionPolicy['logs']);
        
        // تنظيف التنبيهات
        $this->cleanupAlerts($retentionPolicy['alerts']);

        $this->info('تم تنظيف البيانات بنجاح');
    }

    private function cleanupReports($period, $days)
    {
        $count = \DB::table('notification_performance_reports')
            ->where('period', $period)
            ->where('report_date', '<', Carbon::now()->subDays($days))
            ->delete();

        $this->info("تم حذف {$count} تقرير {$period}");
    }

    private function cleanupLogs($days)
    {
        $count = \DB::table('notification_logs')
            ->where('created_at', '<', Carbon::now()->subDays($days))
            ->delete();

        $this->info("تم حذف {$count} سجل");
    }

    private function cleanupAlerts($days)
    {
        $count = \DB::table('notifications')
            ->where('type', 'notification_performance_alert')
            ->where('created_at', '<', Carbon::now()->subDays($days))
            ->delete();

        $this->info("تم حذف {$count} تنبيه");
    }
} 