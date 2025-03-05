<?php
/**
 * Phoenix IT System - Database Connection Test
 * Copyright (c) 2024 PHOENIX IT & MARKETING LTD
 */

require_once '../includes/functions.php';
header('Content-Type: application/json');

// التحقق من نوع الطلب
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'طريقة طلب غير صحيحة'
    ]);
    exit;
}

// قراءة البيانات المرسلة
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode([
        'success' => false,
        'message' => 'بيانات غير صحيحة'
    ]);
    exit;
}

try {
    // محاولة الاتصال بقاعدة البيانات
    $pdo = new PDO(
        "mysql:host={$data['host']};charset=utf8mb4",
        $data['username'],
        $data['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]
    );

    // التحقق من وجود قاعدة البيانات
    $stmt = $pdo->query("SHOW DATABASES LIKE '{$data['database']}'");
    $database_exists = $stmt->rowCount() > 0;

    if (!$database_exists) {
        // إنشاء قاعدة البيانات إذا لم تكن موجودة
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$data['database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }

    // حفظ بيانات الاتصال في الجلسة
    $_SESSION['db_config'] = $data;

    echo json_encode([
        'success' => true,
        'message' => 'تم الاتصال بنجاح',
        'database_created' => !$database_exists
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'فشل الاتصال: ' . $e->getMessage()
    ]);
} 