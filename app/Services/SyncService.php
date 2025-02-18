<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SyncService
{
    protected $backupService;
    protected $cpanel;
    
    public function __construct(BackupService $backupService)
    {
        $this->backupService = $backupService;
        $this->cpanel = config('cpanel');
    }

    public function syncSystem()
    {
        try {
            DB::beginTransaction();

            // مزامنة قاعدة البيانات
            $this->syncDatabase();
            
            // مزامنة الملفات
            $this->syncFiles();
            
            // مزامنة الإعدادات
            $this->syncSettings();
            
            // تحديث النظام
            $this->updateSystem();

            // إنشاء نسخة احتياطية
            $this->backupService->createBackup();

            DB::commit();

            Log::info('تمت المزامنة بنجاح');
            return [
                'success' => true,
                'message' => 'تمت المزامنة بنجاح'
            ];

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('فشل المزامنة: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    protected function syncDatabase()
    {
        $tables = config('sync.tables', []);
        foreach ($tables as $table) {
            $this->syncTable($table);
        }
    }

    protected function syncTable($table)
    {
        $lastSync = DB::table('data_syncs')
            ->where('type', 'table_' . $table)
            ->latest()
            ->first();

        $query = DB::table($table);
        if ($lastSync) {
            $query->where('updated_at', '>', $lastSync->completed_at);
        }

        $records = $query->get();

        foreach ($records as $record) {
            // مزامنة السجل
            $this->syncRecord($table, $record);
        }
    }

    protected function syncFiles()
    {
        $directories = config('sync.directories', []);
        foreach ($directories as $dir) {
            $this->syncDirectory($dir);
        }
    }

    protected function syncSettings()
    {
        $settings = DB::table('settings')->get();
        foreach ($settings as $setting) {
            cache()->forever('setting.' . $setting->key, $setting->value);
        }
    }

    public function syncEmails()
    {
        // مزامنة حسابات البريد
        $response = Http::withBasicAuth(
            $this->cpanel['username'],
            $this->cpanel['token']
        )->get($this->cpanel['api_url'] . '/email/accounts');

        return $response->json();
    }

    public function syncDatabases()
    {
        // مزامنة قواعد البيانات
        $response = Http::withBasicAuth(
            $this->cpanel['username'],
            $this->cpanel['token']
        )->get($this->cpanel['api_url'] . '/databases');

        return $response->json();
    }
} 