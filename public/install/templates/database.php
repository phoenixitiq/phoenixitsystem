<?php include 'layout/header.php'; ?>

<div class="step-content database">
    <h2>إعدادات قاعدة البيانات</h2>
    <form id="database-form">
        <input type="hidden" name="action" value="save_database">
        
        <div class="form-group">
            <label for="host">اسم المضيف</label>
            <input type="text" id="host" name="database[host]" value="localhost" required>
        </div>

        <div class="form-group">
            <label for="port">المنفذ</label>
            <input type="number" id="port" name="database[port]" value="3306" required>
        </div>

        <div class="form-group">
            <label for="database">اسم قاعدة البيانات</label>
            <input type="text" id="database" name="database[database]" required>
        </div>

        <div class="form-group">
            <label for="username">اسم المستخدم</label>
            <input type="text" id="username" name="database[username]" required>
        </div>

        <div class="form-group">
            <label for="password">كلمة المرور</label>
            <input type="password" id="password" name="database[password]">
        </div>
    </form>
</div>

<?php include 'layout/footer.php'; ?>
