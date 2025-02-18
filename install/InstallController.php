<?php

namespace Install;

use Exception;
use PDO;
use PDOException;

class InstallController
{
    private $currentStep = 1;
    private $config;
    private $errors = [];
    private $success = '';

    public function __construct()
    {
        $this->config = require __DIR__ . '/config/system.php';
        $this->checkInstallation();
    }

    public function handleRequest($post = [])
    {
        if (!empty($post)) {
            $this->processStep($post);
        }
        
        return $this->renderStep();
    }

    private function checkInstallation()
    {
        if (file_exists(__DIR__ . '/install.lock')) {
            die('النظام مثبت مسبقاً. يرجى حذف مجلد التثبيت.');
        }
    }

    private function processStep($data)
    {
        switch ($this->currentStep) {
            case 1: // التحقق من المتطلبات
                if ($this->checkRequirements()) {
                    $this->currentStep = 2;
                }
                break;

            case 2: // إعداد قاعدة البيانات
                if ($this->setupDatabase($data)) {
                    $this->currentStep = 3;
                }
                break;

            case 3: // إعداد النظام
                if ($this->setupSystem($data)) {
                    $this->currentStep = 4;
                }
                break;

            case 4: // إنشاء حساب المدير
                if ($this->createAdminAccount($data)) {
                    $this->finishInstallation();
                }
                break;
        }
    }

    private function checkRequirements()
    {
        $requirements = new RequirementsChecker();
        $results = $requirements->check();

        if (!$results['status']) {
            $this->errors = $results['errors'];
            return false;
        }

        return true;
    }

    private function setupDatabase($data)
    {
        try {
            $dsn = "mysql:host={$data['db_host']};port={$data['db_port']}";
            $pdo = new PDO($dsn, $data['db_username'], $data['db_password']);
            
            // إنشاء قاعدة البيانات
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$data['db_database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            
            // حفظ إعدادات قاعدة البيانات
            $this->updateEnvFile([
                'DB_HOST' => $data['db_host'],
                'DB_PORT' => $data['db_port'],
                'DB_DATABASE' => $data['db_database'],
                'DB_USERNAME' => $data['db_username'],
                'DB_PASSWORD' => $data['db_password']
            ]);

            return true;
        } catch (PDOException $e) {
            $this->errors[] = 'خطأ في الاتصال بقاعدة البيانات: ' . $e->getMessage();
            return false;
        }
    }

    private function setupSystem($data)
    {
        try {
            // تحديث إعدادات النظام
            $this->updateEnvFile([
                'APP_NAME' => $data['site_name'],
                'APP_URL' => $data['site_url'],
                'APP_ENV' => 'production',
                'APP_DEBUG' => 'false'
            ]);

            // إنشاء الجداول
            $this->importDatabase();

            return true;
        } catch (Exception $e) {
            $this->errors[] = 'خطأ في إعداد النظام: ' . $e->getMessage();
            return false;
        }
    }

    private function createAdminAccount($data)
    {
        try {
            // التحقق من صحة البيانات
            if ($data['admin_password'] !== $data['admin_password_confirmation']) {
                $this->errors[] = 'كلمات المرور غير متطابقة';
                return false;
            }

            // إنشاء حساب المدير
            $pdo = $this->getDatabaseConnection();
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')");
            $stmt->execute([
                $data['admin_name'],
                $data['admin_email'],
                password_hash($data['admin_password'], PASSWORD_DEFAULT)
            ]);

            return true;
        } catch (Exception $e) {
            $this->errors[] = 'خطأ في إنشاء حساب المدير: ' . $e->getMessage();
            return false;
        }
    }

    private function finishInstallation()
    {
        // إنشاء ملف القفل
        file_put_contents(__DIR__ . '/install.lock', date('Y-m-d H:i:s'));
        
        $this->success = 'تم تثبيت النظام بنجاح!';
        $this->currentStep = 5;
    }

    private function updateEnvFile($values)
    {
        $envFile = dirname(__DIR__) . '/.env';
        $envContent = file_get_contents($envFile);

        foreach ($values as $key => $value) {
            $envContent = preg_replace(
                "/^{$key}=.*/m",
                "{$key}={$value}",
                $envContent
            );
        }

        file_put_contents($envFile, $envContent);
    }

    private function importDatabase()
    {
        $sql = file_get_contents(__DIR__ . '/database/structure.sql');
        $pdo = $this->getDatabaseConnection();
        $pdo->exec($sql);

        // إدخال البيانات الأساسية
        $sql = file_get_contents(__DIR__ . '/database/basic_data.sql');
        $pdo->exec($sql);
    }

    private function getDatabaseConnection()
    {
        $env = parse_ini_file(dirname(__DIR__) . '/.env');
        $dsn = "mysql:host={$env['DB_HOST']};port={$env['DB_PORT']};dbname={$env['DB_DATABASE']}";
        return new PDO($dsn, $env['DB_USERNAME'], $env['DB_PASSWORD']);
    }

    private function renderStep()
    {
        $data = [
            'currentStep' => $this->currentStep,
            'config' => $this->config,
            'errors' => $this->errors,
            'success' => $this->success
        ];

        switch ($this->currentStep) {
            case 1:
                return $this->renderView('requirements', $data);
            case 2:
                return $this->renderView('database', $data);
            case 3:
                return $this->renderView('system', $data);
            case 4:
                return $this->renderView('admin', $data);
            case 5:
                return $this->renderView('finish', $data);
            default:
                return $this->renderView('welcome', $data);
        }
    }

    private function renderView($view, $data)
    {
        extract($data);
        ob_start();
        include __DIR__ . "/views/{$view}.php";
        return ob_get_clean();
    }
} 