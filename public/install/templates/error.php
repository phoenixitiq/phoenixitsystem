<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خطأ - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/install.css">
    <style>
        .error-container {
            text-align: center;
            padding: 2rem;
            margin: 2rem auto;
            max-width: 600px;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .error-icon {
            font-size: 4rem;
            color: var(--error-color);
            margin-bottom: 1rem;
        }

        .error-message {
            color: var(--text-color);
            margin-bottom: 2rem;
        }

        .error-details {
            background: var(--background-color);
            padding: 1rem;
            border-radius: 0.25rem;
            margin-bottom: 2rem;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">⚠️</div>
        <h1>عذراً! حدث خطأ</h1>
        <div class="error-message">
            <?php echo Security::sanitizeOutput($e->getMessage()); ?>
        </div>
        <div class="error-details">
            <p>يرجى المحاولة مرة أخرى أو الاتصال بالدعم الفني إذا استمرت المشكلة.</p>
        </div>
        <a href="index.php" class="btn btn-primary">العودة للصفحة الرئيسية</a>
    </div>
</body>
</html> 