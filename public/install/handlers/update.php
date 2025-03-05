<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/security.php';
require_once '../includes/connection.php';
require_once '../includes/updater.php';
require_once '../includes/logger.php';

try {
    // التحقق من الطلب
    Security::validateRequest();

    // إنشاء كائن التحديث
    $updater = new Updater();
    
    // تحديد نوع العملية
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'check':
            $updateInfo = $updater->checkForUpdates();
            showSuccess('تم التحقق من التحديثات بنجاح', $updateInfo);
            break;

        case 'download':
            if (!isset($_POST['version'])) {
                throw new Exception('لم يتم تحديد إصدار التحديث');
            }
            $filepath = $updater->downloadUpdate($_POST['version']);
            showSuccess('تم تحميل التحديث بنجاح', ['filepath' => $filepath]);
            break;

        case 'install':
            if (!isset($_POST['filepath'])) {
                throw new Exception('لم يتم تحديد ملف التحديث');
            }
            $updater->installUpdate($_POST['filepath']);
            showSuccess('تم تثبيت التحديث بنجاح');
            break;

        default:
            throw new Exception('عملية غير صالحة');
    }

} catch (Exception $e) {
    Logger::error('فشل في عملية التحديث', ['error' => $e->getMessage()]);
    showError($e->getMessage());
} 