<?php

namespace Install;

use Illuminate\Support\Facades\Storage;

class PayrollSetup
{
    private $dbConfig;
    private $systemConfig;

    public function __construct()
    {
        $this->systemConfig = require_once 'config/system.php';
    }

    public function handleRequest($post)
    {
        // التحقق من الخطوة
        $step = isset($post['step']) ? (int)$post['step'] : 1;

        switch ($step) {
            case 1:
                return $this->checkRequirements();
            case 2:
                return $this->handleDatabase($post);
            case 3:
                return $this->handleAdmin($post);
            case 4:
                return $this->finalize();
            default:
                throw new \Exception('خطوة غير صالحة');
        }
    }

    private function handleDatabase($post)
    {
        // التحقق من وجود البيانات المطلوبة
        if (empty($post['db_host']) || empty($post['db_name']) || empty($post['db_user'])) {
            throw new \Exception('جميع حقول قاعدة البيانات مطلوبة');
        }

        $this->dbConfig = [
            'host' => $post['db_host'],
            'name' => $post['db_name'],
            'user' => $post['db_user'],
            'pass' => $post['db_pass'] ?? ''
        ];

        try {
            // محاولة الاتصال بالخادم أولاً
            $pdo = new \PDO(
                "mysql:host={$this->dbConfig['host']};charset=utf8mb4",
                $this->dbConfig['user'],
                $this->dbConfig['pass']
            );
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // إنشاء قاعدة البيانات إذا لم تكن موجودة
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$this->dbConfig['name']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            
            // الاتصال بقاعدة البيانات
            $pdo->exec("USE `{$this->dbConfig['name']}`");
            
            // إنشاء الجداول
            $this->createTables();
            
            // إدخال البيانات الأساسية
            $this->insertBasicData();
            
            return true;
        } catch (\PDOException $e) {
            throw new \Exception('فشل الاتصال بقاعدة البيانات: ' . $e->getMessage());
        }
    }

    private function createTables()
    {
        try {
            $sql = file_get_contents(__DIR__ . '/database/structure.sql');
            $pdo = $this->getPDO();
            $queries = array_filter(array_map('trim', explode(';', $sql)));
            
            foreach ($queries as $query) {
                if (!empty($query)) {
                    $pdo->exec($query);
                }
            }
        } catch (\PDOException $e) {
            throw new \Exception('فشل إنشاء الجداول: ' . $e->getMessage());
        }
    }

    private function insertBasicData()
    {
        try {
            $sql = file_get_contents(__DIR__ . '/database/basic_data.sql');
            $pdo = $this->getPDO();
            $queries = array_filter(array_map('trim', explode(';', $sql)));
            
            foreach ($queries as $query) {
                if (!empty($query)) {
                    $pdo->exec($query);
                }
            }
        } catch (\PDOException $e) {
            throw new \Exception('فشل إدخال البيانات الأساسية: ' . $e->getMessage());
        }
    }

    private function getPDO()
    {
        return new \PDO(
            "mysql:host={$this->dbConfig['host']};dbname={$this->dbConfig['name']};charset=utf8mb4",
            $this->dbConfig['user'],
            $this->dbConfig['pass'],
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
        );
    }

    private function checkRequirements()
    {
        // فحص المتطلبات كما هو
        return true;
    }

    private function handleAdmin($post)
    {
        // معالجة بيانات المدير
        return true;
    }

    private function finalize()
    {
        // إنهاء التثبيت
        return true;
    }

    private function setupSystemFiles()
    {
        // إنشاء ملف .env
        $envExample = file_get_contents(__DIR__ . '/config/.env.example');
        $env = str_replace([
            'DB_HOST=127.0.0.1',
            'DB_DATABASE=laravel',
            'DB_USERNAME=root',
            'DB_PASSWORD='
        ], [
            "DB_HOST={$this->dbConfig['host']}",
            "DB_DATABASE={$this->dbConfig['name']}",
            "DB_USERNAME={$this->dbConfig['user']}",
            "DB_PASSWORD={$this->dbConfig['pass']}"
        ], $envExample);
        
        file_put_contents(dirname(__DIR__) . '/.env', $env);

        // نسخ الصور
        if (!file_exists(dirname(__DIR__) . '/public/images')) {
            mkdir(dirname(__DIR__) . '/public/images', 0755, true);
        }
        copy(__DIR__ . '/assets/images/logo.png', dirname(__DIR__) . '/public/images/logo.png');
        copy(__DIR__ . '/assets/images/favicon.ico', dirname(__DIR__) . '/public/images/favicon.ico');
    }

    private function createLockFile()
    {
        file_put_contents(__DIR__ . '/install.lock', date('Y-m-d H:i:s'));
    }
} 