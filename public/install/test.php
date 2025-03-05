<?php
// تعيين نوع المحتوى للتأكد من عرض النص العربي بشكل صحيح
header('Content-Type: text/html; charset=UTF-8');

// تعريف المسار الرئيسي
define('ROOT_PATH', dirname(__FILE__));

// دالة فحص المجلد
function checkDirectory($path) {
    if (!file_exists($path)) {
        return false;
    }
    return is_writable($path);
}

// دالة تنسيق النتيجة
function formatResult($status) {
    return $status ? '<span style="color: green">✓</span>' : '<span style="color: red">✗</span>';
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>فحص النظام</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>فحص متطلبات النظام</h1>
    
    <h2>إصدار PHP</h2>
    <p>الإصدار الحالي: <?php echo PHP_VERSION; ?> <?php echo formatResult(version_compare(PHP_VERSION, '7.4.0', '>=')); ?></p>

    <h2>الإضافات المطلوبة</h2>
    <ul>
        <?php
        $extensions = ['pdo', 'pdo_mysql', 'mbstring', 'json'];
        foreach ($extensions as $ext) {
            echo "<li>$ext: " . formatResult(extension_loaded($ext)) . "</li>";
        }
        ?>
    </ul>

    <h2>المجلدات</h2>
    <ul>
        <?php
        $directories = [
            '../storage/logs' => 'مجلد السجلات',
            '../storage/cache' => 'مجلد التخزين المؤقت',
            '../storage/uploads' => 'مجلد الملفات'
        ];
        foreach ($directories as $dir => $name) {
            echo "<li>$name: " . formatResult(checkDirectory($dir)) . "</li>";
        }
        ?>
    </ul>
</body>
</html> 