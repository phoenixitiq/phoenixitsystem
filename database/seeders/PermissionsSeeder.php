<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        // الأذونات الأساسية
        $permissions = [
            // إدارة النظام
            ['name' => 'view-dashboard', 'display_name' => 'عرض لوحة التحكم'],
            ['name' => 'manage-settings', 'display_name' => 'إدارة الإعدادات'],
            ['name' => 'view-logs', 'display_name' => 'عرض السجلات'],
            
            // إدارة المستخدمين
            ['name' => 'view-users', 'display_name' => 'عرض المستخدمين'],
            ['name' => 'manage-users', 'display_name' => 'إدارة المستخدمين'],
            ['name' => 'manage-roles', 'display_name' => 'إدارة الصلاحيات'],
            
            // إدارة الفريق
            ['name' => 'view-team', 'display_name' => 'عرض الفريق'],
            ['name' => 'manage-team', 'display_name' => 'إدارة الفريق'],
            ['name' => 'view-departments', 'display_name' => 'عرض الأقسام'],
            ['name' => 'manage-departments', 'display_name' => 'إدارة الأقسام'],
            
            // إدارة الوكلاء
            ['name' => 'view-agents', 'display_name' => 'عرض الوكلاء'],
            ['name' => 'manage-agents', 'display_name' => 'إدارة الوكلاء'],
            ['name' => 'approve-agents', 'display_name' => 'اعتماد الوكلاء'],
            
            // إدارة الخدمات والباقات
            ['name' => 'view-services', 'display_name' => 'عرض الخدمات'],
            ['name' => 'manage-services', 'display_name' => 'إدارة الخدمات'],
            ['name' => 'view-packages', 'display_name' => 'عرض الباقات'],
            ['name' => 'manage-packages', 'display_name' => 'إدارة الباقات'],
            
            // إدارة الطلبات
            ['name' => 'view-orders', 'display_name' => 'عرض الطلبات'],
            ['name' => 'manage-orders', 'display_name' => 'إدارة الطلبات'],
            ['name' => 'process-payments', 'display_name' => 'معالجة المدفوعات'],
            
            // إدارة التوظيف
            ['name' => 'view-careers', 'display_name' => 'عرض الوظائف'],
            ['name' => 'manage-careers', 'display_name' => 'إدارة الوظائف'],
            ['name' => 'view-applications', 'display_name' => 'عرض طلبات التوظيف'],
            ['name' => 'manage-applications', 'display_name' => 'إدارة طلبات التوظيف'],
            
            // إدارة المحتوى
            ['name' => 'view-content', 'display_name' => 'عرض المحتوى'],
            ['name' => 'manage-content', 'display_name' => 'إدارة المحتوى'],
            ['name' => 'publish-content', 'display_name' => 'نشر المحتوى']
        ];

        // إدخال الأذونات
        DB::table('permissions')->insert($permissions);

        // إضافة الأذونات للمدير
        $adminRoleId = DB::table('roles')->where('name', 'super-admin')->value('id');
        $permissionIds = DB::table('permissions')->pluck('id');

        $rolePermissions = $permissionIds->map(function($permissionId) use ($adminRoleId) {
            return [
                'role_id' => $adminRoleId,
                'permission_id' => $permissionId,
                'created_at' => now(),
                'updated_at' => now()
            ];
        })->toArray();

        DB::table('role_permissions')->insert($rolePermissions);
    }
} 