<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تثبيت النظام - <?= $config['name'] ?></title>
    <link rel="stylesheet" href="assets/css/install.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="assets/images/favicon.svg">
</head>
<body>
    <div class="install-container">
        <div class="logo">
            <img src="assets/images/logo.svg" alt="Phoenix IT" width="200">
        </div>

        <div class="steps">
            <div class="step <?= $step == 1 ? 'active' : '' ?>">
                <span class="number">1</span>
                <span class="text">المتطلبات</span>
            </div>
            <div class="step <?= $step == 2 ? 'active' : '' ?>">
                <span class="number">2</span>
                <span class="text">قاعدة البيانات</span>
            </div>
            <div class="step <?= $step == 3 ? 'active' : '' ?>">
                <span class="number">3</span>
                <span class="text">حساب المدير</span>
            </div>
            <div class="step <?= $step == 4 ? 'active' : '' ?>">
                <span class="number">4</span>
                <span class="text">إكمال</span>
            </div>
        </div>

        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <div class="content">
            <?php include "steps/{$currentStep}.php"; ?>
        </div>
    </div>
    <script src="assets/js/install.js"></script>
</body>
</html> 