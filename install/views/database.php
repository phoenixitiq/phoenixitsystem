<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعداد قاعدة البيانات - Phoenix IT</title>
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
        .database-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .form-label {
            font-weight: 500;
        }
        .form-text {
            font-size: 0.875rem;
            color: #6c757d;
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
        .database-form {
            animation: slideUp 0.5s ease-out;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="database-container">
        <h2 class="text-center mb-4">إعداد قاعدة البيانات</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <form method="POST" action="?step=2" class="database-form">
                <div class="mb-4">
                    <label for="db_host" class="form-label">خادم قاعدة البيانات</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-server"></i>
                        </span>
                        <input type="text" class="form-control" id="db_host" name="db_host" 
                               value="localhost" required>
                    </div>
                    <div class="form-text">عادةً ما يكون "localhost"</div>
                </div>

                <div class="mb-4">
                    <label for="db_port" class="form-label">المنفذ</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-plug"></i>
                        </span>
                        <input type="text" class="form-control" id="db_port" name="db_port" 
                               value="3306" required>
                    </div>
                    <div class="form-text">المنفذ الافتراضي هو 3306</div>
                </div>

                <div class="mb-4">
                    <label for="db_name" class="form-label">اسم قاعدة البيانات</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-database"></i>
                        </span>
                        <input type="text" class="form-control" id="db_name" name="db_name" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="db_user" class="form-label">اسم المستخدم</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" class="form-control" id="db_user" name="db_user" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="db_pass" class="form-label">كلمة المرور</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" class="form-control" id="db_pass" name="db_pass">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('db_pass')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <button type="submit" class="btn btn-primary btn-lg">
                        اختبار الاتصال والمتابعة
                        <i class="fas fa-arrow-left me-2"></i>
                    </button>
                </div>
            </form>
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