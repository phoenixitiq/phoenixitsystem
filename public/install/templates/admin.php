<?php include 'layout/header.php'; ?>

<div class="step-content admin">
    <h2>إعداد حساب المدير</h2>
    <form id="admin-form">
        <input type="hidden" name="action" value="save_admin">
        
        <div class="form-group">
            <label for="admin_name">الاسم</label>
            <input type="text" id="admin_name" name="admin[name]" required>
        </div>

        <div class="form-group">
            <label for="admin_email">البريد الإلكتروني</label>
            <input type="email" id="admin_email" name="admin[email]" required>
        </div>

        <div class="form-group">
            <label for="admin_password">كلمة المرور</label>
            <input type="password" id="admin_password" name="admin[password]" required>
        </div>

        <div class="form-group">
            <label for="admin_password_confirmation">تأكيد كلمة المرور</label>
            <input type="password" id="admin_password_confirmation" name="admin[password_confirmation]" required>
        </div>
    </form>
</div>

<?php include 'layout/footer.php'; ?>
