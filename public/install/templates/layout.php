<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تثبيت النظام</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="installation-wrapper">
            <!-- شريط التقدم -->
            <div class="progress-bar">
                <div class="step <?php echo $currentStep === 'welcome' ? 'active' : ''; ?>">الترحيب</div>
                <div class="step <?php echo $currentStep === 'requirements' ? 'active' : ''; ?>">المتطلبات</div>
                <div class="step <?php echo $currentStep === 'database' ? 'active' : ''; ?>">قاعدة البيانات</div>
                <div class="step <?php echo $currentStep === 'admin' ? 'active' : ''; ?>">المدير</div>
                <div class="step <?php echo $currentStep === 'complete' ? 'active' : ''; ?>">اكتمال</div>
            </div>

            <!-- المحتوى الرئيسي -->
            <div id="main-content">
                <?php include "templates/{$currentStep}.php"; ?>
            </div>

            <!-- أزرار التنقل -->
            <div class="navigation-buttons">
                <button class="prev-button">السابق</button>
                <button class="next-button">التالي</button>
                <button class="install-button" style="display: none;">تثبيت</button>
                <button class="finish-button" style="display: none;">إنهاء</button>
            </div>
        </div>
    </div>

    <script src="assets/js/install.js"></script>
</body>
</html> 