<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * أوامر Artisan المسجلة في التطبيق.
     *
     * @var array
     */
    protected $commands = [
        Commands\TestSystem::class,
        Commands\SystemHealthCheck::class,
    ];

    /**
     * تعريف جدول أوامر التطبيق.
     */
    protected function schedule(Schedule $schedule): void
    {
        // جدولة تقارير الأداء
        $schedule->command('notifications:report --period=daily')
                ->dailyAt('00:01');
        
        $schedule->command('notifications:report --period=weekly')
                ->weekly()
                ->sundays()
                ->at('00:30');
        
        $schedule->command('notifications:report --period=monthly')
                ->monthlyOn(1, '01:00');

        // جدولة تنظيف البيانات
        $schedule->command('notifications:cleanup')
                ->dailyAt('02:00');

        // مراقبة أداء النظام
        $schedule->command('system:check-payroll')
                ->everyThirtyMinutes();

        // تشغيل اختبار النظام كل يوم في الساعة 1 صباحاً
        $schedule->command('system:test')
                ->dailyAt('01:00')
                ->emailOutputOnFailure(config('mail.admin.address'));

        // فحص صحة النظام كل ساعة
        $schedule->command('system:health')
                ->hourly()
                ->emailOutputOnFailure(config('mail.admin.address'));
    }

    /**
     * تسجيل الأوامر للتطبيق.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
