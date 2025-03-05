<?php
// التأكد من تعيين اللغة الافتراضية
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'ar';
}
$currentLang = $_SESSION['lang'];

// تحديد المسار الأساسي
$baseUrl = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<!DOCTYPE html>
<html dir="<?php echo $currentLang === 'en' ? 'ltr' : 'rtl'; ?>" lang="<?php echo $currentLang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?php echo $baseUrl; ?>/">
    <meta name="description" content="نظام Phoenix IT - صفحة التثبيت">
    <title><?php echo APP_NAME; ?> - <?php echo __('installation_wizard'); ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    
    <!-- Bootstrap -->
    <?php if ($currentLang === 'ar'): ?>
    <link rel="stylesheet" href="assets/css/bootstrap.rtl.min.css">
    <?php else: ?>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <?php endif; ?>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- الخطوط -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- ملفات CSS -->
    <link rel="stylesheet" href="assets/css/install.css">
    <script>
        window.APP = {
            csrfToken: '<?php echo generateCsrfToken(); ?>',
            baseUrl: '<?php echo $baseUrl; ?>',
            currentStep: '<?php echo $currentStep ?? ''; ?>'
        };
    </script>
</head>
<body class="installation-page <?php echo $currentStep; ?>-step lang-<?php echo $currentLang; ?>">
    <!-- خلفية متحركة -->
    <div class="animated-background"></div>

    <!-- شريط العلوي -->
    <header class="top-nav">
        <div class="nav-brand">
            <a href="<?php echo $baseUrl; ?>/?page=welcome" class="home-link">
                <img src="assets/images/logo.png" alt="Phoenix IT" class="nav-logo">
            </a>
        </div>
        <div class="nav-links">
            <a href="<?php echo $baseUrl; ?>/?page=welcome" class="nav-link">
                <i class="fas fa-home"></i>
                <span>الرئيسية</span>
            </a>
            <div class="lang-switcher">
                <button class="lang-btn" onclick="switchLanguage('<?php echo $currentLang === 'en' ? 'ar' : 'en'; ?>')">
                    <img src="assets/images/<?php echo $currentLang === 'en' ? 'ar' : 'en'; ?>.png" alt="Language">
                    <span><?php echo $currentLang === 'en' ? 'العربية' : 'English'; ?></span>
                </button>
            </div>
        </div>
    </header>

    <div class="main-container">
        <div class="installation-card">
            <!-- رأس البطاقة -->
            <div class="card-header text-center">
                <h1 class="installation-title">
                    <i class="fas fa-cog fa-spin me-2"></i>
                    تثبيت نظام Phoenix IT
                </h1>
                <div class="version-badge">
                    <i class="fas fa-code-branch me-1"></i>
                    الإصدار <?php echo APP_VERSION; ?>
                </div>
            </div>

            <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['flash_type'] ?? 'info'; ?>">
                <?php 
                echo $_SESSION['flash_message']; 
                unset($_SESSION['flash_message'], $_SESSION['flash_type']);
                ?>
            </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
            <?php endif; ?>

            <main> 