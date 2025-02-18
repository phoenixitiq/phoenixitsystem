<div class="admin-setup">
    <h2>إعداد حساب المدير</h2>
    
    <form method="post" action="?step=3">
        <div class="form-group">
            <label for="admin_name">الاسم الكامل</label>
            <input type="text" id="admin_name" name="admin_name" required>
        </div>

        <div class="form-group">
            <label for="admin_email">البريد الإلكتروني</label>
            <input type="email" id="admin_email" name="admin_email" required>
        </div>

        <div class="form-group">
            <label for="admin_password">كلمة المرور</label>
            <input type="password" id="admin_password" name="admin_password" required>
        </div>

        <div class="form-group">
            <label for="admin_password_confirm">تأكيد كلمة المرور</label>
            <input type="password" id="admin_password_confirm" name="admin_password_confirm" required>
        </div>

        <div class="buttons">
            <button type="submit" class="btn btn-primary">متابعة</button>
        </div>
    </form>
</div> 