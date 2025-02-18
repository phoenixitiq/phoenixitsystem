<?php

namespace Install\Controllers;

use Install\Services\RequirementsChecker;
use Exception;
use PDO;
use PDOException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Setting;

// بدء الجلسة
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class InstallController
{
    private $currentStep = 0;
    private $config;
    private $errors = [];
    private $success = '';
    private $requirementsChecker;
    private $envTemplate = <<<EOT
APP_NAME=Phoenix IT
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
EOT;

    private $basePath;
    private $envFile;
    private $envExample;

    public function __construct()
    {
        $this->requirementsChecker = new RequirementsChecker();
        $this->config = require __DIR__ . '/../config/system.php';
        
        // تحديد الخطوة الحالية من الـ URL
        if (isset($_GET['step'])) {
            if ($_GET['step'] === 'welcome' || !is_numeric($_GET['step'])) {
                $this->currentStep = 0;
            } else {
                $this->currentStep = (int)$_GET['step'];
            }
        }

        // التحقق من وجود ملف .env وإنشاؤه إذا لم يكن موجوداً
        $this->checkEnvFile();

        $this->basePath = dirname(dirname(__DIR__));
        $this->envFile = $this->basePath . '/.env';
        $this->envExample = $this->basePath . '/.env.example';
    }

    private function checkEnvFile()
    {
        $envPath = dirname(__DIR__, 2) . '/.env';
        if (!file_exists($envPath)) {
            // نسخ ملف .env.example إذا كان موجوداً
            $envExample = dirname(__DIR__, 2) . '/.env.example';
            if (file_exists($envExample)) {
                copy($envExample, $envPath);
            } else {
                // إنشاء ملف .env جديد من القالب
                file_put_contents($envPath, $this->envTemplate);
            }
        }
    }

    public function handleRequest($post = [])
    {
        try {
            // التحقق من وجود ملف القفل
            if (file_exists(dirname(__DIR__) . '/install.lock')) {
                throw new Exception('النظام مثبت مسبقاً. يرجى حذف مجلد التثبيت.');
            }

            if (!empty($post)) {
                return $this->processStep($post);
            }
            
            return $this->renderStep();
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
            return $this->renderStep();
        }
    }

    private function processStep($data)
    {
        try {
            ob_start();

            switch ($this->currentStep) {
                case 1: // التحقق من المتطلبات
                    $requirements = $this->requirementsChecker->check();
                    if (!$requirements['status']) {
                        $this->errors = $requirements['errors'];
                        break;
                    }
                    $this->createDirectories(); // إنشاء المجلدات المطلوبة
                    header('Location: ?step=2');
                    exit;

                case 2: // إعداد قاعدة البيانات
                    if ($this->setupDatabase($data)) {
                        $this->importDatabaseSchema(); // استيراد هيكل قاعدة البيانات
                        header('Location: ?step=3');
                        exit;
                    }
                    break;

                case 3: // إعداد النظام
                    if ($this->setupSystem($data)) {
                        header('Location: ?step=4');
                        exit;
                    }
                    break;

                case 4: // إنشاء حساب المدير
                    if ($this->createAdminAccount($data)) {
                        $this->finishInstallation();
                        header('Location: ?step=5');
                        exit;
                    }
                    break;
            }

            ob_end_clean();
            return $this->renderStep();
        } catch (Exception $e) {
            ob_end_clean();
            $this->errors[] = $e->getMessage();
            return $this->renderStep();
        }
    }

    private function setupDatabase($data)
    {
        try {
            if (empty($data['db_host']) || empty($data['db_name']) || empty($data['db_user'])) {
                $this->errors[] = 'جميع الحقول مطلوبة';
                return false;
            }

            // تنظيف وتحقق من البيانات
            $dbHost = trim($data['db_host']);
            $dbPort = trim($data['db_port']);
            $dbName = trim($data['db_name']);
            $dbUser = trim($data['db_user']);
            $dbPass = isset($data['db_pass']) ? $data['db_pass'] : '';

            // التحقق من صحة اسم قاعدة البيانات
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $dbName)) {
                $this->errors[] = 'اسم قاعدة البيانات يجب أن يحتوي على أحرف وأرقام وشرطة سفلية فقط';
                return false;
            }

            // اختبار الاتصال بقاعدة البيانات
            $dsn = "mysql:host={$dbHost};port={$dbPort}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'
            ];

            $pdo = new PDO($dsn, $dbUser, $dbPass, $options);

            // إنشاء قاعدة البيانات إذا لم تكن موجودة
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            
            // التحقق من إمكانية الوصول لقاعدة البيانات
            $pdo->exec("USE `{$dbName}`");

            // حفظ إعدادات قاعدة البيانات
            $this->updateEnvFile([
                'DB_HOST' => $dbHost,
                'DB_PORT' => $dbPort,
                'DB_DATABASE' => $dbName,
                'DB_USERNAME' => $dbUser,
                'DB_PASSWORD' => $dbPass
            ]);

            // استيراد هيكل قاعدة البيانات مباشرة
            $this->importDatabaseSchema();

            $this->success = 'تم الاتصال بقاعدة البيانات بنجاح';
            return true;

        } catch (PDOException $e) {
            $error = $e->getMessage();
            if (strpos($error, 'Access denied') !== false) {
                $this->errors[] = 'خطأ في تسجيل الدخول: تأكد من صحة اسم المستخدم وكلمة المرور';
            } elseif (strpos($error, 'Unknown MySQL server host') !== false) {
                $this->errors[] = 'لا يمكن الاتصال بخادم قاعدة البيانات: تأكد من صحة عنوان الخادم';
            } elseif (strpos($error, 'Incorrect database name') !== false) {
                $this->errors[] = 'اسم قاعدة البيانات غير صحيح: يجب أن يحتوي على أحرف وأرقام وشرطة سفلية فقط';
            } else {
                $this->errors[] = 'خطأ في الاتصال بقاعدة البيانات: ' . $error;
            }
            return false;
        }
    }

    private function checkRequiredFiles()
    {
        $requiredFiles = [
            '../public/images/logo.svg' => 'الشعار الرئيسي',
            '../public/images/favicon.ico' => 'أيقونة الموقع'
        ];

        $missing = [];
        foreach ($requiredFiles as $file => $description) {
            if (!file_exists($file)) {
                $missing[] = "{$description} غير موجود في المسار: {$file}";
            }
        }

        if (!empty($missing)) {
            $this->errors = array_merge($this->errors, $missing);
            return false;
        }

        return true;
    }

    private function setupSystem($data)
    {
        // التحقق من وجود الملفات المطلوبة
        $this->checkRequiredFiles();

        if (empty($data['site_name']) || empty($data['site_url'])) {
            $this->errors[] = 'جميع الحقول مطلوبة';
            return false;
        }

        // حفظ إعدادات النظام
        $this->updateEnvFile([
            'APP_NAME' => $data['site_name'],
            'APP_URL' => $data['site_url']
        ]);

        return true;
    }

    private function createAdminAccount($data)
    {
        try {
            // التحقق من البيانات
            if (empty($data['admin_name']) || empty($data['admin_email']) || 
                empty($data['admin_password']) || empty($data['admin_password_confirm'])) {
                $this->errors[] = 'جميع الحقول مطلوبة';
                return false;
            }

            if ($data['admin_password'] !== $data['admin_password_confirm']) {
                $this->errors[] = 'كلمات المرور غير متطابقة';
                return false;
            }

            // إنشاء حساب المدير
            $dbConfig = $this->getDatabaseConfig();
            $pdo = new PDO(
                "mysql:host={$dbConfig['DB_HOST']};port={$dbConfig['DB_PORT']};dbname={$dbConfig['DB_DATABASE']}",
                $dbConfig['DB_USERNAME'],
                $dbConfig['DB_PASSWORD'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            // تشفير كلمة المرور
            $hashedPassword = password_hash($data['admin_password'], PASSWORD_DEFAULT);

            // إدخال بيانات المدير
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
            $stmt->execute([$data['admin_name'], $data['admin_email'], $hashedPassword]);
            
            $userId = $pdo->lastInsertId();

            // ربط المستخدم بدور المدير
            $stmt = $pdo->prepare("INSERT INTO role_user (user_id, role_id) SELECT ?, id FROM roles WHERE slug = 'admin'");
            $stmt->execute([$userId]);

            // حفظ بيانات المدير للعرض في صفحة النجاح
            $_SESSION['admin_email'] = $data['admin_email'];
            $_SESSION['admin_name'] = $data['admin_name'];

            return true;
        } catch (Exception $e) {
            $this->errors[] = 'خطأ في إنشاء حساب المدير: ' . $e->getMessage();
            return false;
        }
    }

    private function finishInstallation()
    {
        try {
            // إنشاء ملف القفل
            $lockFile = dirname(__DIR__) . '/install.lock';
            file_put_contents($lockFile, date('Y-m-d H:i:s'));

            // تحديث ملف .env بالإعدادات النهائية
            $this->updateEnvFile([
                'APP_ENV' => 'production',
                'APP_DEBUG' => 'false',
                'APP_INSTALLED' => 'true',
                'APP_INSTALL_DATE' => date('Y-m-d H:i:s'),
                'APP_URL' => rtrim($_POST['site_url'] ?? '', '/')
            ]);

            // حفظ رابط لوحة التحكم في الجلسة
            $_SESSION['admin_url'] = rtrim($_POST['site_url'] ?? '', '/') . '/admin';
            
            return true;
        } catch (Exception $e) {
            $this->errors[] = 'خطأ في إنهاء التثبيت: ' . $e->getMessage();
            return false;
        }
    }

    private function renderStep()
    {
        $data = [
            'currentStep' => $this->currentStep,
            'config' => $this->config,
            'errors' => $this->errors,
            'success' => $this->success,
            'site_url' => $_POST['site_url'] ?? '',
            'admin_email' => $_SESSION['admin_email'] ?? '',
            'admin_name' => $_SESSION['admin_name'] ?? ''
        ];

        switch ($this->currentStep) {
            case 1:
                $data['requirements'] = $this->requirementsChecker->check();
                return $this->renderView('requirements', $data);
            case 2:
                return $this->renderView('database', $data);
            case 3:
                return $this->renderView('system', $data);
            case 4:
                return $this->renderView('finish', $data);
            default:
                return $this->renderView('welcome', $data);
        }
    }

    private function renderView($view, $data)
    {
        extract($data);
        ob_start();
        include dirname(__DIR__) . "/views/{$view}.php";
        return ob_get_clean();
    }

    private function updateEnvFile($values)
    {
        try {
            $envPath = dirname(__DIR__, 2) . '/.env';
            $envContent = file_get_contents($envPath);
            if ($envContent === false) {
                throw new Exception('لا يمكن قراءة ملف .env');
            }

            foreach ($values as $key => $value) {
                $envContent = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}={$value}",
                    $envContent
                );
            }

            if (file_put_contents($envPath, $envContent) === false) {
                throw new Exception('لا يمكن تحديث ملف .env');
            }
        } catch (Exception $e) {
            throw new Exception('خطأ في تحديث ملف الإعدادات: ' . $e->getMessage());
        }
    }

    // إضافة دالة لإنشاء المجلدات المطلوبة
    private function createDirectories()
    {
        $directories = $this->config['requirements']['directories'];
        $basePath = dirname(__DIR__, 2);

        foreach ($directories as $directory => $permission) {
            $path = $basePath . '/' . $directory;
            if (!file_exists($path)) {
                if (!mkdir($path, octdec($permission), true)) {
                    throw new Exception("لا يمكن إنشاء المجلد: {$directory}");
                }
            } else {
                // تحديث الصلاحيات إذا كان المجلد موجوداً
                chmod($path, octdec($permission));
            }
        }
    }

    // إضافة دالة لاستيراد هيكل قاعدة البيانات
    private function importDatabaseSchema()
    {
        try {
            $schemaFile = dirname(__DIR__) . '/database/schema.sql';
            if (!file_exists($schemaFile)) {
                throw new Exception('ملف هيكل قاعدة البيانات غير موجود');
            }

            $sql = file_get_contents($schemaFile);
            if (!$sql) {
                throw new Exception('لا يمكن قراءة ملف هيكل قاعدة البيانات');
            }

            // استخدام نفس بيانات الاتصال من setupDatabase
            $dbConfig = [
                'DB_HOST' => trim($_POST['db_host']),
                'DB_PORT' => trim($_POST['db_port']),
                'DB_DATABASE' => trim($_POST['db_name']),
                'DB_USERNAME' => trim($_POST['db_user']),
                'DB_PASSWORD' => isset($_POST['db_pass']) ? $_POST['db_pass'] : ''
            ];

            try {
                $dsn = "mysql:host={$dbConfig['DB_HOST']};port={$dbConfig['DB_PORT']};dbname={$dbConfig['DB_DATABASE']}";
                $pdo = new PDO(
                    $dsn,
                    $dbConfig['DB_USERNAME'],
                    $dbConfig['DB_PASSWORD'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'
                    ]
                );

                // تقسيم الأوامر SQL وتنفيذها بشكل منفصل
                $statements = array_filter(array_map('trim', explode(';', $sql)));
                foreach ($statements as $statement) {
                    if (!empty($statement)) {
                        $pdo->exec($statement);
                    }
                }

                return true;
            } catch (PDOException $e) {
                throw new Exception('خطأ في الاتصال بقاعدة البيانات: ' . $e->getMessage());
            }
        } catch (Exception $e) {
            throw new Exception('خطأ في استيراد هيكل قاعدة البيانات: ' . $e->getMessage());
        }
    }

    private function getDatabaseConfig()
    {
        $envPath = dirname(__DIR__, 2) . '/.env';
        $envContent = file_get_contents($envPath);
        $config = [];
        
        preg_match('/DB_HOST=(.*)/', $envContent, $matches);
        $config['DB_HOST'] = trim($matches[1] ?? '127.0.0.1');
        
        preg_match('/DB_PORT=(.*)/', $envContent, $matches);
        $config['DB_PORT'] = trim($matches[1] ?? '3306');
        
        preg_match('/DB_DATABASE=(.*)/', $envContent, $matches);
        $config['DB_DATABASE'] = trim($matches[1]);
        
        preg_match('/DB_USERNAME=(.*)/', $envContent, $matches);
        $config['DB_USERNAME'] = trim($matches[1]);
        
        preg_match('/DB_PASSWORD=(.*)/', $envContent, $matches);
        $config['DB_PASSWORD'] = trim($matches[1]);
        
        return $config;
    }

    public function setupAdmin(Request $request)
    {
        // التحقق من البيانات
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'company_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20'
        ]);

        try {
            // إنشاء حساب المدير
            $admin = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'],
                'role' => 'admin',
                'is_active' => true
            ]);

            // إعداد بيانات الشركة
            Setting::set('company_name', $validated['company_name']);
            Setting::set('company_email', $validated['email']);
            Setting::set('company_phone', $validated['phone']);

            // إنشاء ملف القفل
            $this->createLockFile();

            return redirect()->route('install.finish')
                ->with('success', 'تم إنشاء حساب المدير بنجاح');

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء إنشاء حساب المدير: ' . $e->getMessage());
        }
    }

    private function copyAssets()
    {
        // مسار المجلد العام
        $publicPath = dirname(__DIR__, 2) . '/public';
        
        // إنشاء المجلدات المطلوبة
        $directories = [
            $publicPath . '/images',
            $publicPath . '/install/css',
            $publicPath . '/install/js'
        ];
        
        foreach ($directories as $dir) {
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }
        }
        
        // نسخ الشعار
        $logoSource = __DIR__ . '/../assets/images/logo.svg';
        $logoTarget = $publicPath . '/images/logo.svg';
        if (file_exists($logoSource) && !file_exists($logoTarget)) {
            copy($logoSource, $logoTarget);
        }
        
        // نسخ ملفات CSS
        $cssSource = __DIR__ . '/../assets/css/install.css';
        $cssTarget = $publicPath . '/install/css/install.css';
        if (file_exists($cssSource)) {
            copy($cssSource, $cssTarget);
        }
    }

    private function createLockFile()
    {
        $lockFile = dirname(__DIR__) . '/install.lock';
        file_put_contents($lockFile, date('Y-m-d H:i:s'));
    }
} 