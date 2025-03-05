<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SystemCheckService;

class SystemHealthCheck extends Command
{
    protected $signature = 'system:health';
    protected $description = 'تشغيل فحص شامل لصحة النظام';

    public function handle()
    {
        $this->info('بدء فحص النظام...');

        // فحص قاعدة البيانات
        $this->checkDatabase();

        // فحص الملفات
        $this->checkStorage();

        // فحص الخدمات
        $this->checkServices();

        // فحص الأمان
        $this->checkSecurity();

        $this->info('اكتمل فحص النظام');
    }

    private function checkDatabase()
    {
        $this->info('فحص قاعدة البيانات...');
        try {
            DB::connection()->getPdo();
            $this->info('✓ الاتصال بقاعدة البيانات يعمل');
            
            // فحص الجداول الأساسية
            $tables = ['users', 'roles', 'permissions', 'settings'];
            foreach ($tables as $table) {
                if (Schema::hasTable($table)) {
                    $this->info("✓ جدول {$table} موجود");
                } else {
                    $this->error("× جدول {$table} غير موجود");
                }
            }
        } catch (\Exception $e) {
            $this->error('× مشكلة في قاعدة البيانات: ' . $e->getMessage());
        }
    }

    private function checkStorage()
    {
        $this->info('فحص نظام الملفات...');
        $disks = ['local', 'public', 's3'];
        
        foreach ($disks as $disk) {
            try {
                if (Storage::disk($disk)->put('test.txt', 'test')) {
                    Storage::disk($disk)->delete('test.txt');
                    $this->info("✓ القرص {$disk} يعمل");
                }
            } catch (\Exception $e) {
                $this->error("× مشكلة في القرص {$disk}: " . $e->getMessage());
            }
        }
    }

    private function checkServices()
    {
        $this->info('فحص الخدمات...');
        
        // فحص Redis
        try {
            Redis::ping();
            $this->info('✓ Redis يعمل');
        } catch (\Exception $e) {
            $this->error('× مشكلة في Redis: ' . $e->getMessage());
        }

        // فحص Queue
        try {
            Queue::size();
            $this->info('✓ Queue يعمل');
        } catch (\Exception $e) {
            $this->error('× مشكلة في Queue: ' . $e->getMessage());
        }
    }

    private function checkSecurity()
    {
        $this->info('فحص الأمان...');
        
        // فحص مفتاح التطبيق
        if (config('app.key')) {
            $this->info('✓ مفتاح التطبيق موجود');
        } else {
            $this->error('× مفتاح التطبيق غير موجود');
        }

        // فحص صلاحيات الملفات
        $paths = [
            storage_path(),
            base_path('bootstrap/cache')
        ];

        foreach ($paths as $path) {
            if (is_writable($path)) {
                $this->info("✓ المسار {$path} قابل للكتابة");
            } else {
                $this->error("× المسار {$path} غير قابل للكتابة");
            }
        }
    }
} 