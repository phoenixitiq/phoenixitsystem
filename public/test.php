<?php
require_once dirname(__DIR__) . '/app/Config/bootstrap.php';

echo "<h1>فحص النظام</h1>";
echo "<pre>";

// فحص إصدار PHP
echo "PHP Version: " . PHP_VERSION . "\n";

// فحص الإضافات المطلوبة
$required_extensions = ['pdo', 'mbstring', 'json', 'curl'];
foreach ($required_extensions as $ext) {
    echo "Extension {$ext}: " . (extension_loaded($ext) ? "OK" : "Missing") . "\n";
}

// فحص المجلدات
$directories = [
    'storage/logs',
    'storage/cache',
    'storage/uploads'
];

foreach ($directories as $dir) {
    $path = ROOT_PATH . '/' . $dir;
    echo "Directory {$dir}: " . (is_writable($path) ? "Writable" : "Not writable") . "\n";
}

// فحص التكوين
echo "\nConfiguration Test:\n";
print_r(include ROOT_PATH . '/app/Config/config.php');

echo "</pre>"; 