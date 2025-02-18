<?php

/**
 * Phoenix IT System - API Entry
 * نقطة دخول API
 */

header('Content-Type: application/json');

// تحميل إعدادات API
require_once '../bootstrap/api.php';

// تشغيل API
$api->run(); 