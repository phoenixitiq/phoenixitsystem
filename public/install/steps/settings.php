<?php
if (!defined('ROOT_PATH')) {
    exit('No direct script access allowed');
}

// التحقق من إكمال الخطوة السابقة
if (!isset($_SESSION['db_config'])) {
    header('Location: index.php?step=database');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // التحقق من CSRF token
        if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
            throw new Exception('رمز CSRF غير صالح');
        }

        // التحقق من البيانات
        $settings = [
            'site_name' => trim($_POST['site_name'] ?? ''),
            'site_url' => trim($_POST['site_url'] ?? ''),
            'admin_email' => trim($_POST['admin_email'] ?? ''),
            'admin_username' => trim($_POST['admin_username'] ?? ''),
            'admin_password' => $_POST['admin_password'] ?? '',
            'timezone' => trim($_POST['timezone'] ?? 'UTC')
        ];

        // التحقق من صحة البيانات
        if (empty($settings['site_name'])) {
            throw new Exception('اسم الموقع مطلوب');
        }

        if (!filter_var($settings['site_url'], FILTER_VALIDATE_URL)) {
            throw new Exception('رابط الموقع غير صالح');
        }

        if (!filter_var($settings['admin_email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('البريد الإلكتروني غير صالح');
        }

        if (strlen($settings['admin_password']) < 8) {
            throw new Exception('كلمة المرور يجب أن تكون 8 أحرف على الأقل');
        }

        // حفظ الإعدادات
        $_SESSION['settings'] = $settings;

        // الانتقال إلى الخطوة التالية
        header('Location: index.php?step=finish');
        exit;

    } catch (Exception $e) {
        $error = $e->getMessage();
        writeLog("Settings setup error: " . $error, 'error');
    }
}

// إنشاء CSRF token جديد
$csrf_token = generateCSRFToken();

// الحصول على قائمة المناطق الزمنية
$timezones = DateTimeZone::listIdentifiers();
?>

<div class="settings-setup">
    <h2>إعدادات النظام</h2>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post" action="" class="settings-form">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        
        <div class="form-group">
            <label for="site_name">اسم الموقع:</label>
            <input type="text" id="site_name" name="site_name" required>
        </div>

        <div class="form-group">
            <label for="site_url">رابط الموقع:</label>
            <input type="url" id="site_url" name="site_url" value="<?php echo 'https://' . $_SERVER['HTTP_HOST']; ?>" required>
        </div>

        <div class="form-group">
            <label for="admin_email">البريد الإلكتروني للمدير:</label>
            <input type="email" id="admin_email" name="admin_email" required>
        </div>

        <div class="form-group">
            <label for="admin_username">اسم المستخدم للمدير:</label>
            <input type="text" id="admin_username" name="admin_username" required>
        </div>

        <div class="form-group">
            <label for="admin_password">كلمة المرور للمدير:</label>
            <input type="password" id="admin_password" name="admin_password" required>
            <small>يجب أن تكون 8 أحرف على الأقل</small>
        </div>

        <div class="form-group">
            <label for="timezone">المنطقة الزمنية:</label>
            <select id="timezone" name="timezone" required>
                <?php foreach ($timezones as $tz): ?>
                    <option value="<?php echo htmlspecialchars($tz); ?>"><?php echo htmlspecialchars($tz); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-actions">
            <button type="button" onclick="window.location='index.php?step=database'" class="btn-secondary">السابق</button>
            <button type="submit" class="btn-primary">التالي</button>
        </div>
    </form>
</div> 