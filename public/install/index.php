<?php
/**
 * Phoenix IT System - Installation Wizard
 * Copyright (c) 2024 PHOENIX IT & MARKETING LTD
 */

// منع الوصول المباشر للملف
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__));
}

// تعطيل الذاكرة المؤقتة
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html; charset=UTF-8');

// إظهار جميع الأخطاء في بيئة التطوير فقط
if (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] === 'localhost') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// تعريف المسارات
define('INSTALL_PATH', __DIR__);
define('ROOT_PATH', dirname(dirname(__DIR__)));
define('CSRF_TOKEN_NAME', 'csrf_token');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATION', 'utf8mb4_unicode_ci');

// التحقق من وجود ملف .htaccess وإنشاؤه إذا لم يكن موجوداً
$htaccess = INSTALL_PATH . '/.htaccess';
if (!file_exists($htaccess)) {
    $htContent = <<<EOT
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /public/install/
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php [L,QSA]
</IfModule>

<FilesMatch "^\.">
    Order allow,deny
    Deny from all
</FilesMatch>

Options -Indexes
DirectoryIndex index.php

<Files *.php>
    ForceType application/x-httpd-php
</Files>
EOT;
    file_put_contents($htaccess, $htContent);
}

// تهيئة الجلسة
session_start();

// تعيين الترميز
header('Content-Type: text/html; charset=UTF-8');

// تعريف المسارات
define('INSTALL_PATH', __DIR__);
define('ROOT_PATH', dirname(dirname(__DIR__)));

// تضمين الملفات الأساسية
require_once INSTALL_PATH . '/includes/config.php';
require_once INSTALL_PATH . '/includes/functions.php';

try {
    // التحقق من متطلبات النظام
    checkSystemRequirements();
    
    // تحديد الخطوة الحالية
    $step = isset($_GET['step']) ? $_GET['step'] : 'welcome';
    $stepFile = INSTALL_PATH . '/steps/' . $step . '.php';
    
    if (!file_exists($stepFile)) {
        throw new Exception('الصفحة غير موجودة');
    }
    
    // عرض الصفحة
    require_once INSTALL_PATH . '/includes/header.php';
    require_once $stepFile;
    require_once INSTALL_PATH . '/includes/footer.php';
    
} catch (Exception $e) {
    // عرض صفحة الخطأ
    require_once INSTALL_PATH . '/includes/error.php';
}

/**
 * عرض رسالة الخطأ
 */
function displayError($e) {
    ?>
    <!DOCTYPE html>
    <html dir="rtl" lang="ar">
    <head>
        <meta charset="UTF-8">
        <title>خطأ في التثبيت</title>
        <style>
            body { 
                font-family: system-ui, -apple-system, sans-serif; 
                margin: 20px; 
                direction: rtl;
                background: #f8fafc;
            }
            .error-container { 
                background: #fff; 
                border: 1px solid #dc2626; 
                padding: 20px;
                border-radius: 8px;
                max-width: 600px;
                margin: 50px auto;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            h1 {
                color: #dc2626;
                margin-top: 0;
            }
            p {
                line-height: 1.6;
            }
            .back-link {
                display: inline-block;
                padding: 10px 20px;
                background: #2563eb;
                color: #fff;
                text-decoration: none;
                border-radius: 4px;
                margin-top: 20px;
            }
            .back-link:hover {
                background: #1d4ed8;
            }
        </style>
    </head>
    <body>
        <div class="error-container">
            <h1>خطأ في التثبيت</h1>
            <p><?php echo htmlspecialchars($e->getMessage()); ?></p>
            <p>يرجى التواصل مع مطور النظام لمعالجة هذا المشكل.</p>
            <a href="index.php" class="back-link">العودة للصفحة الرئيسية</a>
        </div>
    </body>
    </html>
    <?php
}

// Force PHP execution
if (substr(php_sapi_name(), 0, 3) == 'cgi') {
    header("Status: 200 OK");
}
header("Content-Type: text/html; charset=UTF-8");

// Simple test output
echo "<!DOCTYPE html>
<html dir='rtl' lang='ar'>
<head>
    <meta charset='UTF-8'>
    <title>اختبار التثبيت</title>
</head>
<body>
    <h1>نظام التثبيت يعمل</h1>
    <p>إصدار PHP: " . phpversion() . "</p>
</body>
</html>";
