<?php
require_once '../includes/installer.php';
require_once '../includes/connection.php';
require_once '../includes/error_handler.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?step=admin');
    exit;
}

// التحقق من CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    displayError('خطأ في التحقق من الأمان');
    exit;
}

// التحقق من البيانات
$admin_name = filter_input(INPUT_POST, 'admin_name', FILTER_SANITIZE_STRING);
$admin_email = filter_input(INPUT_POST, 'admin_email', FILTER_VALIDATE_EMAIL);
$admin_password = $_POST['admin_password'];
$confirm_password = $_POST['confirm_password'];

// التحقق من صحة البيانات
if (!$admin_name || !$admin_email || !$admin_password) {
    displayError('جميع الحقول مطلوبة');
    exit;
}

if ($admin_password !== $confirm_password) {
    displayError('كلمات المرور غير متطابقة');
    exit;
}

try {
    // تشفير كلمة المرور
    $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);
    
    // إدخال بيانات المدير في قاعدة البيانات
    $stmt = $pdo->prepare("
        INSERT INTO users (name, email, password, role, created_at) 
        VALUES (?, ?, ?, 'admin', NOW())
    ");
    
    $stmt->execute([$admin_name, $admin_email, $hashed_password]);
    
    // تحديث حالة التثبيت
    file_put_contents('../.installed', time());
    
    // الانتقال إلى الخطوة التالية
    header('Location: ../index.php?step=complete');
    exit;
    
} catch (PDOException $e) {
    displayError('خطأ في إنشاء حساب المدير: ' . $e->getMessage());
    exit;
} 