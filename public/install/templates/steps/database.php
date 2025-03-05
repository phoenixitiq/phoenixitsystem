<div class="step-content">
    <h2>إعداد قاعدة البيانات</h2>

    <form action="handlers/database.php" method="POST" class="database-form">
        <div class="form-group">
            <label for="host">خادم قاعدة البيانات:</label>
            <input type="text" id="host" name="host" value="localhost" required>
        </div>

        <div class="form-group">
            <label for="port">المنفذ:</label>
            <input type="number" id="port" name="port" value="3306" required>
        </div>

        <div class="form-group">
            <label for="username">اسم المستخدم:</label>
            <input type="text" id="username" name="username" required>
        </div>

        <div class="form-group">
            <label for="password">كلمة المرور:</label>
            <div class="password-field">
                <input type="password" id="password" name="password" required>
                <button type="button" class="password-toggle">عرض</button>
            </div>
        </div>

        <div class="form-group">
            <label for="db_name">اسم قاعدة البيانات:</label>
            <input type="text" id="db_name" name="db_name" required>
        </div>

        <div class="form-group">
            <label for="prefix">بادئة الجداول:</label>
            <input type="text" id="prefix" name="prefix" value="phoenix_" required>
        </div>

        <div class="form-actions">
            <button type="button" class="nav-button" data-step="requirements">السابق</button>
            <button type="submit" class="submit-button">اختبار الاتصال</button>
        </div>
    </form>

    <div id="connectionStatus" class="connection-status"></div>
</div>

<script>
document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function() {
        const input = this.previousElementSibling;
        const type = input.type === 'password' ? 'text' : 'password';
        input.type = type;
        this.querySelector('.show-password').textContent = type === 'password' ? '👁' : '👁‍🗨';
    });
});
</script>
