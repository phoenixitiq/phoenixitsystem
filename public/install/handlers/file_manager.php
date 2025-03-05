<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/security.php';
require_once '../includes/file_manager.php';
require_once '../includes/logger.php';

try {
    // التحقق من الطلب
    Security::validateRequest();

    // إنشاء كائن إدارة الملفات
    $fileManager = new FileManager();
    
    // تحديد نوع العملية
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'clean_temp':
            $result = $fileManager->cleanTempFiles();
            showSuccess('تم تنظيف الملفات المؤقتة بنجاح');
            break;

        case 'clean_logs':
            $result = $fileManager->cleanLogs();
            showSuccess('تم تنظيف ملفات السجلات بنجاح');
            break;

        case 'optimize':
            $result = $fileManager->optimizeStorage();
            showSuccess('تم تحسين التخزين بنجاح');
            break;

        case 'upload':
            if (!isset($_FILES['file'])) {
                throw new Exception('لم يتم تحديد ملف للتحميل');
            }
            $fileManager->validateFile($_FILES['file']);
            // هنا يمكن إضافة عملية التحميل الفعلية
            showSuccess('تم تحميل الملف بنجاح');
            break;

        default:
            throw new Exception('عملية غير صالحة');
    }

} catch (Exception $e) {
    Logger::error('فشل في عملية إدارة الملفات', ['error' => $e->getMessage()]);
    showError($e->getMessage());
} 