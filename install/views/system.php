<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعداد النظام - Phoenix IT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #04a887;
        }
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .system-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .section-title {
            color: var(--primary-color);
            border-bottom: 2px solid #eee;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
        }
        .form-container {
            max-width: 600px;
            margin: 0 auto;
        }
        .input-group-text {
            background: var(--primary-color);
            color: white;
            border: none;
            width: 45px;
            justify-content: center;
        }
        .form-control {
            border: 1px solid #dee2e6;
        }
        .form-control:focus {
            border-color: var(--primary-color);
        }
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        .system-form {
            animation: slideUp 0.5s ease-out;
        }
        .form-section {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }
        .form-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="system-container">
        <h2 class="text-center mb-4">إعداد النظام</h2>

        <div class="system-content">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="form-container">
                <form method="POST" action="?step=3" class="system-form">
                    <div class="form-section mb-5">
                        <h4 class="section-title">
                            <i class="fas fa-globe me-2"></i>
                            معلومات الموقع
                        </h4>
                        
                        <div class="mb-4">
                            <label for="site_name" class="form-label">اسم الموقع</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-building"></i>
                                </span>
                                <input type="text" class="form-control" id="site_name" name="site_name" 
                                       value="Phoenix IT" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="site_url" class="form-label">رابط الموقع</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-link"></i>
                                </span>
                                <input type="url" class="form-control" id="site_url" name="site_url" 
                                       value="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="site_logo" class="form-label">شعار الموقع</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-image"></i>
                                </span>
                                <input type="file" class="form-control" id="site_logo" name="site_logo" accept="image/svg+xml,image/png">
                            </div>
                            <div class="form-text">المسار الحالي: public/images/logo.svg</div>
                        </div>

                        <div class="mb-4">
                            <label for="site_favicon" class="form-label">أيقونة الموقع</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-image"></i>
                                </span>
                                <input type="file" class="form-control" id="site_favicon" name="site_favicon" accept="image/x-icon">
                            </div>
                            <div class="form-text">المسار الحالي: public/images/favicon.ico</div>
                        </div>
                    </div>

                    <div class="form-section mb-5">
                        <h4 class="section-title">
                            <i class="fas fa-user-shield me-2"></i>
                            معلومات المدير
                        </h4>
                        
                        <div class="mb-4">
                            <label for="admin_name" class="form-label">الاسم</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" class="form-control" id="admin_name" name="admin_name" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="admin_email" class="form-label">البريد الإلكتروني</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" class="form-control" id="admin_email" name="admin_email" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="admin_password" class="form-label">كلمة المرور</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control" id="admin_password" name="admin_password" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('admin_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="admin_password_confirm" class="form-label">تأكيد كلمة المرور</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control" id="admin_password_confirm" 
                                       name="admin_password_confirm" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('admin_password_confirm')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg">
                            إكمال التثبيت
                            <i class="fas fa-arrow-left me-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const type = input.type === 'password' ? 'text' : 'password';
        input.type = type;
        
        const icon = event.currentTarget.querySelector('i');
        icon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
    }
    </script>
</body>
</html> 