<?php
if (!defined('ROOT_PATH')) {
    exit('No direct script access allowed');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db_host = filter_input(INPUT_POST, 'db_host', FILTER_SANITIZE_STRING);
    $db_name = filter_input(INPUT_POST, 'db_name', FILTER_SANITIZE_STRING);
    $db_user = filter_input(INPUT_POST, 'db_user', FILTER_SANITIZE_STRING);
    $db_pass = filter_input(INPUT_POST, 'db_pass');
    
    try {
        $pdo = new PDO(
            "mysql:host=$db_host",
            $db_user,
            $db_pass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        
        // إنشاء قاعدة البيانات إذا لم تكن موجودة
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db_name` 
                    CHARACTER SET utf8mb4 
                    COLLATE utf8mb4_unicode_ci");
                    
        // تحديث ملف الإعدادات
        updateConfig([
            'DB_HOST' => $db_host,
            'DB_NAME' => $db_name,
            'DB_USER' => $db_user,
            'DB_PASS' => $db_pass
        ]);
        
        // استيراد هيكل قاعدة البيانات
        importDatabase($pdo, $db_name);
        
        redirect('?step=settings');
    } catch (PDOException $e) {
        $error = $e->getMessage();
    }
}

// إنشاء CSRF token جديد
$csrf_token = generateCSRFToken();
?>

<div class="database-setup">
    <h2>إعداد قاعدة البيانات</h2>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="post" action="" class="database-form">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        
        <div class="form-group">
            <label for="db_host">خادم قاعدة البيانات:</label>
            <input type="text" id="db_host" name="db_host" value="localhost" required>
            <small>عادةً ما يكون "localhost"</small>
        </div>

        <div class="form-group">
            <label for="db_name">اسم قاعدة البيانات:</label>
            <input type="text" id="db_name" name="db_name" required>
        </div>

        <div class="form-group">
            <label for="db_user">اسم المستخدم:</label>
            <input type="text" id="db_user" name="db_user" required>
        </div>

        <div class="form-group">
            <label for="db_pass">كلمة المرور:</label>
            <input type="password" id="db_pass" name="db_pass" required>
        </div>

        <div class="form-group">
            <label for="db_prefix">بادئة الجداول:</label>
            <input type="text" id="db_prefix" name="db_prefix" value="phoenix_" required>
            <small>مثال: phoenix_</small>
        </div>

        <div class="form-actions">
            <button type="button" onclick="window.location='index.php?step=welcome'" class="btn-secondary">السابق</button>
            <button type="submit" class="btn-primary">التالي</button>
        </div>
    </form>
</div> 