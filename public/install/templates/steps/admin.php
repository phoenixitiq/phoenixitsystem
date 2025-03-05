<div class="step-content">
    <h2>إعداد حساب المدير</h2>

    <form action="handlers/admin.php" method="POST" class="admin-form">
        <div class="form-group">
            <label for="admin_name">الاسم الكامل:</label>
            <input type="text" id="admin_name" name="admin_name" required>
        </div>

        <div class="form-group">
            <label for="admin_username">اسم المستخدم:</label>
            <input type="text" id="admin_username" name="admin_username" required>
            <small class="hint">يجب أن يحتوي على حروف وأرقام فقط، 3-20 حرفاً</small>
        </div>

        <div class="form-group">
            <label for="admin_email">البريد الإلكتروني:</label>
            <input type="email" id="admin_email" name="admin_email" required>
        </div>

        <div class="form-group">
            <label for="admin_password">كلمة المرور:</label>
            <div class="password-field">
                <input type="password" id="admin_password" name="admin_password" required>
                <button type="button" class="password-toggle">عرض</button>
            </div>
            <small class="hint">8 أحرف على الأقل، حرف كبير، حرف صغير، رقم</small>
        </div>

        <div class="form-group">
            <label for="admin_password_confirm">تأكيد كلمة المرور:</label>
            <div class="password-field">
                <input type="password" id="admin_password_confirm" name="admin_password_confirm" required>
                <button type="button" class="password-toggle">عرض</button>
            </div>
        </div>

        <div class="form-actions">
            <button type="button" class="nav-button" data-step="database">السابق</button>
            <button type="submit" class="submit-button">إكمال التثبيت</button>
        </div>
    </form>
</div>

<script>
// التحقق من قوة كلمة المرور
document.getElementById('admin_password').addEventListener('input', function() {
    const password = this.value;
    const strength = checkPasswordStrength(password);
    const strengthDiv = document.querySelector('.password-strength');
    
    const strengthClasses = {
        weak: 'text-error',
        medium: 'text-warning',
        strong: 'text-success'
    };

    strengthDiv.className = 'password-strength ' + strengthClasses[strength.level];
    strengthDiv.textContent = strength.message;
});

function checkPasswordStrength(password) {
    if (password.length < 8) {
        return { level: 'weak', message: 'كلمة المرور ضعيفة - يجب أن تكون 8 أحرف على الأقل' };
    }

    let strength = 0;
    if (password.match(/[a-z]/)) strength++;
    if (password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++;

    if (strength >= 4) {
        return { level: 'strong', message: 'كلمة المرور قوية' };
    } else if (strength >= 3) {
        return { level: 'medium', message: 'كلمة المرور متوسطة' };
    } else {
        return { level: 'weak', message: 'كلمة المرور ضعيفة' };
    }
}
</script>
