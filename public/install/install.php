<?php

/**
 * التحقق من متطلبات النظام
 */
function checkRequirements()
{
    $requirements = [
        'php' => [
            'version' => '8.2.0',
            'current' => PHP_VERSION,
            'status' => version_compare(PHP_VERSION, '8.2.0', '>=')
        ],
        'extensions' => [
            'pdo' => extension_loaded('pdo'),
            'pdo_mysql' => extension_loaded('pdo_mysql'),
            'mbstring' => extension_loaded('mbstring')
        ],
        'permissions' => [
            '/storage' => is_writable(BASE_PATH . '/storage'),
            '/.env' => is_writable(BASE_PATH)
        ]
    ];

    return $requirements;
}

/**
 * إنشاء اتصال قاعدة البيانات
 */
function createDatabaseConnection($host, $database, $username, $password)
{
    try {
        $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `$database`");
        return $pdo;
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * تثبيت قاعدة البيانات
 */
function installDatabase($pdo)
{
    try {
        $sql = file_get_contents(INSTALL_PATH . '/database/structure.sql');
        $pdo->exec($sql);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * إنشاء ملف .env
 */
function createEnvFile($data)
{
    $env = file_get_contents(BASE_PATH . '/.env.example');
    $env = str_replace('DB_HOST=127.0.0.1', "DB_HOST={$data['db_host']}", $env);
    $env = str_replace('DB_DATABASE=laravel', "DB_DATABASE={$data['db_name']}", $env);
    $env = str_replace('DB_USERNAME=root', "DB_USERNAME={$data['db_user']}", $env);
    $env = str_replace('DB_PASSWORD=', "DB_PASSWORD={$data['db_pass']}", $env);
    $env = str_replace('APP_KEY=', "APP_KEY=" . 'base64:' . base64_encode(random_bytes(32)), $env);
    
    return file_put_contents(BASE_PATH . '/.env', $env);
}

/**
 * إنشاء حساب المدير
 */
function createAdminAccount($pdo, $data)
{
    try {
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'super_admin')");
        return $stmt->execute([$data['name'], $data['email'], $password]);
    } catch (PDOException $e) {
        return false;
    }
} 