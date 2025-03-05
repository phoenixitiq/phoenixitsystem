<?php
if (!defined('ROOT_PATH')) {
    exit('No direct script access allowed');
}

// التحقق من إكمال الخطوات السابقة
if (!isset($_SESSION['db_config']) || !isset($_SESSION['settings'])) {
    header('Location: index.php?step=welcome');
    exit;
}

$error = '';
$success = '';

try {
    // إنشاء قاعدة البيانات وتثبيت النظام
    if (!isset($_SESSION['installation_completed'])) {
        // إنشاء جداول قاعدة البيانات
        createDatabaseTables($_SESSION['db_config']);
        
        // إنشاء ملف .env
        createEnvFile(array_merge($_SESSION['db_config'], $_SESSION['settings']));
        
        // إنشاء حساب المدير
        createAdminAccount($_SESSION['settings']);
        
        // إنشاء ملف التثبيت
        createInstallationLockFile();
        
        $_SESSION['installation_completed'] = true;
        $success = 'تم تثبيت النظام بنجاح!';
        
        // تسجيل نجاح التثبيت
        writeLog('Installation completed successfully', 'success');
    }
} catch (Exception $e) {
    $error = $e->getMessage();
    writeLog("Installation error: " . $error, 'error');
}
?>

<div class="finish-installation">
    <h2>اكتمال التثبيت</h2>
    
    <?php if ($error): ?>
        <div class="alert alert-danger">
            <h3>حدث خطأ أثناء التثبيت:</h3>
            <p><?php echo htmlspecialchars($error); ?></p>
            <a href="index.php?step=database" class="btn-secondary">العودة وإعادة المحاولة</a>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <h3>تهانينا!</h3>
            <p><?php echo htmlspecialchars($success); ?></p>
            
            <div class="installation-details">
                <h4>تفاصيل التثبيت:</h4>
                <ul>
                    <li>اسم الموقع: <?php echo htmlspecialchars($_SESSION['settings']['site_name']); ?></li>
                    <li>رابط الموقع: <?php echo htmlspecialchars($_SESSION['settings']['site_url']); ?></li>
                    <li>البريد الإلكتروني للمدير: <?php echo htmlspecialchars($_SESSION['settings']['admin_email']); ?></li>
                </ul>
            </div>

            <div class="security-notice">
                <h4>ملاحظات أمنية هامة:</h4>
                <ul>
                    <li>يرجى حذف مجلد التثبيت "install" من الخادم.</li>
                    <li>تأكد من تعيين الصلاحيات المناسبة للملفات والمجلدات.</li>
                    <li>قم بتغيير كلمة المرور بعد أول تسجيل دخول.</li>
                </ul>
            </div>

            <div class="next-steps">
                <h4>الخطوات التالية:</h4>
                <a href="../admin" class="btn-primary">الذهاب إلى لوحة التحكم</a>
                <a href="../" class="btn-secondary">الذهاب إلى الصفحة الرئيسية</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
// تنظيف جلسة التثبيت
if ($success) {
    unset($_SESSION['db_config']);
    unset($_SESSION['settings']);
}
?> 