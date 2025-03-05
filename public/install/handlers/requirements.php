<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/security.php';
require_once '../includes/requirements.php';
require_once '../includes/logger.php';

try {
    // التحقق من الطلب
    Security::validateRequest();

    // إنشاء كائن فحص المتطلبات
    $requirements = new SystemRequirements();
    
    // تنفيذ الفحص
    $results = $requirements->checkAll();
    
    // الحصول على التوصيات
    $recommendations = $requirements->getRecommendations();
    
    // التحقق من وجود أخطاء حرجة
    $criticalErrors = [];
    
    // التحقق من إصدار PHP
    if (!$results['php']['version']['status']) {
        $criticalErrors[] = 'إصدار PHP غير متوافق';
    }
    
    // التحقق من الإضافات الضرورية
    foreach ($results['php']['extensions'] as $ext => $info) {
        if (!$info['status'] && in_array($ext, ['pdo', 'pdo_mysql', 'mbstring'])) {
            $criticalErrors[] = "الإضافة $ext غير متوفرة";
        }
    }
    
    // إرسال النتائج
    showSuccess('تم فحص متطلبات النظام بنجاح', [
        'results' => $results,
        'recommendations' => $recommendations,
        'critical_errors' => $criticalErrors,
        'can_proceed' => empty($criticalErrors)
    ]);

} catch (Exception $e) {
    Logger::error('فشل في فحص المتطلبات', ['error' => $e->getMessage()]);
    showError($e->getMessage());
}
