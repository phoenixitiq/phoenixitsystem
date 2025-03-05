<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // إضافة المستخدم الافتراضي
        DB::table('users')->insert([
            'name' => 'Phoenix Admin',
            'email' => 'admin@phoenixitiq.com',
            'password' => Hash::make('Phoenix@2024'),
            'role' => 'admin',
            'status' => true,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // إضافة الإعدادات الأساسية
        $settings = [
            // إعدادات عامة
            ['key' => 'site_name', 'value' => 'Phoenix IT', 'group_name' => 'general'],
            ['key' => 'site_description', 'value' => 'نظام إدارة الخدمات التقنية', 'group_name' => 'general'],
            ['key' => 'site_logo', 'value' => 'logo.png', 'group_name' => 'general'],
            ['key' => 'site_email', 'value' => 'info@phoenixitiq.com', 'group_name' => 'general'],
            
            // إعدادات النظام
            ['key' => 'system_version', 'value' => '1.0.0', 'group_name' => 'system'],
            ['key' => 'system_timezone', 'value' => 'Asia/Riyadh', 'group_name' => 'system'],
            ['key' => 'system_language', 'value' => 'ar', 'group_name' => 'system'],
            
            // إعدادات الموظفين
            ['key' => 'work_hours', 'value' => '8', 'group_name' => 'employees'],
            ['key' => 'work_days', 'value' => '5', 'group_name' => 'employees'],
            ['key' => 'vacation_days', 'value' => '30', 'group_name' => 'employees'],
            
            // إعدادات النسخ الاحتياطي
            ['key' => 'backup_enabled', 'value' => '1', 'group_name' => 'backup'],
            ['key' => 'backup_frequency', 'value' => 'daily', 'group_name' => 'backup'],
            ['key' => 'backup_retention', 'value' => '7', 'group_name' => 'backup']
        ];

        DB::table('settings')->insert($settings);

        // إضافة أقسام الموظفين
        $departments = [
            ['name' => 'تطوير البرمجيات'],
            ['name' => 'التسويق'],
            ['name' => 'خدمة العملاء'],
            ['name' => 'الموارد البشرية'],
            ['name' => 'المالية']
        ];

        foreach ($departments as $dept) {
            DB::table('departments')->insert([
                'name' => $dept['name'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $this->call([
            PermissionsSeeder::class
        ]);
    }
}
