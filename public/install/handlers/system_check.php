<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/security.php';
require_once '../includes/system_check.php';
require_once '../includes/logger.php';

try {
    // التحقق من الطلب
    Security::validateRequest();

    // إجراء الفحص الشامل للنظام
    $systemCheck = new SystemCheck();
    $results = $systemCheck->runDiagnostics();

    // تحديد ما إذا كان يمكن المتابعة
    $canProceed = empty($results['errors']);

    // إضافة معلومات إضافية للنتائج
    $results['system_info'] = [
        'php_version' => PHP_VERSION,
        'server_software' => $_SERVER['SERVER_SOFTWARE'],
        'server_os' => PHP_OS,
        'server_time' => date('Y-m-d H:i:s'),
        'memory_limit' => ini_get('memory_limit'),
        'max_execution_time' => ini_get('max_execution_time'),
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size')
    ];

    showSuccess('تم إجراء فحص النظام بنجاح', [
        'results' => $results,
        'can_proceed' => $canProceed
    ]);

} catch (Exception $e) {
    Logger::error('فشل في فحص النظام', ['error' => $e->getMessage()]);
    showError($e->getMessage());
} 