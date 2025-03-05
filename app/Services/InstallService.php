<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Exception;
use Illuminate\Support\Facades\File;

class InstallService
{
    public function setupDatabase($data)
    {
        try {
            // 1. إنشاء قاعدة البيانات
            $pdo = new \PDO(
                "mysql:host={$data['db_host']};port={$data['db_port']}",
                $data['db_username'],
                $data['db_password']
            );
            
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$data['db_database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            
            // 2. تحديث ملف .env
            $this->updateEnvironmentFile($data);
            
            // 3. تشغيل الهجرات
            Artisan::call('migrate:fresh', ['--force' => true]);
            
            // 4. إضافة البيانات الأساسية
            $this->seedBasicData();

            return true;
        } catch (Exception $e) {
            throw new Exception('DB: ' . $e->getMessage());
        }
    }

    private function seedBasicData()
    {
        try {
            // 1. إضافة الأدوار
            $roles = DB::table('roles')->insert([
                ['name' => 'super-admin', 'display_name' => 'مدير النظام'],
                ['name' => 'admin', 'display_name' => 'مدير'],
                ['name' => 'employee', 'display_name' => 'موظف'],
                ['name' => 'agent', 'display_name' => 'وكيل']
            ]);

            // 2. إضافة الصلاحيات
            $permissions = DB::table('permissions')->insert([
                ['name' => 'manage-team', 'display_name' => 'إدارة الفريق'],
                ['name' => 'view-team', 'display_name' => 'عرض الفريق'],
                ['name' => 'manage-careers', 'display_name' => 'إدارة الوظائف'],
                ['name' => 'view-applications', 'display_name' => 'عرض طلبات التوظيف']
            ]);

            // 3. ربط الصلاحيات بالأدوار
            $adminRole = DB::table('roles')->where('name', 'super-admin')->first();
            $permissionIds = DB::table('permissions')->pluck('id');
            
            foreach ($permissionIds as $permissionId) {
                DB::table('role_permissions')->insert([
                    'role_id' => $adminRole->id,
                    'permission_id' => $permissionId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // إضافة المستخدم الأساسي
            DB::table('users')->insert([
                'name' => 'مدير النظام',
                'email' => 'admin@phoenixitiq.com',
                'password' => bcrypt('admin123'),
                'role_id' => 1, // super-admin
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // إضافة الأقسام
            DB::table('departments')->insert([
                [
                    'name' => 'management',
                    'display_name_ar' => 'الإدارة',
                    'display_name_en' => 'Management'
                ],
                [
                    'name' => 'tech',
                    'display_name_ar' => 'التقنية',
                    'display_name_en' => 'Technology'
                ],
                [
                    'name' => 'design',
                    'display_name_ar' => 'التصميم',
                    'display_name_en' => 'Design'
                ],
                [
                    'name' => 'marketing',
                    'display_name_ar' => 'التسويق',
                    'display_name_en' => 'Marketing'
                ],
                [
                    'name' => 'support',
                    'display_name_ar' => 'خدمة العملاء',
                    'display_name_en' => 'Customer Support'
                ]
            ]);

            // إضافة الوظائف
            DB::table('careers')->insert([
                [
                    'title_ar' => 'مطور ويب',
                    'title_en' => 'Web Developer',
                    'description_ar' => 'نبحث عن مطور ويب موهوب للانضمام إلى فريقنا',
                    'description_en' => 'We are looking for a talented web developer to join our team',
                    'requirements_ar' => 'خبرة لا تقل عن 3 سنوات في تطوير الويب',
                    'requirements_en' => 'Minimum 3 years experience in web development',
                    'department_id' => 2, // قسم التقنية
                    'status' => 'open',
                    'slug' => 'web-developer',
                    'is_active' => true
                ],
                [
                    'title_ar' => 'مصمم جرافيك',
                    'title_en' => 'Graphic Designer',
                    'description_ar' => 'مطلوب مصمم جرافيك مبدع',
                    'description_en' => 'Looking for a creative graphic designer',
                    'requirements_ar' => 'خبرة في برامج Adobe Creative Suite',
                    'requirements_en' => 'Experience with Adobe Creative Suite',
                    'department_id' => 3, // قسم التصميم
                    'status' => 'open',
                    'slug' => 'graphic-designer',
                    'is_active' => true
                ]
            ]);

            // إضافة الإعدادات الأساسية
            DB::table('settings')->insert([
                ['key' => 'site_name', 'value' => 'Phoenix IT'],
                ['key' => 'site_email', 'value' => 'info@phoenixitiq.com'],
                ['key' => 'site_phone', 'value' => '+966000000000'],
                ['key' => 'site_address', 'value' => 'السعودية']
            ]);

        } catch (Exception $e) {
            throw new Exception('Data: ' . $e->getMessage());
        }
    }

    private function updateEnvironmentFile($data)
    {
        try {
            $path = base_path('.env');
            if (!file_exists($path)) {
                copy(base_path('.env.example'), $path);
            }

            $env = file_get_contents($path);

            $env = preg_replace('/DB_HOST=.*/', 'DB_HOST=' . $data['db_host'], $env);
            $env = preg_replace('/DB_PORT=.*/', 'DB_PORT=' . $data['db_port'], $env);
            $env = preg_replace('/DB_DATABASE=.*/', 'DB_DATABASE=' . $data['db_database'], $env);
            $env = preg_replace('/DB_USERNAME=.*/', 'DB_USERNAME=' . $data['db_username'], $env);
            $env = preg_replace('/DB_PASSWORD=.*/', 'DB_PASSWORD=' . $data['db_password'], $env);

            file_put_contents($path, $env);
        } catch (Exception $e) {
            throw new Exception('Env: ' . $e->getMessage());
        }
    }

    public function saveEnvironmentConfig($data)
    {
        $envPath = base_path('.env');
        $envExample = base_path('.env.example');

        if (!file_exists($envPath)) {
            copy($envExample, $envPath);
        }

        $env = file_get_contents($envPath);

        $env = str_replace('DB_HOST=' . env('DB_HOST'), 'DB_HOST=' . $data['db_host'], $env);
        $env = str_replace('DB_PORT=' . env('DB_PORT'), 'DB_PORT=' . $data['db_port'], $env);
        $env = str_replace('DB_DATABASE=' . env('DB_DATABASE'), 'DB_DATABASE=' . $data['db_database'], $env);
        $env = str_replace('DB_USERNAME=' . env('DB_USERNAME'), 'DB_USERNAME=' . $data['db_username'], $env);
        $env = str_replace('DB_PASSWORD=' . env('DB_PASSWORD'), 'DB_PASSWORD=' . $data['db_password'], $env);

        file_put_contents($envPath, $env);
    }

    public function saveSystemSettings($data)
    {
        $envPath = base_path('.env');
        $env = file_get_contents($envPath);

        $env = str_replace('APP_NAME=' . env('APP_NAME'), 'APP_NAME="' . $data['app_name'] . '"', $env);
        $env = str_replace('APP_URL=' . env('APP_URL'), 'APP_URL=' . $data['app_url'], $env);

        file_put_contents($envPath, $env);

        // يمكن إضافة المزيد من الإعدادات هنا
        // مثل إعدادات البريد الإلكتروني وغيرها
    }
}