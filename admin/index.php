<?php

/**
 * Phoenix IT System - Admin Dashboard
 * لوحة تحكم النظام
 */

try {
    // التحقق من تسجيل الدخول
    require_once '../app/Auth.php';

    if (!Auth::check()) {
        header('Location: /login');
        exit;
    }

    // توجيه إلى لوحة التحكم
    require_once 'dashboard.php';
} catch (Exception $e) {
    error_log($e->getMessage());
    if (getenv('APP_ENV') !== 'production') {
        echo '<pre>' . $e->getMessage() . '</pre>';
    } else {
        include '../errors/500.php';
    }
} 