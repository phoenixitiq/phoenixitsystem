<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/security.php';
require_once '../includes/connection.php';
require_once '../includes/installer.php';
require_once '../includes/logger.php';

try {
    // التحقق من الطلب
    Security::validateRequest();

    // التحقق من البيانات المطلوبة
    $required = ['admin_name', 'admin_username', 'admin_email', 'admin_password', 'admin_password_confirm'];
    validateRequiredFields($_POST, $required);

    // تنظيف وتحقق من البيانات
    $data = [
        'name' => Security::sanitizeInput($_POST['admin_name']),
        'username' => Security::sanitizeInput($_POST['admin_username']),
        'email' => Security::sanitizeInput($_POST['admin_email']),
        'password' => $_POST['admin_password'],
        'password_confirm' => $_POST['admin_password_confirm']
    ];

    // التحقق من صحة البيانات
    Security::validateUsername($data['username']);
    Security::validateEmail($data['email']);
    Security::validatePassword($data['password']);

    // التحقق من تطابق كلمتي المرور
    if ($data['password'] !== $data['password_confirm']) {
        throw new Exception('كلمتا المرور غير متطابقتين');
    }

    // إنشاء كائن التثبيت
    $installer = new Installer();

    // تنفيذ عملية التثبيت
    $result = $installer->install([
        'admin_name' => $data['name'],
        'admin_username' => $data['username'],
        'admin_email' => $data['email'],
        'admin_password' => $data['password'],
        'db_config' => $_SESSION['db_config']
    ]);

    // تنظيف بيانات الجلسة
    unset($_SESSION['db_config']);

    // إنشاء ملف .env
    $envData = [
        'DB_HOST' => $_SESSION['db_config']['host'],
        'DB_PORT' => $_SESSION['db_config']['port'],
        'DB_DATABASE' => $_SESSION['db_config']['db_name'],
        'DB_USERNAME' => $_SESSION['db_config']['username'],
        'DB_PASSWORD' => $_SESSION['db_config']['password'],
        'DB_PREFIX' => $_SESSION['db_config']['prefix'],
        'APP_URL' => rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/'),
        'APP_ENV' => 'production',
        'APP_DEBUG' => 'false',
        'APP_KEY' => bin2hex(random_bytes(32))
    ];

    if (!createEnvFile($envData)) {
        throw new Exception('فشل في إنشاء ملف .env');
    }

    // إنشاء ملف القفل
    $lockFile = ROOT_PATH . '/storage/installed.lock';
    if (!file_put_contents($lockFile, date('Y-m-d H:i:s'))) {
        throw new Exception('فشل في إنشاء ملف القفل');
    }

    showSuccess('تم تثبيت النظام بنجاح', [
        'redirect' => '../admin/login.php'
    ]);

} catch (Exception $e) {
    Logger::error('فشل في إكمال التثبيت', ['error' => $e->getMessage()]);
    showError($e->getMessage());
}
