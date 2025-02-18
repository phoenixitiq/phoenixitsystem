<?php

/**
 * Phoenix IT System
 * نقطة الدخول العامة
 */

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

// تعيين حد أقصى للذاكرة
ini_set('memory_limit', '256M');

// تعيين وقت أقصى للتنفيذ
set_time_limit(300);

// عرض جميع الأخطاء للتطوير
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('LARAVEL_START', microtime(true));

// تحميل الملفات الأساسية
require __DIR__ . '/../vendor/autoload.php';

// تشغيل التطبيق
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
