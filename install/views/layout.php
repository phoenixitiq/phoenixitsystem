<?php
// Define default values for variables
$currentStep = $currentStep ?? 'welcome';
$content = $content ?? '';

// تحديد المسار الأساسي للموقع
$baseUrl = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/install');

// مسارات الشعار
$publicLogoPath = $_SERVER['DOCUMENT_ROOT'] . $baseUrl . '/public/images/logo.svg';
$installLogoPath = __DIR__ . '/../assets/images/logo.svg';

// التأكد من وجود مجلد الصور
if (!file_exists(dirname($publicLogoPath))) {
    @mkdir(dirname($publicLogoPath), 0755, true);
}

// نسخ الشعار إذا لم يكن موجوداً
if (!file_exists($publicLogoPath) && file_exists($installLogoPath)) {
    @copy($installLogoPath, $publicLogoPath);
}

// تحديد مسار الشعار للعرض
$logoPath = file_exists($publicLogoPath) ? '/public/images/logo.svg' : '/install/assets/images/logo.svg';
$logoUrl = $baseUrl . $logoPath;
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تثبيت النظام - Phoenix IT</title>
    <link rel="icon" type="image/x-icon" href="<?php echo $baseUrl; ?>/public/images/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/public/install/css/install.css">
    <style>
        :root {
            --primary-color: #04a887;
            --primary-hover: #038d73;
            --secondary-color: #2d3436;
            --error-color: #dc3545;
            --success-color: #198754;
        }
        
        body {
            background: linear-gradient(135deg, #f6f8fa 0%, #e9ecef 100%);
            font-family: 'Tajawal', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 2rem 0;
        }

        .main-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .logo-container {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-container img {
            height: 80px;
            margin-bottom: 1rem;
        }

        .version-badge {
            background: var(--primary-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            display: inline-block;
            margin-top: 0.5rem;
        }

        .steps-progress {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3rem;
            position: relative;
            padding: 0 2rem;
        }

        .steps-progress::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: #e9ecef;
            transform: translateY(-50%);
            z-index: 1;
        }

        .step-item {
            position: relative;
            z-index: 2;
            background: white;
            padding: 0 1rem;
            text-align: center;
        }

        .step-number {
            width: 40px;
            height: 40px;
            line-height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            color: var(--secondary-color);
            margin: 0 auto 0.5rem;
            transition: all 0.3s ease;
        }

        .step-item.active .step-number {
            background: var(--primary-color);
            color: white;
            transform: scale(1.1);
        }

        .step-item.completed .step-number {
            background: var(--success-color);
            color: white;
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.75rem 2rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-2px);
        }

        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 10px;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(4, 168, 135, 0.25);
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .alert-success {
            background-color: #d1e7dd;
            color: var(--success-color);
        }

        .alert-danger {
            background-color: #f8d7da;
            color: var(--error-color);
        }

        /* تأثيرات حركية */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        <?php if (isset($extraStyles)) echo $extraStyles; ?>

        /* تحديث أنماط خطوات التثبيت */
        .install-steps {
            margin-bottom: 2rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .step-item {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
        }
        
        .step-number {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .step-title {
            font-size: 0.9rem;
            color: #6c757d;
            text-align: center;
        }
        
        .step-item.active .step-number {
            background: var(--primary-color);
            color: white;
        }
        
        .step-item.active .step-title {
            color: var(--primary-color);
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="main-container">
            <div class="logo-container">
                <img src="<?php echo $logoUrl; ?>" alt="Phoenix IT Logo" class="logo">
                <span class="version-badge">الإصدار 1.0.0</span>
            </div>

            <div class="install-steps">
                <div class="d-flex justify-content-between">
                    <div class="step-item <?php echo $currentStep == 'welcome' ? 'active' : ''; ?>">
                        <div class="step-number">1</div>
                        <span class="step-title">الترحيب</span>
                    </div>
                    <div class="step-item <?php echo $currentStep == 'requirements' ? 'active' : ''; ?>">
                        <div class="step-number">2</div>
                        <span class="step-title">المتطلبات</span>
                    </div>
                    <div class="step-item <?php echo $currentStep == 'database' ? 'active' : ''; ?>">
                        <div class="step-number">3</div>
                        <span class="step-title">قاعدة البيانات</span>
                    </div>
                    <div class="step-item <?php echo $currentStep == 'admin' ? 'active' : ''; ?>">
                        <div class="step-number">4</div>
                        <span class="step-title">حساب المدير</span>
                    </div>
                    <div class="step-item <?php echo $currentStep == 'finish' ? 'active' : ''; ?>">
                        <div class="step-number">5</div>
                        <span class="step-title">اكتمال التثبيت</span>
                    </div>
                </div>
            </div>

            <div class="content">
                <?php echo $content; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php if (isset($extraScripts)) echo $extraScripts; ?>
</body>
</html> 