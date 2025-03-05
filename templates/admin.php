<?php
// التحقق من الخطوة السابقة
if (!Installer::checkStep('database')) {
    header('Location: ?step=database');
    exit;
}
?>

<div class="admin-setup">
    <h2><?php echo $lang['admin_settings']; ?></h2>
    
    <form method="POST" action="handlers/admin.php" class="setup-form">
        <div class="form-group">
            <label for="admin_name"><?php echo $lang['admin_name']; ?></label>
            <input type="text" name="admin_name" id="admin_name" required>
        </div>

        <div class="form-group">
            <label for="admin_email"><?php echo $lang['admin_email']; ?></label>
            <input type="email" name="admin_email" id="admin_email" required>
        </div>

        <div class="form-group">
            <label for="admin_password"><?php echo $lang['admin_password']; ?></label>
            <input type="password" name="admin_password" id="admin_password" required>
        </div>

        <div class="form-group">
            <label for="confirm_password"><?php echo $lang['confirm_password']; ?></label>
            <input type="password" name="confirm_password" id="confirm_password" required>
        </div>

        <?php include 'includes/csrf.php'; ?>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?php echo $lang['continue']; ?></button>
        </div>
    </form>
</div> 