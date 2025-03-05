<?php

class Installer {
    private static $steps = ['welcome', 'requirements', 'database', 'admin', 'complete'];
    
    public static function getCurrentStep() {
        $step = isset($_GET['step']) ? $_GET['step'] : 'welcome';
        return in_array($step, self::$steps) ? $step : 'welcome';
    }

    public static function checkStep($step) {
        switch($step) {
            case 'welcome':
                return true;
            case 'requirements':
                return self::checkRequirements();
            case 'database':
                return self::checkDatabase();
            case 'admin':
                return self::checkDatabase() && self::checkRequirements();
            case 'complete':
                return self::checkAllSteps();
        }
    }

    public static function getNextStep($currentStep) {
        $currentIndex = array_search($currentStep, self::$steps);
        return isset(self::$steps[$currentIndex + 1]) ? self::$steps[$currentIndex + 1] : 'complete';
    }

    public static function checkRequirements() {
        $requirements = [
            'php' => version_compare(PHP_VERSION, '7.4.0', '>='),
            'pdo' => extension_loaded('pdo'),
            'mysql' => extension_loaded('pdo_mysql'),
            'writeable' => is_writable('../')
        ];
        return !in_array(false, $requirements);
    }

    private static function checkDatabase() {
        try {
            require_once 'connection.php';
            return isset($pdo);
        } catch(Exception $e) {
            return false;
        }
    }

    private static function checkAdminSetup() {
        try {
            require_once 'connection.php';
            $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'");
            return $stmt->fetchColumn() > 0;
        } catch(Exception $e) {
            return false;
        }
    }

    private static function checkAllSteps() {
        return self::checkRequirements() && 
               self::checkDatabase() && 
               self::checkAdminSetup();
    }

    public static function createDatabase($config) {
        try {
            $pdo = new PDO(
                "mysql:host={$config['host']}",
                $config['username'],
                $config['password']
            );
            
            $pdo->exec("CREATE DATABASE IF NOT EXISTS {$config['database']}");
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function importDatabase($config) {
        try {
            $pdo = new PDO(
                "mysql:host={$config['host']};dbname={$config['database']}",
                $config['username'],
                $config['password']
            );
            
            $sql = file_get_contents(__DIR__ . '/../database/structure.sql');
            $pdo->exec($sql);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // المتطلبات الأساسية
    private static $requirements = [
        'php' => '7.4.0',
        'extensions' => [
            'pdo',
            'pdo_mysql',
            'mbstring',
            'json'
        ],
        'writable_paths' => [
            '../config',
            '../storage'
        ]
    ];
} 