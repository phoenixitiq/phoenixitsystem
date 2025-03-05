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

    // تنظيف وتحقق من بيانات المدير
    $adminData = [
        'name' => sanitizeInput($_POST['admin_name']),
        'email' => sanitizeInput($_POST['admin_email']),
        'username' => sanitizeUsername($_POST['admin_username']),
        'password' => $_POST['admin_password']
    ];

    // تسجيل بداية عملية التثبيت
    Logger::info('بدء عملية التثبيت', ['username' => $adminData['username']]);

    // إنشاء مثيل من Installer
    $installer = new Installer();

    // إنشاء حساب المدير
    $installer->createAdminAccount($adminData);
    Logger::info('تم إنشاء حساب المدير بنجاح');

    // إكمال عملية التثبيت
    $installer->completeInstallation();
    Logger::info('تم إكمال عملية التثبيت بنجاح');

    // حفظ البريد الإلكتروني للمدير في الجلسة
    $_SESSION['admin_email'] = $adminData['email'];

    // إرسال استجابة النجاح
    showSuccess('تم تثبيت النظام بنجاح', ['redirect' => '?step=complete']);

} catch (Exception $e) {
    Logger::error('فشل في عملية التثبيت', ['error' => $e->getMessage()]);
    showError($e->getMessage());
} 