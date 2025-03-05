<div class="setup-container">
    <div class="page-header">
        <h1>إعداد حساب المدير</h1>
        <p>أدخل معلومات حساب المدير الرئيسي</p>
    </div>

    <div class="setup-form">
        <form id="adminForm" method="POST">
            <div class="form-group">
                <label for="admin_name">الاسم الكامل</label>
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" id="admin_name" name="admin_name" required>
                </div>
            </div>

            <div class="form-group">
                <label for="admin_email">البريد الإلكتروني</label>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="admin_email" name="admin_email" required>
                </div>
            </div>

            <div class="form-group">
                <label for="admin_password">كلمة المرور</label>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="admin_password" name="admin_password" required>
                    <button type="button" class="toggle-password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="password-strength"></div>
            </div>

            <div class="form-group">
                <label for="admin_password_confirm">تأكيد كلمة المرور</label>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="admin_password_confirm" name="admin_password_confirm" required>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" onclick="window.location.href='?page=database'" class="btn btn-secondary">
                    <i class="fas fa-arrow-right"></i> السابق
                </button>
                <button type="submit" class="btn btn-primary">
                    إنهاء التثبيت <i class="fas fa-check"></i>
                </button>
            </div>
        </form>
    </div>
</div> 