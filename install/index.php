<?php

/**
 * Phoenix IT System - Installation
 * برنامج التثبيت
 */

// التحقق من وجود ملف التثبيت
if (file_exists(__DIR__ . '/install.lock')) {
    die('تم تثبيت النظام مسبقاً. لإعادة التثبيت، يرجى حذف ملف install.lock');
}

// تحميل الملفات المطلوبة
require_once __DIR__ . '/Services/RequirementsChecker.php';
require_once __DIR__ . '/Services/DatabaseService.php';
require_once __DIR__ . '/Services/EnvironmentService.php';
require_once __DIR__ . '/Controllers/InstallController.php';

// بدء التثبيت
try {
    $installer = new Install\Controllers\InstallController();
    $step = isset($_GET['step']) ? (int)$_GET['step'] : 0;
    $installer->handle($step);
} catch (Exception $e) {
    die("<div class='alert alert-danger'>
            <h4>حدث خطأ أثناء التثبيت</h4>
            <p>{$e->getMessage()}</p>
            <a href='.' class='btn btn-primary'>المحاولة مرة أخرى</a>
         </div>");
}

// توجيه كل الطلبات إلى index.php
require_once 'bootstrap.php';