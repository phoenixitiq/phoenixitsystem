<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/security.php';
require_once '../includes/security_check.php';
require_once '../includes/logger.php';

try {
    // التحقق من الطلب
    Security::validateRequest();

    // إنشاء كائن فحص الأمان
    $checker = new SecurityCheck();
    
    // تنفيذ الفحوصات
    $results = $checker->runChecks();
    
    // إنشاء تقرير
    $report = $checker->generateReport();
    
    // تحليل النتائج
    $criticalIssues = 0;
    $warnings = 0;
    
    foreach ($results as $category) {
        foreach ($category as $check) {
            if ($check['status'] === 'danger') {
                $criticalIssues++;
            } elseif ($check['status'] === 'warning') {
                $warnings++;
            }
        }
    }
    
    // إرسال النتائج
    showSuccess('تم إكمال فحص الأمان بنجاح', [
        'report' => $report,
        'summary' => [
            'critical' => $criticalIssues,
            'warnings' => $warnings,
            'safe' => count($results) - ($criticalIssues + $warnings)
        ]
    ]);

} catch (Exception $e) {
    Logger::error('فشل في فحص الأمان', ['error' => $e->getMessage()]);
    showError($e->getMessage());
} 