<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PaymentNotification;
use App\Models\NotificationLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class NotificationPerformanceReport extends Command
{
    protected $signature = 'notifications:report {--period=daily} {--export=false}';
    protected $description = 'إنشاء تقرير أداء نظام الإشعارات';

    public function handle()
    {
        $period = $this->option('period');
        $data = $this->generateReport($period);
        
        $this->displayReport($data);
        
        if ($this->option('export')) {
            $this->exportReport($data);
        }

        $this->saveReport($data, $period);
    }

    private function generateReport($period)
    {
        $startDate = $this->getStartDate($period);
        
        return [
            'summary' => $this->getSummary($startDate),
            'by_channel' => $this->getChannelStats($startDate),
            'by_type' => $this->getTypeStats($startDate),
            'failures' => $this->getFailureAnalysis($startDate),
            'performance' => $this->getPerformanceMetrics($startDate)
        ];
    }

    private function getStartDate($period)
    {
        return match($period) {
            'daily' => now()->subDay(),
            'weekly' => now()->subWeek(),
            'monthly' => now()->subMonth(),
            default => now()->subDay()
        };
    }

    private function getSummary($startDate)
    {
        return [
            'total_notifications' => PaymentNotification::where('created_at', '>=', $startDate)->count(),
            'successful_deliveries' => PaymentNotification::where('status', 'sent')
                ->where('created_at', '>=', $startDate)
                ->count(),
            'failed_deliveries' => PaymentNotification::where('status', 'failed')
                ->where('created_at', '>=', $startDate)
                ->count(),
            'pending_notifications' => PaymentNotification::where('status', 'pending')
                ->where('created_at', '>=', $startDate)
                ->count()
        ];
    }

    private function getChannelStats($startDate)
    {
        return NotificationLog::where('created_at', '>=', $startDate)
            ->groupBy('channel')
            ->select('channel', 
                \DB::raw('count(*) as total'),
                \DB::raw('sum(case when status = "success" then 1 else 0 end) as successful'),
                \DB::raw('sum(case when status = "failed" then 1 else 0 end) as failed'),
                \DB::raw('avg(case when status = "success" then TIME_TO_SEC(TIMEDIFF(sent_at, created_at)) else null end) as avg_delivery_time')
            )
            ->get();
    }

    private function getTypeStats($startDate)
    {
        return PaymentNotification::where('created_at', '>=', $startDate)
            ->groupBy('type')
            ->select('type',
                \DB::raw('count(*) as total'),
                \DB::raw('sum(case when status = "sent" then 1 else 0 end) as delivered'),
                \DB::raw('sum(case when status = "failed" then 1 else 0 end) as failed')
            )
            ->get();
    }

    private function getFailureAnalysis($startDate)
    {
        return NotificationLog::where('status', 'failed')
            ->where('created_at', '>=', $startDate)
            ->groupBy('error_code')
            ->select('error_code',
                \DB::raw('count(*) as count'),
                \DB::raw('GROUP_CONCAT(DISTINCT error_message) as error_messages')
            )
            ->get();
    }

    private function getPerformanceMetrics($startDate)
    {
        $totalNotifications = PaymentNotification::where('created_at', '>=', $startDate)->count();
        $successfulDeliveries = PaymentNotification::where('status', 'sent')
            ->where('created_at', '>=', $startDate)
            ->count();

        return [
            'success_rate' => $totalNotifications > 0 ? 
                round(($successfulDeliveries / $totalNotifications) * 100, 2) : 0,
            'avg_delivery_time' => $this->calculateAverageDeliveryTime($startDate),
            'peak_hours' => $this->getPeakHours($startDate),
            'retry_stats' => $this->getRetryStats($startDate)
        ];
    }

    private function calculateAverageDeliveryTime($startDate)
    {
        return NotificationLog::where('status', 'success')
            ->where('created_at', '>=', $startDate)
            ->whereNotNull('sent_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(SECOND, created_at, sent_at)) as avg_time')
            ->first()
            ->avg_time ?? 0;
    }

    private function getPeakHours($startDate)
    {
        return NotificationLog::where('created_at', '>=', $startDate)
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();
    }

    private function getRetryStats($startDate)
    {
        return PaymentNotification::where('created_at', '>=', $startDate)
            ->where('attempts', '>', 0)
            ->selectRaw('
                AVG(attempts) as avg_retries,
                COUNT(CASE WHEN status = "sent" THEN 1 END) as successful_after_retry,
                COUNT(CASE WHEN status = "failed" THEN 1 END) as failed_after_retry
            ')
            ->first();
    }

    private function displayReport($data)
    {
        $this->info('=== تقرير أداء نظام الإشعارات ===');
        
        // عرض الملخص
        $this->info("\nملخص الإشعارات:");
        foreach ($data['summary'] as $key => $value) {
            $this->line("- {$key}: {$value}");
        }

        // عرض إحصائيات القنوات
        $this->info("\nأداء القنوات:");
        foreach ($data['by_channel'] as $stat) {
            $this->line("- {$stat->channel}:");
            $this->line("  * إجمالي: {$stat->total}");
            $this->line("  * ناجح: {$stat->successful}");
            $this->line("  * فاشل: {$stat->failed}");
        }

        // عرض مقاييس الأداء
        $this->info("\nمقاييس الأداء:");
        $this->line("- معدل النجاح: {$data['performance']['success_rate']}%");
        $this->line("- متوسط وقت التسليم: " . round($data['performance']['avg_delivery_time'] / 60, 2) . " دقيقة");
    }

    private function exportReport($data)
    {
        $filename = 'notification_report_' . now()->format('Y-m-d_H-i-s') . '.json';
        
        Storage::put("reports/notifications/{$filename}", json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        $this->info("\nتم تصدير التقرير إلى: reports/notifications/{$filename}");
    }

    private function saveReport($data, $period)
    {
        \DB::table('notification_performance_reports')->insert([
            'report_date' => now()->format('Y-m-d'),
            'period' => $period,
            'total_notifications' => $data['summary']['total_notifications'],
            'successful_count' => $data['summary']['successful_deliveries'],
            'failed_count' => $data['summary']['failed_deliveries'],
            'success_rate' => $data['performance']['success_rate'],
            'avg_delivery_time' => $data['performance']['avg_delivery_time'],
            'peak_hour' => $data['performance']['peak_hours'][0]->hour ?? null,
            'peak_hour_count' => $data['performance']['peak_hours'][0]->count ?? 0,
            'retry_attempts' => $data['performance']['retry_stats']->avg_retries ?? 0,
            'retry_success_rate' => $this->calculateRetrySuccessRate($data['performance']['retry_stats']),
            'channel_stats' => json_encode($data['by_channel']),
            'type_stats' => json_encode($data['by_type']),
            'failure_analysis' => json_encode($data['failures']),
            'created_at' => now()
        ]);

        // فحص العتبات وإرسال التنبيهات إذا لزم الأمر
        $this->checkAlertThresholds($data);

        $this->saveChannelPerformance($data);
    }

    private function checkAlertThresholds($data)
    {
        $thresholds = json_decode(setting('alert_thresholds'), true);
        
        if ($data['performance']['success_rate'] < $thresholds['success_rate_min']) {
            // إرسال تنبيه انخفاض معدل النجاح
            $this->sendAlert('success_rate', $data['performance']['success_rate']);
        }

        if ($data['performance']['avg_delivery_time'] > $thresholds['delivery_time_max']) {
            // إرسال تنبيه ارتفاع وقت التسليم
            $this->sendAlert('delivery_time', $data['performance']['avg_delivery_time']);
        }
    }

    private function calculateRetrySuccessRate($retryStats)
    {
        if (!$retryStats || !$retryStats->successful_after_retry) {
            return 0;
        }

        $totalRetries = $retryStats->successful_after_retry + $retryStats->failed_after_retry;
        return $totalRetries > 0 
            ? round(($retryStats->successful_after_retry / $totalRetries) * 100, 2)
            : 0;
    }

    private function sendAlert($type, $value)
    {
        $alertData = [
            'type' => $type,
            'value' => $value,
            'timestamp' => now(),
            'report_date' => now()->format('Y-m-d')
        ];

        // إرسال التنبيه للمسؤولين
        $admins = \App\Models\User::whereHas('roles', function($q) {
            $q->where('slug', 'admin');
        })->get();

        foreach ($admins as $admin) {
            \App\Models\Notification::create([
                'user_id' => $admin->id,
                'type' => 'notification_performance_alert',
                'data' => $alertData
            ]);

            // إرسال إشعار فوري عبر الواتساب للحالات الحرجة
            if ($type === 'success_rate' && $value < 90) {
                $this->sendUrgentWhatsAppAlert($admin, $type, $value);
            }
        }

        // تسجيل التنبيه في السجلات
        \Illuminate\Support\Facades\Log::channel('notifications')
            ->warning("Performance Alert: {$type} - {$value}");
    }

    private function sendUrgentWhatsAppAlert($admin, $type, $value)
    {
        $message = match($type) {
            'success_rate' => "تنبيه عاجل: معدل نجاح الإشعارات منخفض ({$value}%)",
            'delivery_time' => "تنبيه: ارتفاع متوسط وقت التسليم ({$value} ثانية)",
            default => "تنبيه أداء النظام: {$type} - {$value}"
        };

        // إرسال رسالة واتساب عاجلة
        try {
            $whatsapp = app(config('payroll.notifications.channels.whatsapp.provider'));
            $whatsapp->sendUrgentMessage($admin->phone, $message);
        } catch (\Exception $e) {
            $this->error("فشل إرسال تنبيه واتساب: " . $e->getMessage());
        }
    }

    private function saveChannelPerformance($data)
    {
        foreach ($data['by_channel'] as $channelStat) {
            \DB::table('notification_channel_performance')->insert([
                'channel' => $channelStat->channel,
                'date' => now()->format('Y-m-d'),
                'total_sent' => $channelStat->total,
                'successful' => $channelStat->successful,
                'failed' => $channelStat->failed,
                'avg_delivery_time' => $channelStat->avg_delivery_time,
                'error_rate' => ($channelStat->total > 0)
                    ? round(($channelStat->failed / $channelStat->total) * 100, 2)
                    : 0,
                'cost' => $this->calculateChannelCost(
                    $channelStat->channel,
                    $channelStat->total
                ),
                'created_at' => now()
            ]);
        }
    }

    private function calculateChannelCost($channel, $messageCount)
    {
        $costs = json_decode(setting('channel_costs'), true);
        return $messageCount * ($costs[$channel] ?? 0);
    }
} 