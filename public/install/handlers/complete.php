<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/security.php';
require_once '../includes/connection.php';
require_once '../includes/installer.php';
require_once '../includes/steps.php';
require_once '../includes/logger.php';

try {
    // التحقق من الطلب
    Security::validateRequest();

    // التحقق من اكتمال جميع الخطوات
    $steps = new Steps();
    if (!$steps->checkAllSteps()) {
        throw new Exception('لم يتم إكمال جميع خطوات التثبيت');
    }

    // إنشاء نسخة احتياطية من قاعدة البيانات
    if (!backupDatabase($_SESSION['db_config'])) {
        Logger::warning('فشل في إنشاء نسخة احتياطية من قاعدة البيانات');
    }

    // تحسين التثبيت
    if (!optimizeInstallation()) {
        Logger::warning('فشل في تحسين التثبيت');
    }

    // تنظيف ملفات التثبيت
    if (!cleanInstallationFiles()) {
        Logger::warning('فشل في تنظيف ملفات التثبيت');
    }

    // إنشاء مفتاح تشفير جديد
    $appKey = generateSecureKey();
    setEnvironmentValue('APP_KEY', $appKey);

    Logger::info('تم إكمال عملية التثبيت بنجاح');

    showSuccess('تم إكمال التثبيت بنجاح', [
        'redirect' => '../admin/login.php'
    ]);

} catch (Exception $e) {
    Logger::error('فشل في إكمال التثبيت', ['error' => $e->getMessage()]);
    showError($e->getMessage());
} 