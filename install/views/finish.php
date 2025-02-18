<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اكتمال التثبيت - Phoenix IT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #04a887;
            --success-color: #198754;
        }
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .finish-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .success-icon {
            color: var(--success-color);
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        .info-box {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin: 1.5rem 0;
        }
    </style>
</head>
<body>
    <div class="finish-content text-center">
        <div class="success-animation mb-4">
            <i class="fas fa-check-circle success-icon"></i>
        </div>
        
        <h2 class="mb-3">تم تثبيت النظام بنجاح!</h2>
        <p class="text-muted mb-5">يمكنك الآن تسجيل الدخول إلى لوحة التحكم</p>

        <div class="info-section mb-5">
            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h5 class="mb-4">معلومات تسجيل الدخول</h5>
                <div class="info-details">
                    <p class="mb-2">البريد الإلكتروني: <strong><?php echo htmlspecialchars($admin_email); ?></strong></p>
                    <p class="mb-0">كلمة المرور: <em>التي قمت بإدخالها أثناء التثبيت</em></p>
                </div>
            </div>
        </div>

        <div class="notes-section mb-5">
            <h5 class="notes-title mb-4">
                <i class="fas fa-exclamation-circle me-2"></i>
                ملاحظات هامة
            </h5>
            <div class="notes-grid">
                <div class="note-card">
                    <div class="note-icon">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                    <p>يرجى حذف مجلد التثبيت من الخادم</p>
                </div>
                <div class="note-card">
                    <div class="note-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <p>تأكد من تعيين الصلاحيات المناسبة للملفات</p>
                </div>
                <div class="note-card">
                    <div class="note-icon">
                        <i class="fas fa-sync"></i>
                    </div>
                    <p>قم بعمل نسخة احتياطية بشكل دوري</p>
                </div>
            </div>
        </div>

        <a href="<?php echo rtrim($data['site_url'] ?? '', '/') . '/admin'; ?>" class="btn btn-primary btn-lg">
            الذهاب إلى لوحة التحكم
            <i class="fas fa-arrow-left me-2"></i>
        </a>
    </div>

    <style>
    .success-animation {
        animation: scaleIn 0.5s ease-out;
    }

    .success-icon {
        font-size: 5rem;
        color: var(--success-color);
        animation: pulse 2s infinite;
    }

    .info-section {
        max-width: 500px;
        margin: 0 auto;
    }

    .info-card {
        background: #f8f9fa;
        padding: 2rem;
        border-radius: 15px;
        transition: all 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .info-icon {
        font-size: 2.5rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }

    .info-details {
        background: white;
        padding: 1rem;
        border-radius: 10px;
    }

    .notes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .note-card {
        background: #fff3cd;
        padding: 1.5rem;
        border-radius: 10px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .note-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .note-icon {
        font-size: 1.5rem;
        color: #856404;
        margin-bottom: 1rem;
    }

    .notes-title {
        color: #856404;
    }

    @keyframes scaleIn {
        from { transform: scale(0); }
        to { transform: scale(1); }
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
    </style>
</body>
</html> 