<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تثبيت النظام - Phoenix IT System</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="../assets/css/install.css">
</head>
<body>
    <div class="container">
        <div class="installation-wrapper">
            <div class="installation-header">
                <img src="../assets/images/logo.png" alt="Phoenix IT System">
                <h1>معالج تثبيت النظام</h1>
            </div>
            <?php if ($current_step !== 'welcome'): ?>
                <?php include 'steps.php'; ?>
            <?php endif; ?>
            <main class="content">
        </div>
    </div>
</body>
</html>
