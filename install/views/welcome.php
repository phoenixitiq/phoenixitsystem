<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تثبيت نظام Phoenix IT</title>
    <!-- تحميل ملفات CSS من CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #04a887;
        }
        body {
            background-color: #f8f9fa;
            font-family: system-ui, -apple-system, sans-serif;
        }
        .welcome-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
        }
        .welcome-content {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 2rem;
        }
        .logo {
            height: 100px;
            width: auto;
            margin-bottom: 1rem;
        }
        .version-badge {
            background: var(--primary-color);
            color: white;
            padding: 0.25rem 1rem;
            border-radius: 20px;
            display: inline-block;
            font-size: 0.9rem;
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 2rem 0;
        }
        .feature-card {
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 8px;
            text-align: center;
        }
        .feature-icon {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-primary:hover {
            filter: brightness(90%);
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <div class="welcome-content text-center">
            <div class="logo-section">
                <?php
                // تحديد المسار النسبي للصور
                $basePath = dirname(dirname(__DIR__));
                $webRoot = '/public';
                $imagesPath = '/assets/images';
                
                // تحديد المسارات الكاملة للملفات
                $logoPath = $basePath . $webRoot . $imagesPath . '/logo.svg';
                $defaultLogoPath = $basePath . $webRoot . $imagesPath . '/default-logo.png';
                
                // تحديد المسارات النسبية للعرض
                $webLogoPath = '../../public/assets/images/logo.svg';
                $webDefaultLogoPath = '../../public/assets/images/default-logo.png';
                
                // التحقق من وجود الملفات وإمكانية القراءة
                if (is_readable($logoPath)) {
                    $displayPath = $webLogoPath;
                } else {
                    $displayPath = $webDefaultLogoPath;
                }
                ?>
                <img src="<?php echo htmlspecialchars($displayPath, ENT_QUOTES, 'UTF-8'); ?>" 
                     alt="Phoenix IT" 
                     class="logo">
                <div class="version-badge">الإصدار 1.0.0</div>
            </div>
            
            <h1 class="mt-4 mb-3">مرحباً بك في نظام Phoenix IT</h1>
            <p class="text-muted mb-4">سنساعدك في تثبيت النظام خطوة بخطوة</p>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <h5>سهل الاستخدام</h5>
                    <p>واجهة بسيطة وسهلة الاستخدام</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt fa-2x"></i>
                    </div>
                    <h5>آمن</h5>
                    <p>حماية متقدمة لبياناتك</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-sync fa-2x"></i>
                    </div>
                    <h5>تحديثات مستمرة</h5>
                    <p>تحديثات دورية للنظام</p>
                </div>
            </div>

            <a href="?step=requirements" class="btn btn-primary btn-lg">
                ابدأ التثبيت
                <i class="fas fa-arrow-left me-2"></i>
            </a>
        </div>
    </div>
</body>
</html>
