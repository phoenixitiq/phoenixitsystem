<div class="setup-container">
    <div class="page-header">
        <h1>إعداد قاعدة البيانات</h1>
        <p>أدخل معلومات الاتصال بقاعدة البيانات</p>
    </div>

    <div class="setup-form">
        <form id="databaseForm" method="POST">
            <div class="form-group">
                <label for="db_host">خادم قاعدة البيانات</label>
                <div class="input-group">
                    <i class="fas fa-server"></i>
                    <input type="text" id="db_host" name="db_host" value="localhost" required>
                </div>
            </div>

            <div class="form-group">
                <label for="db_name">اسم قاعدة البيانات</label>
                <div class="input-group">
                    <i class="fas fa-database"></i>
                    <input type="text" id="db_name" name="db_name" required>
                </div>
            </div>

            <div class="form-group">
                <label for="db_user">اسم المستخدم</label>
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" id="db_user" name="db_user" required>
                </div>
            </div>

            <div class="form-group">
                <label for="db_pass">كلمة المرور</label>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="db_pass" name="db_pass" required>
                    <button type="button" class="toggle-password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" onclick="window.location.href='?page=requirements'" class="btn btn-secondary">
                    <i class="fas fa-arrow-right"></i> السابق
                </button>
                <button type="submit" class="btn btn-primary">
                    التالي <i class="fas fa-arrow-left"></i>
                </button>
            </div>
        </form>
    </div>
</div> 