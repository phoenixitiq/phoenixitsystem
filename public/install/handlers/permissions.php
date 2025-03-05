<?php
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['directory'])) {
    $directory = $_POST['directory'];
    $validDirectories = ['storage', 'cache', 'logs'];
    
    if (!in_array($directory, $validDirectories)) {
        echo json_encode(['success' => false, 'message' => 'مجلد غير صالح']);
        exit;
    }

    // إعادة تشغيل إعداد المجلدات
    $results = setupDirectories();
    $path = ROOT_PATH . '/' . ($directory === 'storage' ? 'storage' : 'storage/' . $directory);
    
    if (isset($results[$path])) {
        echo json_encode([
            'success' => $results[$path]['status'],
            'message' => $results[$path]['status'] ? 'تم إصلاح الصلاحيات بنجاح' : $results[$path]['error']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'حدث خطأ غير متوقع']);
    }
    exit;
} 