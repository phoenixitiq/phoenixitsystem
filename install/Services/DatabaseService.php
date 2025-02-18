<?php

namespace Install\Services;

use PDO;
use PDOException;

class DatabaseService
{
    public function testConnection($config)
    {
        try {
            $dsn = "mysql:host={$config['host']};port={$config['port']}";
            $pdo = new PDO($dsn, $config['username'], $config['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function createDatabase($config)
    {
        try {
            $dsn = "mysql:host={$config['host']};port={$config['port']}";
            $pdo = new PDO($dsn, $config['username'], $config['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $dbname = $config['database'];
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            return true;
        } catch (PDOException $e) {
            throw new \Exception('فشل إنشاء قاعدة البيانات: ' . $e->getMessage());
        }
    }

    public function importSchema($config)
    {
        try {
            $dsn = "mysql:host={$config['host']};port={$config['port']}";
            $pdo = new PDO($dsn, $config['username'], $config['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // إنشاء قاعدة البيانات إذا لم تكن موجودة
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$config['database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("USE `{$config['database']}`");
            
            // تعطيل فحص المفاتيح الأجنبية مؤقتاً
            $pdo->exec('SET FOREIGN_KEY_CHECKS=0');
            
            // تنفيذ ملفات SQL بالترتيب
            $sqlFiles = glob(__DIR__ . '/../database/[0-9]*.sql');
            sort($sqlFiles);
            
            foreach ($sqlFiles as $file) {
                $sql = file_get_contents($file);
                $statements = array_filter(array_map('trim', explode(';', $sql)));
                
                foreach ($statements as $statement) {
                    if (!empty($statement)) {
                        try {
                            $pdo->exec($statement);
                        } catch (PDOException $e) {
                            error_log("Error in file {$file}: " . $e->getMessage());
                            error_log("Statement: {$statement}");
                            throw $e;
                        }
                    }
                }
            }
            
            // إعادة تفعيل فحص المفاتيح الأجنبية
            $pdo->exec('SET FOREIGN_KEY_CHECKS=1');
            
            return true;
        } catch (PDOException $e) {
            throw new \Exception('فشل استيراد هيكل قاعدة البيانات: ' . $e->getMessage());
        }
    }
} 