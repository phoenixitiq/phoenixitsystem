<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Permission;
use App\Models\NotificationTemplate;
use App\Models\PaymentNotification;

class CheckPayrollSystem extends Command
{
    protected $signature = 'system:check-payroll';
    protected $description = 'فحص نظام الرواتب والسلف';

    public function handle()
    {
        $this->info('جاري فحص نظام الرواتب...');

        // فحص جداول قاعدة البيانات
        $this->checkDatabaseTables();
        
        // فحص الإعدادات
        $this->checkSettings();
        
        // فحص الصلاحيات
        $this->checkPermissions();
        
        // فحص العمليات الحسابية
        $this->checkCalculations();

        // فحص نظام الإشعارات
        $this->checkNotificationSystem();

        // فحص صحة النظام
        $this->checkSystemHealth();
    }

    private function checkDatabaseTables()
    {
        $tables = [
            'salary_payments',
            'salary_advances',
            'advance_repayments',
            'loan_repayment_plans',
            'loan_installments'
        ];

        foreach ($tables as $table) {
            if (\Schema::hasTable($table)) {
                $this->info("✅ جدول {$table} موجود");
            } else {
                $this->error("❌ جدول {$table} غير موجود");
            }
        }
    }

    private function checkSettings()
    {
        $settings = [
            'max_monthly_advance',
            'max_loan_amount',
            'max_loan_installments',
            'min_service_for_loan',
            'loan_approval_chain'
        ];

        foreach ($settings as $key) {
            if (setting($key)) {
                $this->info("✅ إعداد {$key} موجود");
            } else {
                $this->error("❌ إعداد {$key} غير موجود");
            }
        }
    }

    private function checkCalculations()
    {
        // فحص حسابات السلف والقروض
        $calculations = [
            'monthly_advance' => $this->checkMonthlyAdvanceCalc(),
            'loan_installments' => $this->checkLoanInstallmentsCalc(),
            'salary_deductions' => $this->checkDeductionsCalc()
        ];

        foreach ($calculations as $type => $result) {
            if ($result['status']) {
                $this->info("✅ حسابات {$type} صحيحة");
            } else {
                $this->error("❌ خطأ في حسابات {$type}: " . $result['message']);
            }
        }
    }

    private function checkPermissions()
    {
        $required = [
            'manage_salaries',
            'approve_advances',
            'manage_loans',
            'view_payroll_reports',
            'approve_monthly_advance',
            'approve_loan_requests'
        ];

        foreach ($required as $permission) {
            if (Permission::where('slug', $permission)->exists()) {
                $this->info("✅ صلاحية {$permission} موجودة");
            } else {
                $this->error("❌ صلاحية {$permission} غير موجودة");
            }
        }
    }

    private function checkNotificationSystem()
    {
        // فحص إعدادات القنوات
        $this->checkNotificationChannels();
        
        // فحص القوالب
        $this->checkNotificationTemplates();
        
        // فحص الجداول
        $this->checkNotificationTables();
        
        // فحص الإحصائيات
        $this->checkNotificationStats();
        
        // فحص نظام التقارير
        $this->checkReportingSystem();
        
        // فحص تكاليف النظام
        $this->checkNotificationCosts();
        
        // فحص أداء القنوات
        $this->checkChannelPerformance();
        
        // فحص سياسة الاحتفاظ بالبيانات
        $this->checkRetentionPolicy();
        
        // فحص جدولة المهام
        $this->checkScheduledTasks();
    }

    private function checkNotificationChannels()
    {
        // Implementation of checkNotificationChannels method
    }

    private function checkNotificationTemplates()
    {
        $requiredTemplates = [
            'payment_due',
            'payment_overdue',
            'invoice',
            'payment_link'
        ];

        foreach ($requiredTemplates as $template) {
            $exists = NotificationTemplate::where('type', $template)
                ->where('is_active', true)
                ->exists();

            if ($exists) {
                $this->info("✅ قالب {$template} جاهز");
            } else {
                $this->error("❌ قالب {$template} غير موجود أو غير مفعل");
            }
        }
    }

    private function checkNotificationTables()
    {
        // Implementation of checkNotificationTables method
    }

    private function checkNotificationStats()
    {
        $stats = [
            'total_sent_24h' => PaymentNotification::where('sent_at', '>=', now()->subDay())->count(),
            'success_rate' => $this->calculateSuccessRate(),
            'avg_delivery_time' => $this->calculateAverageDeliveryTime(),
            'failed_attempts' => PaymentNotification::where('status', 'failed')->count()
        ];

        foreach ($stats as $key => $value) {
            $this->info("📊 {$key}: {$value}");
        }
    }

    private function calculateSuccessRate()
    {
        $total = PaymentNotification::where('created_at', '>=', now()->subDays(7))->count();
        $success = PaymentNotification::where('status', 'sent')
            ->where('created_at', '>=', now()->subDays(7))
            ->count();
        
        return $total > 0 ? round(($success / $total) * 100, 2) . '%' : '0%';
    }

    private function calculateAverageDeliveryTime()
    {
        $avg = PaymentNotification::where('status', 'sent')
            ->whereNotNull('sent_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(SECOND, created_at, sent_at)) as avg_time')
            ->first()
            ->avg_time;
        
        return $avg ? round($avg / 60, 2) . ' minutes' : 'N/A';
    }

    private function checkReportingSystem()
    {
        // فحص جدول التقارير
        if (\Schema::hasTable('notification_performance_reports')) {
            $this->info('✅ جدول تقارير الأداء موجود');
        } else {
            $this->error('❌ جدول تقارير الأداء غير موجود');
        }

        // فحص إعدادات التقارير
        $requiredSettings = [
            'notification_report_retention',
            'report_export_formats',
            'report_schedule',
            'alert_thresholds'
        ];

        foreach ($requiredSettings as $setting) {
            if (setting($setting)) {
                $this->info("✅ إعداد {$setting} موجود");
            } else {
                $this->error("❌ إعداد {$setting} غير موجود");
            }
        }

        // فحص التقارير السابقة
        $lastReport = \DB::table('notification_performance_reports')
            ->orderBy('report_date', 'desc')
            ->first();

        if ($lastReport) {
            $this->info("✅ آخر تقرير تم إنشاؤه بتاريخ: {$lastReport->report_date}");
            $this->info("   معدل النجاح: {$lastReport->success_rate}%");
        } else {
            $this->warn('⚠️ لم يتم إنشاء أي تقارير بعد');
        }

        // فحص أداء النظام في آخر 24 ساعة
        $this->checkSystemPerformance();
        
        // فحص حالة التنبيهات
        $this->checkAlertSystem();
    }

    private function checkSystemPerformance()
    {
        $lastDayStats = \DB::table('notification_performance_reports')
            ->where('report_date', '>=', now()->subDay())
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastDayStats) {
            $this->info("\nأداء النظام في آخر 24 ساعة:");
            $this->line("- معدل النجاح: {$lastDayStats->success_rate}%");
            $this->line("- متوسط وقت التسليم: {$lastDayStats->avg_delivery_time} ثانية");
            $this->line("- عدد المحاولات: {$lastDayStats->retry_attempts}");
            
            // تحليل الأداء
            if ($lastDayStats->success_rate < 95) {
                $this->warn("⚠️ معدل النجاح أقل من المتوقع");
            }
            if ($lastDayStats->avg_delivery_time > 300) {
                $this->warn("⚠️ وقت التسليم أعلى من المتوقع");
            }
        }
    }

    private function checkAlertSystem()
    {
        $thresholds = json_decode(setting('alert_thresholds'), true);
        if (!$thresholds) {
            $this->error("❌ لم يتم تعيين عتبات التنبيه");
            return;
        }

        $this->info("\nإعدادات نظام التنبيهات:");
        $this->line("- الحد الأدنى لمعدل النجاح: {$thresholds['success_rate_min']}%");
        $this->line("- الحد الأقصى لوقت التسليم: {$thresholds['delivery_time_max']} ثانية");
        $this->line("- الحد الأقصى لمحاولات الإعادة: {$thresholds['retry_attempts_max']}");

        // فحص التنبيهات الأخيرة
        $recentAlerts = \App\Models\Notification::where('type', 'notification_performance_alert')
            ->where('created_at', '>=', now()->subDay())
            ->count();

        if ($recentAlerts > 0) {
            $this->warn("⚠️ يوجد {$recentAlerts} تنبيهات في آخر 24 ساعة");
        } else {
            $this->info("✅ لا توجد تنبيهات حديثة");
        }
    }

    private function checkNotificationCosts()
    {
        $costs = json_decode(setting('channel_costs'), true);
        $targets = json_decode(setting('performance_targets'), true);
        
        $this->info("\nتحليل التكاليف:");
        
        $actualCosts = \DB::table('notification_channel_performance')
            ->where('date', '>=', now()->subMonth())
            ->selectRaw('
                SUM(cost) as total_cost,
                SUM(total_sent) as total_messages,
                SUM(cost) / SUM(total_sent) as avg_cost_per_message
            ')
            ->first();
        
        if ($actualCosts) {
            $this->line("- إجمالي التكلفة: {$actualCosts->total_cost}");
            $this->line("- متوسط تكلفة الرسالة: {$actualCosts->avg_cost_per_message}");
            
            if ($actualCosts->avg_cost_per_message > $targets['cost_per_message']) {
                $this->warn("⚠️ متوسط التكلفة أعلى من المستهدف");
            }
        }
    }

    private function checkChannelPerformance()
    {
        $this->info("\nأداء القنوات:");
        
        $performance = \DB::table('notification_channel_performance')
            ->where('date', '=', now()->format('Y-m-d'))
            ->get();
        
        foreach ($performance as $channel) {
            $successRate = ($channel->total_sent > 0)
                ? round(($channel->successful / $channel->total_sent) * 100, 2)
                : 0;
                
            $this->line("- {$channel->channel}:");
            $this->line("  * معدل النجاح: {$successRate}%");
            $this->line("  * متوسط وقت التسليم: {$channel->avg_delivery_time} ثانية");
            $this->line("  * معدل الأخطاء: {$channel->error_rate}%");
            
            // تحليل الأداء
            if ($successRate < 95) {
                $this->warn("  ⚠️ أداء القناة منخفض");
            }
        }
    }

    private function checkRetentionPolicy()
    {
        $policy = json_decode(setting('retention_policy'), true);
        
        $this->info("\nسياسة الاحتفاظ بالبيانات:");
        foreach ($policy as $key => $days) {
            $this->line("- {$key}: {$days} يوم");
        }

        // فحص حجم البيانات
        $this->checkDataSize();
    }

    private function checkDataSize()
    {
        $tables = [
            'notification_logs',
            'notification_performance_reports',
            'notification_channel_performance'
        ];

        foreach ($tables as $table) {
            $count = \DB::table($table)->count();
            $this->line("- {$table}: {$count} سجل");
            
            if ($count > 1000000) { // تحذير عند تجاوز مليون سجل
                $this->warn("⚠️ حجم جدول {$table} كبير");
            }
        }
    }

    private function checkScheduledTasks()
    {
        $this->info("\nحالة المهام المجدولة:");
        
        $tasks = [
            'notifications:report --period=daily' => 'daily',
            'notifications:report --period=weekly' => 'weekly',
            'notifications:report --period=monthly' => 'monthly',
            'notifications:cleanup' => 'daily',
            'system:check-payroll' => '30 minutes'
        ];

        foreach ($tasks as $task => $frequency) {
            // فحص آخر تنفيذ للمهمة
            $lastRun = \Cache::get("last_run_{$task}");
            
            if ($lastRun) {
                $this->line("- {$task}: آخر تنفيذ {$lastRun}");
            } else {
                $this->warn("⚠️ {$task}: لم يتم التنفيذ بعد");
            }
        }
    }

    private function checkSystemHealth()
    {
        $this->info("\nفحص صحة النظام:");

        // فحص الموارد الأساسية
        $this->checkSystemResources();
        
        // فحص قاعدة البيانات
        $this->checkDatabaseHealth();
        
        // فحص الذاكرة المؤقتة
        $this->checkCacheHealth();
        
        // فحص النظام
        $this->checkApplicationHealth();
    }

    private function checkSystemResources()
    {
        // فحص عدد العمليات
        $processCount = shell_exec('ps aux | grep php | wc -l');
        $this->line("- عدد العمليات PHP: {$processCount}");
        
        // فحص استخدام CPU
        $cpuUsage = sys_getloadavg();
        $this->line("- استخدام CPU: {$cpuUsage[0]}% (1m), {$cpuUsage[1]}% (5m), {$cpuUsage[2]}% (15m)");
        
        // فحص الذاكرة
        $memoryUsage = memory_get_usage(true) / 1024 / 1024;
        $this->line("- استخدام الذاكرة: {$memoryUsage} MB");
        
        // فحص مساحة القرص
        $diskUsage = disk_free_space('/') / disk_total_space('/') * 100;
        $this->line("- مساحة القرص المتاحة: {$diskUsage}%");
        
        // حفظ المقاييس
        $this->savePerformanceMetrics([
            'system_load' => $cpuUsage[0],
            'memory_usage' => $memoryUsage,
            'disk_usage' => 100 - $diskUsage,
            'process_count' => intval($processCount),
            'queue_size' => \DB::table('jobs')->count(),
            'processing_time' => $this->measureProcessingTime()
        ]);
    }

    private function checkDatabaseHealth()
    {
        $this->info("\nفحص قاعدة البيانات:");
        
        try {
            // فحص الاتصالات النشطة
            $connections = \DB::select('SHOW STATUS WHERE Variable_name = "Threads_connected"')[0]->Value;
            $this->line("- الاتصالات النشطة: {$connections}");
            
            // فحص حجم قاعدة البيانات
            $dbSize = \DB::select('SELECT SUM(data_length + index_length) AS size FROM information_schema.tables')[0]->size;
            $this->line("- حجم قاعدة البيانات: " . round($dbSize / 1024 / 1024, 2) . " MB");
            
            // فحص أداء الاستعلامات
            $this->checkQueryPerformance();
        } catch (\Exception $e) {
            $this->error("❌ خطأ في فحص قاعدة البيانات: " . $e->getMessage());
        }
    }

    private function checkQueryPerformance()
    {
        $start = microtime(true);
        
        // تنفيذ مجموعة من الاستعلامات القياسية
        \DB::table('notification_logs')->count();
        \DB::table('payment_notifications')->where('status', 'pending')->count();
        \DB::table('system_performance_metrics')->orderBy('id', 'desc')->limit(10)->get();
        
        $queryTime = (microtime(true) - $start) * 1000;
        $this->line("- متوسط وقت الاستعلام: {$queryTime} ms");
        
        if ($queryTime > 1000) {
            $this->warn("⚠️ أداء الاستعلامات بطيء");
        }
    }

    private function checkApplicationHealth()
    {
        $this->info("\nفحص صحة التطبيق:");
        
        // فحص المهام المجدولة
        $failedJobs = \DB::table('failed_jobs')->count();
        $this->line("- المهام الفاشلة: {$failedJobs}");
        
        // فحص الجلسات النشطة
        $activeSessions = \DB::table('sessions')->where('last_activity', '>=', strtotime('-30 minutes'))->count();
        $this->line("- الجلسات النشطة: {$activeSessions}");
        
        // فحص معدل الطلبات
        $requestRate = $this->getRequestRate();
        $this->line("- معدل الطلبات: {$requestRate}/دقيقة");
    }

    private function measureProcessingTime()
    {
        $start = microtime(true);
        
        // تنفيذ عملية قياسية
        \DB::table('notification_logs')
            ->where('created_at', '>=', now()->subDay())
            ->count();
        
        return (microtime(true) - $start) * 1000; // تحويل إلى مللي ثانية
    }

    private function savePerformanceMetrics($metrics)
    {
        \DB::table('system_performance_metrics')->insert([
            'check_date' => now()->format('Y-m-d'),
            'check_time' => now()->format('H:i:s'),
            'metrics' => json_encode($metrics),
            'alerts' => json_encode($this->generateAlerts($metrics)),
            'system_load' => $metrics['system_load'],
            'memory_usage' => $metrics['memory_usage'],
            'disk_usage' => $metrics['disk_usage'],
            'queue_size' => $metrics['queue_size'],
            'processing_time' => $metrics['processing_time'],
            'created_at' => now()
        ]);
    }

    private function generateAlerts($metrics)
    {
        $alerts = [];
        $thresholds = json_decode(setting('monitoring.thresholds'), true);

        foreach ($metrics as $key => $value) {
            if (isset($thresholds[$key]) && $value > $thresholds[$key]) {
                $alerts[] = [
                    'type' => $key,
                    'value' => $value,
                    'threshold' => $thresholds[$key],
                    'timestamp' => now()->format('Y-m-d H:i:s')
                ];
            }
        }

        return $alerts;
    }

    private function checkCacheHealth()
    {
        $this->info("\nفحص الذاكرة المؤقتة:");
        
        try {
            // فحص اتصال الذاكرة المؤقتة
            $isConnected = \Cache::has('test_key');
            $this->line("- اتصال الذاكرة المؤقتة: " . ($isConnected ? "✅ متصل" : "❌ غير متصل"));
            
            // فحص معدل الإصابة
            $stats = $this->getCacheStats();
            $this->line("- معدل الإصابة: {$stats['hit_rate']}%");
            $this->line("- حجم البيانات المخزنة: {$stats['size']} MB");
            
            // فحص أداء الذاكرة المؤقتة
            $cachePerformance = $this->measureCachePerformance();
            $this->line("- متوسط وقت الوصول: {$cachePerformance} ms");
            
            if ($stats['hit_rate'] < 80) {
                $this->warn("⚠️ معدل إصابة الذاكرة المؤقتة منخفض");
            }
            
            if ($cachePerformance > 100) {
                $this->warn("⚠️ أداء الذاكرة المؤقتة بطيء");
            }
        } catch (\Exception $e) {
            $this->error("❌ خطأ في فحص الذاكرة المؤقتة: " . $e->getMessage());
        }
    }

    private function getCacheStats()
    {
        $hits = \Cache::get('cache_hits', 0);
        $misses = \Cache::get('cache_misses', 0);
        $total = $hits + $misses;
        
        return [
            'hit_rate' => $total > 0 ? round(($hits / $total) * 100, 2) : 0,
            'size' => $this->getCacheSize(),
            'hits' => $hits,
            'misses' => $misses
        ];
    }

    private function getCacheSize()
    {
        if (config('cache.default') === 'redis') {
            try {
                $info = \Redis::info('memory');
                return round($info['used_memory'] / 1024 / 1024, 2);
            } catch (\Exception $e) {
                return 0;
            }
        }
        return 0;
    }

    private function measureCachePerformance()
    {
        $start = microtime(true);
        
        // اختبار عمليات الذاكرة المؤقتة
        for ($i = 0; $i < 100; $i++) {
            $key = "test_key_{$i}";
            \Cache::put($key, "test_value_{$i}", 60);
            \Cache::get($key);
            \Cache::forget($key);
        }
        
        return round((microtime(true) - $start) * 1000 / 100, 2);
    }

    private function getRequestRate()
    {
        try {
            // حساب معدل الطلبات في آخر دقيقة
            $requests = \DB::table('system_performance_metrics')
                ->where('check_date', now()->format('Y-m-d'))
                ->where('check_time', '>=', now()->subMinutes(1)->format('H:i:s'))
                ->avg('metrics->request_count');
                
            return round($requests ?? 0, 2);
        } catch (\Exception $e) {
            return 0;
        }
    }
} 