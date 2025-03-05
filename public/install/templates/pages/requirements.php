<?php
// إضافة في بداية الملف
$setupResults = setupDirectories();
?>

<div class="requirements-container">
    <div class="page-header">
        <h1>متطلبات النظام</h1>
        <p>تحقق من توافق النظام مع المتطلبات التالية</p>
    </div>

    <div class="requirements-grid">
        <!-- متطلبات PHP -->
        <div class="requirement-section">
            <h2>متطلبات PHP</h2>
            <div class="requirement-item <?php echo phpversion() >= '7.4' ? 'success' : 'error'; ?>">
                <i class="fas <?php echo phpversion() >= '7.4' ? 'fa-check' : 'fa-times'; ?>"></i>
                <div class="requirement-info">
                    <h3>PHP 7.4+</h3>
                    <p>النسخة الحالية: <?php echo phpversion(); ?></p>
                </div>
            </div>
            
            <?php
            $extensions = ['pdo', 'mysqli', 'curl', 'gd', 'zip'];
            foreach ($extensions as $ext) {
                $loaded = extension_loaded($ext);
            ?>
            <div class="requirement-item <?php echo $loaded ? 'success' : 'error'; ?>">
                <i class="fas <?php echo $loaded ? 'fa-check' : 'fa-times'; ?>"></i>
                <div class="requirement-info">
                    <h3><?php echo strtoupper($ext); ?></h3>
                    <p><?php echo $loaded ? 'متوفر' : 'غير متوفر'; ?></p>
                </div>
            </div>
            <?php } ?>
        </div>

        <!-- متطلبات النظام -->
        <div class="requirement-section">
            <h2>متطلبات النظام</h2>
            <?php
            $directories = [
                'storage' => ROOT_PATH . '/storage',
                'cache' => ROOT_PATH . '/storage/cache',
                'logs' => ROOT_PATH . '/storage/logs'
            ];

            foreach ($directories as $name => $path) {
                $check = checkDirectoryPermissions($path);
                $status = $check['writable'];
                $permissions = $check['permissions'];
            ?>
            <div class="requirement-item <?php echo $status ? 'success' : 'error'; ?>">
                <i class="fas <?php echo $status ? 'fa-check' : 'fa-times'; ?>"></i>
                <div class="requirement-info">
                    <h3><?php echo ucfirst($name); ?></h3>
                    <p>
                        <?php 
                        if ($status) {
                            echo "قابل للكتابة (صلاحيات: {$permissions})";
                        } else {
                            echo "غير قابل للكتابة (صلاحيات: {$permissions})";
                            if (isset($setupResults[$path]) && !$setupResults[$path]['status']) {
                                echo " - " . $setupResults[$path]['error'];
                            }
                        }
                        ?>
                    </p>
                </div>
                <?php if (!$status): ?>
                <button type="button" class="fix-permissions" data-directory="<?php echo $name; ?>">
                    <i class="fas fa-wrench"></i>
                    إصلاح
                </button>
                <?php endif; ?>
            </div>
            <?php } ?>
        </div>
    </div>

    <div class="actions">
        <button onclick="window.location.href='?page=welcome'" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i> السابق
        </button>
        <button onclick="window.location.href='?page=database'" class="btn btn-primary" <?php echo allRequirementsMet() ? '' : 'disabled'; ?>>
            التالي <i class="fas fa-arrow-left"></i>
        </button>
    </div>
</div> 