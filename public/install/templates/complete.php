<div class="step-content">
    <div class="complete-message">
        <h2>تم التثبيت بنجاح!</h2>
        <p>تم تثبيت نظام Phoenix IT بنجاح. يمكنك الآن تسجيل الدخول إلى لوحة التحكم.</p>
        
        <div class="admin-info">
            <h3>معلومات الدخول:</h3>
            <p>اسم المستخدم: <?php echo $_SESSION['admin_username'] ?? ''; ?></p>
            <p>رابط لوحة التحكم: <a href="../admin/login.php">اضغط هنا</a></p>
        </div>

        <div class="security-notice">
            <h3>ملاحظات أمنية هامة:</h3>
            <ul>
                <li>قم بحذف مجلد التثبيت (install) من الخادم</li>
                <li>تأكد من تعيين الصلاحيات المناسبة للملفات والمجلدات</li>
                <li>قم بتغيير كلمة المرور بعد أول تسجيل دخول</li>
            </ul>
        </div>

        <div class="next-steps">
            <a href="../admin/login.php" class="button">الذهاب إلى لوحة التحكم</a>
        </div>
    </div>
</div>
