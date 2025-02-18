<?php

/**
 * Phoenix IT System
 * نظام إدارة الخدمات التقنية
 */

// تحقق من وجود ملف التثبيت
if (!file_exists(__DIR__ . '/install/install.lock')) {
    header('Location: /install/');
    exit;
}

// توجيه كل الطلبات إلى public/index.php
require __DIR__ . '/public/index.php';