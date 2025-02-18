-- إنشاء جدول الأدوار
CREATE TABLE `roles` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(50) NOT NULL,
    `slug` varchar(50) NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `roles_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول الصلاحيات
CREATE TABLE `permissions` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(50) NOT NULL,
    `slug` varchar(50) NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `permissions_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول العلاقة بين الأدوار والصلاحيات
CREATE TABLE `role_has_permissions` (
    `role_id` bigint(20) unsigned NOT NULL,
    `permission_id` bigint(20) unsigned NOT NULL,
    PRIMARY KEY (`role_id`,`permission_id`),
    KEY `role_has_permissions_permission_id_foreign` (`permission_id`),
    CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
    CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إدخال الأدوار الأساسية
INSERT INTO `roles` (`name`, `slug`, `created_at`, `updated_at`) VALUES
('Super Admin', 'super-admin', NOW(), NOW()),
('Admin', 'admin', NOW(), NOW()),
('User', 'user', NOW(), NOW()),
('Employee', 'employee', NOW(), NOW());

-- إدخال الصلاحيات الأساسية
INSERT INTO `permissions` (`name`, `slug`, `created_at`, `updated_at`) VALUES
('إدارة المستخدمين', 'manage-users', NOW(), NOW()),
('إدارة الأدوار', 'manage-roles', NOW(), NOW()),
('إدارة الصلاحيات', 'manage-permissions', NOW(), NOW()),
('إدارة الإعدادات', 'manage-settings', NOW(), NOW()),
('إدارة النسخ الاحتياطية', 'manage-backups', NOW(), NOW()),
('عرض السجلات', 'view-logs', NOW(), NOW()); 