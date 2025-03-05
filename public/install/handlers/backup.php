<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/security.php';
require_once '../includes/connection.php';
require_once '../includes/backup.php';
require_once '../includes/logger.php';

try {
    // التحقق من الطلب
    Security::validateRequest();

    // التحقق من وجود اتصال قاعدة البيانات
    if (!isset($_SESSION['db_config'])) {
        throw new Exception('لم يتم إعداد قاعدة البيانات');
    }

    // إنشاء اتصال قاعدة البيانات
    $connection = new Connection($_SESSION['db_config']);
    
    // إنشاء كائن النسخ الاحتياطي
    $backup = new Backup($connection);

    // تحديد نوع العملية
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'create':
            $filename = $backup->createBackup();
            showSuccess('تم إنشاء نسخة احتياطية بنجاح', ['filename' => $filename]);
            break;

        case 'restore':
            if (!isset($_POST['filename'])) {
                throw new Exception('لم يتم تحديد ملف النسخة الاحتياطية');
            }
            $backup->restoreBackup($_POST['filename']);
            showSuccess('تم استعادة النسخة الاحتياطية بنجاح');
            break;

        case 'clean':
            $days = isset($_POST['days']) ? (int)$_POST['days'] : 7;
            $backup->cleanOldBackups($days);
            showSuccess('تم تنظيف النسخ الاحتياطية القديمة بنجاح');
            break;

        default:
            throw new Exception('عملية غير صالحة');
    }

} catch (Exception $e) {
    Logger::error('فشل في عملية النسخ الاحتياطي', ['error' => $e->getMessage()]);
    showError($e->getMessage());
} 