<?php

/**
 * Phoenix IT System
 * 
 * نقطة الدخول الرئيسية للنظام
 */

// بدء الجلسة في بداية الملف
session_start();

// تعريف ثابت بداية التطبيق
define('LARAVEL_START', microtime(true));

// التحقق من وجود ملف .env
if (!file_exists(__DIR__ . '/.env')) {
    header('Location: /public/install/');
    exit;
}

// تضمين autoload
require __DIR__.'/vendor/autoload.php';

// بدء التطبيق
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);

// تضمين ملف التثبيت
require_once 'includes/installer.php';

// التحقق من التثبيت المسبق
if (file_exists(__DIR__ . '/.installed')) {
    die('النظام مثبت مسبقاً');
}

// تحديد الخطوة الحالية
$step = isset($_GET['step']) ? $_GET['step'] : 'welcome';

// التحقق من صحة الخطوة
if (!in_array($step, Installer::$steps)) {
    $step = 'welcome';
}

// عرض الصفحة المطلوبة
include "templates/$step.php";

// تأكد من تضمين الفئة `Steps.php` بشكل صحيح
require_once 'includes/Steps.php';
