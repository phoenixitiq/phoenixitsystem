<div class="database-setup">
    <h2>إعداد قاعدة البيانات</h2>
    
    <form method="post" action="?step=2">
        <input type="hidden" name="step" value="2">
        <div class="form-group">
            <label for="db_host">خادم قاعدة البيانات</label>
            <input type="text" id="db_host" name="db_host" value="localhost" required>
        </div>

        <div class="form-group">
            <label for="db_name">اسم قاعدة البيانات</label>
            <input type="text" id="db_name" name="db_name" required>
        </div>

        <div class="form-group">
            <label for="db_user">اسم المستخدم</label>
            <input type="text" id="db_user" name="db_user" required>
        </div>

        <div class="form-group">
            <label for="db_pass">كلمة المرور</label>
            <input type="password" id="db_pass" name="db_pass">
        </div>

        <div class="buttons">
            <button type="submit" class="btn btn-primary">متابعة</button>
        </div>
    </form>
</div> 