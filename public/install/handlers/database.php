<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/security.php';
require_once '../includes/connection.php';
require_once '../includes/logger.php';

try {
    // التحقق من الطلب
    Security::validateRequest();

    // التحقق من البيانات المطلوبة
    $required = ['host', 'port', 'username', 'password', 'db_name', 'prefix'];
    validateRequiredFields($_POST, $required);

    // تنظيف وتحقق من البيانات
    $config = [
        'host' => Security::sanitizeInput($_POST['host']),
        'port' => (int)$_POST['port'],
        'username' => Security::sanitizeInput($_POST['username']),
        'password' => $_POST['password'],
        'db_name' => Security::sanitizeInput($_POST['db_name']),
        'prefix' => Security::sanitizeInput($_POST['prefix'])
    ];

    // التحقق من صحة البيانات
    if ($config['port'] < 1 || $config['port'] > 65535) {
        throw new Exception('منفذ غير صالح');
    }

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $config['prefix'])) {
        throw new Exception('بادئة الجداول غير صالحة');
    }

    // محاولة الاتصال بقاعدة البيانات
    $connection = new Connection($config);
    
    // حفظ بيانات الاتصال في الجلسة
    $_SESSION['db_config'] = $config;

    // التحقق من وجود قاعدة البيانات
    $connection->execute("CREATE DATABASE IF NOT EXISTS `{$config['db_name']}`");
    $connection->execute("USE `{$config['db_name']}`");

    // التحقق من الجداول الموجودة
    $tables = $connection->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    showSuccess('تم الاتصال بقاعدة البيانات بنجاح', [
        'tables_exist' => !empty($tables),
        'redirect' => 'index.php?step=admin'
    ]);

} catch (Exception $e) {
    Logger::error('فشل في إعداد قاعدة البيانات', ['error' => $e->getMessage()]);
    showError($e->getMessage());
}
