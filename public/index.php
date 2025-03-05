<?php

/**
 * Phoenix IT System
 * 
 * نقطة الدخول الرئيسية للنظام
 */

define('START_TIME', microtime(true));
define('ROOT_PATH', dirname(__DIR__));

// التحقق من وجود ملف .env
if (!file_exists(__DIR__ . '/../.env')) {
    // التحقق من وجود مجلد التثبيت
    if (file_exists(__DIR__ . '/install/index.php')) {
        // توجيه المستخدم إلى صفحة الترحيب في التثبيت
        header('Location: /install/?step=welcome');
        exit;
    } else {
        die('يرجى تثبيت النظام أولاً');
    }
}

// تضمين autoloader
require __DIR__.'/../vendor/autoload.php';

// تحميل ملف .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// تحميل الملفات الأساسية
require_once ROOT_PATH . '/app/Config/bootstrap.php';
require_once ROOT_PATH . '/app/Helpers/functions.php';

try {
    // تهيئة التطبيق
    $app = new App\Core\Application();
    
    // تحميل التكوينات
    $app->loadConfig();
    
    // تهيئة جلسة المستخدم
    $app->initSession();
    
    // تحديد اللغة
    $app->setLocale();
    
    // معالجة الطلب
    $app->handleRequest();
    
} catch (Exception $e) {
    // تسجيل الخطأ
    error_log($e->getMessage());
    
    // عرض صفحة الخطأ
    require_once ROOT_PATH . '/resources/views/errors/500.php';
}

// ترتيب الخطوات
$installation_steps = [
    'welcome',
    'requirements',
    'database',
    'admin',
    'complete'
];

// التحقق من الخطوة الحالية
$current_step = $_GET['step'] ?? 'welcome';
if (!in_array($current_step, $installation_steps)) {
    header('Location: ?step=welcome');
    exit;
}

// التحقق من الخطوة السابقة
$current_step_index = array_search($current_step, $installation_steps);
if ($current_step_index > 0) {
    $previous_step = $installation_steps[$current_step_index - 1];
    if (!isset($_SESSION['steps'][$previous_step])) {
        header('Location: ?step=' . $previous_step);
        exit;
    }
}

require_once 'path/to/Steps.php';
