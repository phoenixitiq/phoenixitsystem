<?php
// تعيين إعدادات PHP
ini_set('display_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Baghdad');
mb_internal_encoding('UTF-8');

// تعيين إعدادات اللغة
setlocale(LC_ALL, 'ar_IQ.UTF-8');

// تعريف الثوابت
define('APP_VERSION', '1.0.0');
define('APP_DEBUG', true);
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('PUBLIC_PATH', ROOT_PATH . '/public');

// التحقق من المجلدات المطلوبة
$required_directories = [
    STORAGE_PATH . '/logs',
    STORAGE_PATH . '/cache',
    STORAGE_PATH . '/uploads'
];

foreach ($required_directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// تحميل الملفات الأساسية
require_once ROOT_PATH . '/vendor/autoload.php'; 