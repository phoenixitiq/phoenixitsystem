<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class TestSystem extends Command
{
    protected $signature = 'system:test {--detailed}';
    protected $description = 'اختبار شامل للنظام';

    public function handle()
    {
        $this->info('بدء اختبار النظام...');
        $results = [];

        // اختبار قاعدة البيانات
        $results['database'] = $this->testDatabase();

        // اختبار التخزين
        $results['storage'] = $this->testStorage();

        // اختبار البريد
        $results['mail'] = $this->testMail();

        // اختبار النسخ الاحتياطي
        $results['backup'] = $this->testBackup();

        // اختبار الأمان
        $results['security'] = $this->testSecurity();

        $this->displayResults($results);
    }

    private function testDatabase()
    {
        try {
            // اختبار الاتصال
            DB::connection()->getPdo();
            
            // اختبار الجداول الأساسية
            $requiredTables = [
                'users', 'roles', 'permissions', 'settings'
            ];
            
            $missingTables = [];
            foreach ($requiredTables as $table) {
                if (!Schema::hasTable($table)) {
                    $missingTables[] = $table;
                }
            }

            return [
                'status' => empty($missingTables),
                'message' => empty($missingTables) ? 'قاعدة البيانات تعمل بشكل صحيح' : 'جداول مفقودة: ' . implode(', ', $missingTables)
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
            ];
        }
    }

    private function testStorage()
    {
        try {
            $testFile = 'test_' . time() . '.txt';
            
            // اختبار التخزين المحلي
            Storage::disk('local')->put($testFile, 'test');
            $exists = Storage::disk('local')->exists($testFile);
            Storage::disk('local')->delete($testFile);

            return [
                'status' => $exists,
                'message' => $exists ? 'نظام التخزين يعمل بشكل صحيح' : 'مشكلة في نظام التخزين'
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'خطأ في نظام التخزين: ' . $e->getMessage()
            ];
        }
    }

    private function testMail()
    {
        try {
            Mail::raw('اختبار النظام', function($message) {
                $message->to(config('mail.from.address'))
                        ->subject('اختبار النظام');
            });

            return [
                'status' => true,
                'message' => 'نظام البريد يعمل بشكل صحيح'
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'خطأ في نظام البريد: ' . $e->getMessage()
            ];
        }
    }

    private function testBackup()
    {
        try {
            $backupPath = storage_path('app/backups');
            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0755, true);
            }

            return [
                'status' => is_writable($backupPath),
                'message' => is_writable($backupPath) ? 'نظام النسخ الاحتياطي جاهز' : 'مشكلة في صلاحيات مجلد النسخ الاحتياطي'
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'خطأ في نظام النسخ الاحتياطي: ' . $e->getMessage()
            ];
        }
    }

    private function testSecurity()
    {
        $checks = [
            'app_key' => !empty(config('app.key')),
            'debug_mode' => !config('app.debug'),
            'file_permissions' => $this->checkFilePermissions(),
            'env_file' => file_exists(base_path('.env')),
        ];

        return [
            'status' => !in_array(false, $checks),
            'message' => !in_array(false, $checks) ? 'إعدادات الأمان صحيحة' : 'مشاكل في إعدادات الأمان'
        ];
    }

    private function checkFilePermissions()
    {
        $paths = [
            storage_path(),
            base_path('bootstrap/cache')
        ];

        foreach ($paths as $path) {
            if (!is_writable($path)) {
                return false;
            }
        }

        return true;
    }

    private function displayResults($results)
    {
        $this->newLine();
        $this->info('نتائج الاختبار:');
        $this->newLine();

        $headers = ['النظام', 'الحالة', 'الرسالة'];
        $rows = [];

        foreach ($results as $system => $result) {
            $rows[] = [
                $system,
                $result['status'] ? '✅' : '❌',
                $result['message']
            ];
        }

        $this->table($headers, $rows);

        $allPassed = !in_array(false, array_column($results, 'status'));
        
        $this->newLine();
        if ($allPassed) {
            $this->info('✅ جميع الاختبارات ناجحة. النظام جاهز للاستخدام.');
        } else {
            $this->error('❌ بعض الاختبارات فشلت. يرجى مراجعة النتائج أعلاه.');
        }
    }
} 