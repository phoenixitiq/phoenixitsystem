<?php

namespace App\Services\Install;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

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
            Artisan::call('migrate:fresh', ['--force' => true]);
            
            // تنفيذ البذور
            Artisan::call('db:seed', ['--force' => true]);
            
            // إنشاء ملف التثبيت
            File::put(storage_path('installed'), date('Y-m-d H:i:s'));
            
            return true;
        } catch (Exception $e) {
            throw new Exception('فشل إعداد قاعدة البيانات: ' . $e->getMessage());
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

    public function checkRequirements()
    {
        return [
            'php' => $this->checkPHPversion(),
            'extensions' => $this->checkExtensions(),
            'directories' => $this->checkDirectories(),
            'environment' => $this->checkEnvironment()
        ];
    }

    private function checkPHPversion()
    {
        return [
            'version' => PHP_VERSION,
            'required' => '8.1.0',
            'status' => version_compare(PHP_VERSION, '8.1.0', '>=')
        ];
    }

    private function checkExtensions()
    {
        $required = [
            'BCMath',
            'Ctype',
            'Fileinfo',
            'JSON',
            'Mbstring',
            'OpenSSL',
            'PDO',
            'Tokenizer',
            'XML',
            'cURL',
            'GD'
        ];

        $results = [];
        foreach ($required as $ext) {
            $results[$ext] = extension_loaded(strtolower($ext));
        }

        return $results;
    }

    private function checkDirectories()
    {
        $directories = [
            'storage/app' => storage_path('app'),
            'storage/framework' => storage_path('framework'),
            'storage/logs' => storage_path('logs'),
            'bootstrap/cache' => base_path('bootstrap/cache'),
            'public/uploads' => public_path('uploads')
        ];

        $results = [];
        foreach ($directories as $name => $path) {
            $results[$name] = [
                'exists' => file_exists($path),
                'writable' => is_writable($path)
            ];
        }

        return $results;
    }

    private function checkEnvironment()
    {
        return [
            'env_file' => file_exists(base_path('.env')),
            'debug_mode' => config('app.debug'),
            'app_key' => !empty(config('app.key')),
            'app_url' => config('app.url')
        ];
    }
} 