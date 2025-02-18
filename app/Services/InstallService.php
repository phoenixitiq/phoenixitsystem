<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;

class InstallService
{
    public function setupDatabase($config)
    {
        try {
            // إنشاء قاعدة البيانات
            $pdo = new \PDO(
                "mysql:host={$config['db_host']};port={$config['db_port']}",
                $config['db_username'],
                $config['db_password']
            );
            
            $pdo->exec("CREATE DATABASE IF NOT EXISTS {$config['db_database']} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            
            // تحديث ملف .env
            $this->updateEnvFile([
                'DB_HOST' => $config['db_host'],
                'DB_PORT' => $config['db_port'],
                'DB_DATABASE' => $config['db_database'],
                'DB_USERNAME' => $config['db_username'],
                'DB_PASSWORD' => $config['db_password']
            ]);
            
            // تنفيذ الترحيلات
            \Artisan::call('migrate:fresh', ['--force' => true]);
            
            // تنفيذ البذور
            \Artisan::call('db:seed', ['--force' => true]);
            
            return true;
        } catch (\Exception $e) {
            throw new \Exception('فشل إعداد قاعدة البيانات: ' . $e->getMessage());
        }
    }

    private function updateEnvFile($data)
    {
        $envFile = base_path('.env');
        $envContent = file_get_contents($envFile);

        foreach ($data as $key => $value) {
            $envContent = preg_replace(
                "/^{$key}=.*/m",
                "{$key}={$value}",
                $envContent
            );
        }

        file_put_contents($envFile, $envContent);
    }
} 