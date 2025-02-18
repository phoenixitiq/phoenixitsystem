-- إنشاء جداول النظام

-- جدول المستخدمين (الجدول الأساسي)
CREATE TABLE IF NOT EXISTS `users` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    `phone` varchar(20) DEFAULT NULL,
    `avatar` varchar(255) DEFAULT NULL,
    `remember_token` varchar(100) DEFAULT NULL,
    `last_login_at` timestamp NULL DEFAULT NULL,
    `last_login_ip` varchar(45) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `email_verified_at` timestamp NULL DEFAULT NULL,
    `status` enum('active','inactive','blocked') NOT NULL DEFAULT 'active',
    `language` varchar(10) DEFAULT 'ar',
    `national_id` varchar(20) DEFAULT NULL,
    `passport_number` varchar(20) DEFAULT NULL,
    `emergency_contact` varchar(255) DEFAULT NULL,
    `birth_date` date DEFAULT NULL,
    `address` text DEFAULT NULL,
    `contract_type` enum('full_time','part_time','temporary') NOT NULL DEFAULT 'full_time',
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`),
    KEY `users_name_index` (`name`),
    KEY `users_email_index` (`email`),
    KEY `users_created_at_index` (`created_at`),
    KEY `users_status_index` (`status`),
    KEY `users_national_id_index` (`national_id`),
    KEY `users_phone_index` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جداول الأمان والصلاحيات
CREATE TABLE IF NOT EXISTS `roles` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `slug` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `roles_slug_unique` (`slug`),
    KEY `roles_name_index` (`name`),
    KEY `roles_slug_index` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `permissions` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `slug` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `group` varchar(255) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `permissions_slug_unique` (`slug`),
    KEY `permissions_name_index` (`name`),
    KEY `permissions_slug_index` (`slug`),
    KEY `permissions_group_index` (`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `permission_role` (
    `permission_id` bigint(20) unsigned NOT NULL,
    `role_id` bigint(20) unsigned NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`permission_id`, `role_id`),
    FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `role_user` (
    `user_id` bigint(20) unsigned NOT NULL,
    `role_id` bigint(20) unsigned NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`, `role_id`),
    KEY `role_user_role_id_foreign` (`role_id`),
    CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جداول النظام
CREATE TABLE IF NOT EXISTS `settings` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `key` varchar(255) NOT NULL,
    `value` text DEFAULT NULL,
    `group` varchar(255) DEFAULT NULL,
    `autoload` tinyint(1) NOT NULL DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `settings_key_unique` (`key`),
    KEY `settings_key_index` (`key`),
    KEY `settings_group_index` (`group`),
    KEY `settings_autoload_index` (`autoload`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `employees` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint(20) unsigned NOT NULL,
    `employee_number` varchar(50) NOT NULL,
    `position` varchar(255) NOT NULL,
    `department` varchar(255) NOT NULL,
    `salary` decimal(10,2) NOT NULL,
    `bank_account` varchar(50) DEFAULT NULL,
    `bank_name` varchar(255) DEFAULT NULL,
    `join_date` date NOT NULL,
    `end_date` date DEFAULT NULL,
    `status` enum('active','inactive','on_leave') NOT NULL DEFAULT 'active',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `department_id` bigint(20) unsigned NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `employees_employee_number_unique` (`employee_number`),
    KEY `employees_user_id_foreign` (`user_id`),
    KEY `employees_employee_number_index` (`employee_number`),
    KEY `employees_department_status_index` (`department`, `status`),
    KEY `employees_position_index` (`position`),
    KEY `employees_join_date_index` (`join_date`),
    CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `employees_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `payrolls` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `employee_id` bigint(20) unsigned NOT NULL,
    `month` date NOT NULL,
    `basic_salary` decimal(10,2) NOT NULL,
    `allowances` decimal(10,2) DEFAULT 0,
    `deductions` decimal(10,2) DEFAULT 0,
    `overtime_hours` decimal(5,2) DEFAULT 0,
    `overtime_rate` decimal(10,2) DEFAULT 0,
    `payment_date` date NOT NULL,
    `payment_method` enum('bank_transfer','cash','cheque') NOT NULL,
    `transaction_id` varchar(100) DEFAULT NULL,
    `notes` text DEFAULT NULL,
    `status` enum('pending','processing','paid','cancelled') NOT NULL DEFAULT 'pending',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `payroll_date` date NOT NULL COMMENT 'تاريخ كشف الراتب',
    `currency` varchar(3) NOT NULL DEFAULT 'SAR',
    `exchange_rate` decimal(10,4) DEFAULT 1.0000,
    `total_allowances` decimal(10,2) GENERATED ALWAYS AS (allowances + overtime_hours * overtime_rate) STORED,
    `total_deductions` decimal(10,2) GENERATED ALWAYS AS (deductions) STORED,
    `gross_salary` decimal(10,2) GENERATED ALWAYS AS (basic_salary + total_allowances) STORED,
    `net_salary` decimal(10,2) GENERATED ALWAYS AS (gross_salary - total_deductions) STORED,
    PRIMARY KEY (`id`),
    KEY `payrolls_employee_id_foreign` (`employee_id`),
    KEY `payrolls_month_index` (`month`),
    KEY `payrolls_status_index` (`status`),
    KEY `payrolls_payment_date_index` (`payment_date`),
    KEY `payrolls_employee_month_index` (`employee_id`, `month`),
    CONSTRAINT `payrolls_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `activity_logs` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint(20) unsigned NOT NULL,
    `action` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `model_type` varchar(255) DEFAULT NULL,
    `model_id` bigint(20) unsigned DEFAULT NULL,
    `properties` json DEFAULT NULL,
    `ip_address` varchar(45) DEFAULT NULL,
    `user_agent` varchar(255) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `activity_logs_user_id_foreign` (`user_id`),
    KEY `activity_logs_action_index` (`action`),
    KEY `activity_logs_created_at_index` (`created_at`),
    KEY `activity_logs_model_type_model_id_index` (`model_type`, `model_id`),
    CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `password_resets` (
    `email` varchar(255) NOT NULL,
    `token` varchar(255) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY `password_resets_email_index` (`email`),
    KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `salary_components` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `type` enum('allowance','deduction') NOT NULL,
    `is_fixed` tinyint(1) NOT NULL DEFAULT 0,
    `is_taxable` tinyint(1) NOT NULL DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `salary_components_type_index` (`type`),
    KEY `salary_components_is_fixed_index` (`is_fixed`),
    KEY `salary_components_is_taxable_index` (`is_taxable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `employee_documents` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `employee_id` bigint(20) unsigned NOT NULL,
    `type` varchar(50) NOT NULL,
    `title` varchar(255) NOT NULL,
    `file_path` varchar(255) NOT NULL,
    `expiry_date` date DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `employee_documents_employee_id_foreign` (`employee_id`),
    KEY `employee_documents_type_index` (`type`),
    KEY `employee_documents_expiry_date_index` (`expiry_date`),
    CONSTRAINT `employee_documents_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `departments` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `code` varchar(50) NOT NULL,
    `parent_id` bigint(20) unsigned DEFAULT NULL,
    `manager_id` bigint(20) unsigned DEFAULT NULL,
    `description` text DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `departments_code_unique` (`code`),
    KEY `departments_parent_id_foreign` (`parent_id`),
    KEY `departments_manager_id_foreign` (`manager_id`),
    CONSTRAINT `departments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `departments_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إدخال البيانات الأساسية
INSERT INTO `roles` (`name`, `slug`, `description`) VALUES
('مدير النظام', 'admin', 'كامل الصلاحيات للنظام'),
('مدير الموارد البشرية', 'hr_manager', 'إدارة شؤون الموظفين والرواتب'),
('محاسب', 'accountant', 'إدارة الرواتب والمدفوعات'),
('موظف', 'employee', 'صلاحيات الموظف الأساسية');

INSERT INTO `permissions` (`name`, `slug`, `description`, `group`) VALUES
('إدارة المستخدمين', 'manage-users', 'إدارة حسابات المستخدمين', 'النظام'),
('إدارة الأدوار', 'manage-roles', 'إدارة الأدوار والصلاحيات', 'النظام'),
('إدارة الصلاحيات', 'manage-permissions', 'إدارة صلاحيات النظام', 'النظام'),
('إدارة الإعدادات', 'manage-settings', 'إدارة إعدادات النظام', 'النظام'),
('إدارة الموظفين', 'manage-employees', 'إدارة بيانات الموظفين', 'الموارد البشرية'),
('إدارة الرواتب', 'manage-payrolls', 'إدارة الرواتب والمدفوعات', 'المالية'),
('عرض الراتب', 'view-salary', 'عرض تفاصيل الراتب', 'المالية'),
('طلب سلفة', 'request-advance', 'تقديم طلب سلفة', 'المالية');

-- ربط الصلاحيات بالأدوار
INSERT INTO `permission_role` (`permission_id`, `role_id`, `created_at`, `updated_at`)
SELECT p.id, r.id, NOW(), NOW()
FROM `permissions` p, `roles` r 
WHERE r.slug IN ('admin', 'hr_manager', 'accountant')
AND (
    CASE 
        WHEN r.slug = 'admin' THEN TRUE
        WHEN r.slug = 'hr_manager' THEN p.slug IN ('manage-employees', 'manage-payrolls', 'view-salary')
        WHEN r.slug = 'accountant' THEN p.slug IN ('manage-payrolls', 'view-salary')
    END
);

-- إضافة الأقسام الأساسية
INSERT INTO `departments` (`name`, `code`, `description`) VALUES
('الإدارة العليا', 'MGMT', 'الإدارة العليا للشركة'),
('الموارد البشرية', 'HR', 'إدارة الموارد البشرية'),
('المالية', 'FIN', 'الإدارة المالية'),
('تقنية المعلومات', 'IT', 'إدارة تقنية المعلومات');

-- إنشاء قاعدة البيانات بدعم UTF-8
CREATE DATABASE IF NOT EXISTS `phoenix_system` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `phoenix_system`;

-- حذف الجداول إن وجدت
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `roles`;
DROP TABLE IF EXISTS `permissions`;
DROP TABLE IF EXISTS `permission_role`;
DROP TABLE IF EXISTS `departments`;
DROP TABLE IF EXISTS `employees`;
DROP TABLE IF EXISTS `attendance_records`;
DROP TABLE IF EXISTS `salary_advances`;
DROP TABLE IF EXISTS `payrolls`;
DROP TABLE IF EXISTS `settings`;
DROP TABLE IF EXISTS `leaves`;
DROP TABLE IF EXISTS `documents`;
DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `activity_logs`;
SET FOREIGN_KEY_CHECKS = 1;

-- إنشاء الجداول الأساسية
-- 1. جداول المستخدمين والصلاحيات
CREATE TABLE `roles` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(50) NOT NULL,
    `slug` varchar(50) NOT NULL UNIQUE,
    `description` text DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `permissions` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(50) NOT NULL,
    `slug` varchar(50) NOT NULL UNIQUE,
    `description` text DEFAULT NULL,
    `group` varchar(50) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL UNIQUE,
    `password` varchar(255) NOT NULL,
    `phone` varchar(20) DEFAULT NULL,
    `avatar` varchar(255) DEFAULT NULL,
    `role_id` bigint(20) unsigned DEFAULT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `email_verified_at` timestamp NULL DEFAULT NULL,
    `remember_token` varchar(100) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `permission_role` (
    `permission_id` bigint(20) unsigned NOT NULL,
    `role_id` bigint(20) unsigned NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`permission_id`, `role_id`),
    FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. جداول الهيكل التنظيمي
CREATE TABLE `departments` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `code` varchar(20) NOT NULL UNIQUE,
    `description` text DEFAULT NULL,
    `manager_id` bigint(20) unsigned DEFAULT NULL,
    `parent_id` bigint(20) unsigned DEFAULT NULL,
    `level` int DEFAULT 0,
    `path` varchar(255) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`parent_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `employees` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint(20) unsigned NOT NULL,
    `department_id` bigint(20) unsigned DEFAULT NULL,
    `employee_number` varchar(50) NOT NULL UNIQUE,
    `position` varchar(100) DEFAULT NULL,
    `join_date` date NOT NULL,
    `contract_type` enum('full_time','part_time','temporary','remote') DEFAULT 'full_time',
    `basic_salary` decimal(10,2) NOT NULL DEFAULT 0,
    `bank_name` varchar(100) DEFAULT NULL,
    `bank_account` varchar(50) DEFAULT NULL,
    `iban` varchar(50) DEFAULT NULL,
    `national_id` varchar(20) DEFAULT NULL,
    `passport_number` varchar(20) DEFAULT NULL,
    `emergency_contact` varchar(100) DEFAULT NULL,
    `emergency_phone` varchar(20) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. جداول الحضور والإجازات
CREATE TABLE `attendance_records` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `employee_id` bigint(20) unsigned NOT NULL,
    `check_in` datetime NOT NULL,
    `check_out` datetime DEFAULT NULL,
    `work_hours` decimal(5,2) DEFAULT NULL,
    `overtime_hours` decimal(5,2) DEFAULT 0,
    `status` enum('present','absent','late','leave') DEFAULT 'present',
    `ip_address` varchar(45) DEFAULT NULL,
    `location` point DEFAULT NULL,
    `device_info` varchar(255) DEFAULT NULL,
    `notes` text DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `leaves` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `employee_id` bigint(20) unsigned NOT NULL,
    `type` enum('annual','sick','emergency','unpaid','other') NOT NULL,
    `start_date` date NOT NULL,
    `end_date` date NOT NULL,
    `days` int NOT NULL,
    `reason` text DEFAULT NULL,
    `attachment` varchar(255) DEFAULT NULL,
    `status` enum('pending','approved','rejected','cancelled') DEFAULT 'pending',
    `approved_by` bigint(20) unsigned DEFAULT NULL,
    `approval_date` datetime DEFAULT NULL,
    `rejection_reason` text DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. جداول العملاء والمشاريع
CREATE TABLE `clients` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `phone` varchar(20) DEFAULT NULL,
    `company_name` varchar(255) DEFAULT NULL,
    `contact_person` varchar(255) DEFAULT NULL,
    `address` text DEFAULT NULL,
    `city` varchar(100) DEFAULT NULL,
    `country` varchar(100) DEFAULT NULL,
    `postal_code` varchar(20) DEFAULT NULL,
    `tax_number` varchar(50) DEFAULT NULL,
    `status` enum('active','inactive','blocked') DEFAULT 'active',
    `source` varchar(50) DEFAULT NULL,
    `industry` varchar(100) DEFAULT NULL,
    `notes` text DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `projects` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `client_id` bigint(20) unsigned NOT NULL,
    `name` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `type` enum('web','mobile','marketing','hosting','other') NOT NULL,
    `status` enum('new','in_progress','completed','cancelled','on_hold') DEFAULT 'new',
    `start_date` date DEFAULT NULL,
    `end_date` date DEFAULT NULL,
    `budget` decimal(10,2) DEFAULT NULL,
    `manager_id` bigint(20) unsigned DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. جداول الوثائق والملفات
CREATE TABLE `documents` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `employee_id` bigint(20) unsigned NOT NULL,
    `type` varchar(50) NOT NULL,
    `title` varchar(255) NOT NULL,
    `file_path` varchar(255) NOT NULL,
    `expiry_date` date DEFAULT NULL,
    `notes` text DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. جداول النظام
CREATE TABLE `notifications` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint(20) unsigned NOT NULL,
    `title` varchar(255) NOT NULL,
    `content` text NOT NULL,
    `type` varchar(50) NOT NULL,
    `read_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `activity_logs` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint(20) unsigned DEFAULT NULL,
    `action` varchar(50) NOT NULL,
    `description` text NOT NULL,
    `ip_address` varchar(45) DEFAULT NULL,
    `user_agent` varchar(255) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `settings` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `key` varchar(100) NOT NULL UNIQUE,
    `value` text DEFAULT NULL,
    `group_name` varchar(50) DEFAULT 'general',
    `is_system` tinyint(1) DEFAULT 0,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء الفهارس
CREATE INDEX idx_user_email ON users(email);
CREATE INDEX idx_user_role ON users(role_id);
CREATE INDEX idx_employee_number ON employees(employee_number);
CREATE INDEX idx_attendance_date ON attendance_records(check_in);
CREATE INDEX idx_salary_month_year ON payrolls(month, year);
CREATE INDEX idx_settings_group ON settings(group_name);
CREATE INDEX idx_notifications_user ON notifications(user_id, read_at);
CREATE INDEX idx_activity_logs_user ON activity_logs(user_id, created_at);
CREATE INDEX idx_leaves_employee ON leaves(employee_id, start_date);
CREATE INDEX idx_documents_employee ON documents(employee_id, type);

-- جداول إضافية للنظام

-- 1. جدول الباقات
CREATE TABLE `packages` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `slug` varchar(100) NOT NULL UNIQUE,
    `description` text DEFAULT NULL,
    `category` enum('web','mobile','marketing','hosting','maintenance') NOT NULL,
    `price` decimal(10,2) NOT NULL,
    `duration` int NOT NULL DEFAULT 1, -- بالشهور
    `features` json DEFAULT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `display_order` int DEFAULT 0,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. جدول الاشتراكات
CREATE TABLE `subscriptions` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `client_id` bigint(20) unsigned NOT NULL,
    `package_id` bigint(20) unsigned NOT NULL,
    `start_date` date NOT NULL,
    `end_date` date NOT NULL,
    `status` enum('active','expired','cancelled','pending') DEFAULT 'pending',
    `payment_status` enum('paid','unpaid','partial') DEFAULT 'unpaid',
    `total_amount` decimal(10,2) NOT NULL,
    `paid_amount` decimal(10,2) DEFAULT 0,
    `billing_cycle` enum('monthly','quarterly','semi_annual','annual','full_contract') DEFAULT 'monthly',
    `auto_renew` tinyint(1) DEFAULT 0,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. جدول العملاء
CREATE TABLE `clients` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `phone` varchar(20) DEFAULT NULL,
    `company_name` varchar(255) DEFAULT NULL,
    `address` text DEFAULT NULL,
    `country` varchar(100) DEFAULT NULL,
    `city` varchar(100) DEFAULT NULL,
    `status` enum('active','inactive','blocked') DEFAULT 'active',
    `source` varchar(50) DEFAULT NULL,
    `notes` text DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. جدول المشاريع
CREATE TABLE `projects` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `client_id` bigint(20) unsigned NOT NULL,
    `name` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `type` enum('web','mobile','marketing','hosting','other') NOT NULL,
    `status` enum('new','in_progress','completed','cancelled','on_hold') DEFAULT 'new',
    `start_date` date DEFAULT NULL,
    `end_date` date DEFAULT NULL,
    `budget` decimal(10,2) DEFAULT NULL,
    `manager_id` bigint(20) unsigned DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. جداول الخوادم والاستضافة
CREATE TABLE `servers` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `hostname` varchar(255) NOT NULL,
    `ip_address` varchar(45) NOT NULL,
    `type` enum('shared','vps','dedicated','cloud') NOT NULL,
    `provider` varchar(100) DEFAULT NULL,
    `cpanel_url` varchar(255) DEFAULT NULL,
    `cpanel_username` varchar(100) DEFAULT NULL,
    `cpanel_password` varchar(255) DEFAULT NULL,
    `status` enum('active','inactive','maintenance') DEFAULT 'active',
    `notes` text DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. جدول المواقع والنطاقات
CREATE TABLE `domains` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `client_id` bigint(20) unsigned NOT NULL,
    `server_id` bigint(20) unsigned DEFAULT NULL,
    `name` varchar(255) NOT NULL,
    `registrar` varchar(100) DEFAULT NULL,
    `registration_date` date DEFAULT NULL,
    `expiry_date` date DEFAULT NULL,
    `auto_renew` tinyint(1) DEFAULT 0,
    `status` enum('active','expired','transferred','pending') DEFAULT 'active',
    `dns_management` tinyint(1) DEFAULT 0,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`server_id`) REFERENCES `servers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إدخال البيانات الأساسية للباقات
INSERT INTO `packages` (`name`, `slug`, `description`, `category`, `price`, `duration`, `features`) VALUES
('باقة الويب الأساسية', 'basic-web', 'تصميم موقع أساسي مع لوحة تحكم', 'web', 499.00, 1, '{"pages": 5, "emails": 5, "storage": "1GB", "support": "basic"}'),
('باقة التسويق الفضية', 'silver-marketing', 'إدارة وسائل التواصل الاجتماعي', 'marketing', 299.00, 1, '{"platforms": 3, "posts": 15, "design": true, "reports": "monthly"}'),
('باقة الاستضافة المتقدمة', 'advanced-hosting', 'استضافة مواقع احترافية', 'hosting', 199.00, 12, '{"space": "10GB", "bandwidth": "100GB", "databases": 10, "domains": 5}');

-- إنشاء الفهارس الإضافية
CREATE INDEX idx_package_category ON packages(category);
CREATE INDEX idx_subscription_dates ON subscriptions(start_date, end_date);
CREATE INDEX idx_client_status ON clients(status);
CREATE INDEX idx_project_status ON projects(status);
CREATE INDEX idx_domain_expiry ON domains(expiry_date);
CREATE INDEX idx_server_status ON servers(status);

-- 7. جداول المهام والمتابعة
CREATE TABLE `tasks` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `project_id` bigint(20) unsigned DEFAULT NULL,
    `title` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `priority` enum('low','medium','high','urgent') DEFAULT 'medium',
    `status` enum('new','in_progress','review','completed','on_hold','cancelled') DEFAULT 'new',
    `assigned_to` bigint(20) unsigned DEFAULT NULL,
    `assigned_by` bigint(20) unsigned DEFAULT NULL,
    `start_date` datetime DEFAULT NULL,
    `due_date` datetime DEFAULT NULL,
    `completed_at` datetime DEFAULT NULL,
    `completion_percentage` int DEFAULT 0,
    `estimated_hours` decimal(5,2) DEFAULT NULL,
    `actual_hours` decimal(5,2) DEFAULT NULL,
    `category` enum('development','design','marketing','content','support','other') DEFAULT NULL,
    `is_billable` tinyint(1) DEFAULT 1,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول المهام الفرعية
CREATE TABLE `subtasks` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `task_id` bigint(20) unsigned NOT NULL,
    `title` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `status` enum('pending','completed') DEFAULT 'pending',
    `completed_at` datetime DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول تعليقات المهام
CREATE TABLE `task_comments` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `task_id` bigint(20) unsigned NOT NULL,
    `user_id` bigint(20) unsigned NOT NULL,
    `comment` text NOT NULL,
    `attachment` varchar(255) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول متابعة وقت العمل
CREATE TABLE `time_logs` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `task_id` bigint(20) unsigned NOT NULL,
    `user_id` bigint(20) unsigned NOT NULL,
    `start_time` datetime NOT NULL,
    `end_time` datetime DEFAULT NULL,
    `duration` int DEFAULT NULL, -- بالدقائق
    `description` text DEFAULT NULL,
    `is_billable` tinyint(1) DEFAULT 1,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول المرفقات
CREATE TABLE `task_attachments` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `task_id` bigint(20) unsigned NOT NULL,
    `user_id` bigint(20) unsigned NOT NULL,
    `file_name` varchar(255) NOT NULL,
    `file_path` varchar(255) NOT NULL,
    `file_size` int NOT NULL,
    `file_type` varchar(100) NOT NULL,
    `description` text DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول متابعي المهمة
CREATE TABLE `task_followers` (
    `task_id` bigint(20) unsigned NOT NULL,
    `user_id` bigint(20) unsigned NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`task_id`, `user_id`),
    FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول التذكيرات
CREATE TABLE `task_reminders` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `task_id` bigint(20) unsigned NOT NULL,
    `user_id` bigint(20) unsigned NOT NULL,
    `reminder_date` datetime NOT NULL,
    `status` enum('pending','sent','cancelled') DEFAULT 'pending',
    `description` text DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول قوالب المهام
CREATE TABLE `task_templates` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `category` enum('development','design','marketing','content','support','other') NOT NULL,
    `estimated_hours` decimal(5,2) DEFAULT NULL,
    `checklist` json DEFAULT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء الفهارس للمهام
CREATE INDEX idx_task_status ON tasks(status);
CREATE INDEX idx_task_priority ON tasks(priority);
CREATE INDEX idx_task_dates ON tasks(start_date, due_date);
CREATE INDEX idx_task_assigned ON tasks(assigned_to);
CREATE INDEX idx_timelogs_dates ON time_logs(start_time, end_time);
CREATE INDEX idx_task_reminders_date ON task_reminders(reminder_date);

-- إدخال بيانات أساسية لقوالب المهام
INSERT INTO `task_templates` (`title`, `category`, `description`, `estimated_hours`, `checklist`) VALUES
('تطوير موقع أساسي', 'development', 'قالب لتطوير موقع ويب أساسي', 40.00, '["تحليل المتطلبات","تصميم الواجهات","برمجة الموقع","اختبار الموقع","رفع الموقع"]'),
('حملة تسويقية', 'marketing', 'قالب لإدارة حملة تسويقية', 20.00, '["تحليل السوق","تحديد الجمهور","تصميم المحتوى","نشر الإعلانات","متابعة النتائج"]'),
('تصميم هوية بصرية', 'design', 'قالب لتصميم هوية بصرية كاملة', 15.00, '["تصميم الشعار","اختيار الألوان","تصميم القرطاسية","دليل الهوية","التسليم النهائي"]');

-- 8. جداول التسويق الرقمي
CREATE TABLE `marketing_campaigns` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `client_id` bigint(20) unsigned NOT NULL,
    `type` enum('social_media','google_ads','seo','email','other') NOT NULL,
    `status` enum('draft','active','paused','completed') DEFAULT 'draft',
    `start_date` date DEFAULT NULL,
    `end_date` date DEFAULT NULL,
    `budget` decimal(10,2) DEFAULT NULL,
    `objectives` text DEFAULT NULL,
    `target_audience` text DEFAULT NULL,
    `platforms` json DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `social_media_posts` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `campaign_id` bigint(20) unsigned DEFAULT NULL,
    `platform` enum('facebook','instagram','twitter','linkedin','tiktok') NOT NULL,
    `content` text NOT NULL,
    `media_urls` json DEFAULT NULL,
    `scheduled_at` datetime DEFAULT NULL,
    `published_at` datetime DEFAULT NULL,
    `status` enum('draft','scheduled','published','failed') DEFAULT 'draft',
    `engagement_stats` json DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`campaign_id`) REFERENCES `marketing_campaigns` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `marketing_analytics` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `campaign_id` bigint(20) unsigned NOT NULL,
    `date` date NOT NULL,
    `platform` varchar(50) NOT NULL,
    `impressions` int DEFAULT 0,
    `clicks` int DEFAULT 0,
    `conversions` int DEFAULT 0,
    `spend` decimal(10,2) DEFAULT 0,
    `engagement_rate` decimal(5,2) DEFAULT 0,
    `metrics` json DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`campaign_id`) REFERENCES `marketing_campaigns` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. جداول التذاكر والدعم الفني
CREATE TABLE `tickets` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `client_id` bigint(20) unsigned NOT NULL,
    `subject` varchar(255) NOT NULL,
    `description` text NOT NULL,
    `priority` enum('low','medium','high','urgent') DEFAULT 'medium',
    `status` enum('new','open','pending','resolved','closed') DEFAULT 'new',
    `type` enum('technical','billing','general','feature_request') NOT NULL,
    `assigned_to` bigint(20) unsigned DEFAULT NULL,
    `resolution` text DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `ticket_responses` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `ticket_id` bigint(20) unsigned NOT NULL,
    `user_id` bigint(20) unsigned NOT NULL,
    `message` text NOT NULL,
    `is_private` tinyint(1) DEFAULT 0,
    `attachments` json DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. جداول المحتوى والتصميم
CREATE TABLE `content_library` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `type` enum('image','video','document','template') NOT NULL,
    `category` varchar(100) DEFAULT NULL,
    `file_path` varchar(255) NOT NULL,
    `thumbnail_path` varchar(255) DEFAULT NULL,
    `metadata` json DEFAULT NULL,
    `tags` json DEFAULT NULL,
    `created_by` bigint(20) unsigned NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `design_projects` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `client_id` bigint(20) unsigned NOT NULL,
    `project_id` bigint(20) unsigned DEFAULT NULL,
    `type` enum('logo','branding','ui_ux','print','social_media') NOT NULL,
    `requirements` text DEFAULT NULL,
    `deliverables` json DEFAULT NULL,
    `status` enum('brief','design','review','completed') DEFAULT 'brief',
    `designer_id` bigint(20) unsigned DEFAULT NULL,
    `deadline` date DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`designer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. جداول التقارير والتحليلات
CREATE TABLE `reports` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `type` enum('marketing','project','financial','performance') NOT NULL,
    `parameters` json DEFAULT NULL,
    `data` json DEFAULT NULL,
    `created_by` bigint(20) unsigned NOT NULL,
    `schedule` varchar(50) DEFAULT NULL,
    `last_generated` datetime DEFAULT NULL,
    `recipients` json DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `analytics_data` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `entity_type` varchar(50) NOT NULL,
    `entity_id` bigint(20) unsigned NOT NULL,
    `metric_name` varchar(100) NOT NULL,
    `metric_value` decimal(15,2) NOT NULL,
    `date` date NOT NULL,
    `metadata` json DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. جداول الفواتير والمدفوعات
CREATE TABLE `invoices` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `client_id` bigint(20) unsigned NOT NULL,
    `project_id` bigint(20) unsigned DEFAULT NULL,
    `invoice_number` varchar(50) NOT NULL UNIQUE,
    `issue_date` date NOT NULL,
    `due_date` date NOT NULL,
    `subtotal` decimal(10,2) NOT NULL,
    `tax_rate` decimal(5,2) DEFAULT 0,
    `tax_amount` decimal(10,2) DEFAULT 0,
    `discount` decimal(10,2) DEFAULT 0,
    `total` decimal(10,2) NOT NULL,
    `status` enum('draft','sent','paid','overdue','cancelled') DEFAULT 'draft',
    `notes` text DEFAULT NULL,
    `terms` text DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `invoice_items` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `invoice_id` bigint(20) unsigned NOT NULL,
    `description` varchar(255) NOT NULL,
    `quantity` decimal(8,2) NOT NULL DEFAULT 1,
    `unit_price` decimal(10,2) NOT NULL,
    `amount` decimal(10,2) NOT NULL,
    `tax_rate` decimal(5,2) DEFAULT 0,
    `created_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء الفهارس الإضافية
CREATE INDEX idx_campaign_status ON marketing_campaigns(status);
CREATE INDEX idx_campaign_dates ON marketing_campaigns(start_date, end_date);
CREATE INDEX idx_posts_schedule ON social_media_posts(scheduled_at);
CREATE INDEX idx_ticket_status ON tickets(status);
CREATE INDEX idx_ticket_priority ON tickets(priority);
CREATE INDEX idx_content_type ON content_library(type);
CREATE INDEX idx_design_deadline ON design_projects(deadline);
CREATE INDEX idx_invoice_dates ON invoices(issue_date, due_date);
CREATE INDEX idx_invoice_status ON invoices(status);

-- 13. جداول نظام الدفع الإلكتروني
CREATE TABLE `payment_gateways` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `code` varchar(50) NOT NULL UNIQUE,
    `provider` varchar(100) NOT NULL,
    `settings` json DEFAULT NULL,
    `credentials` json DEFAULT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `test_mode` tinyint(1) DEFAULT 0,
    `instructions` text DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `payment_transactions` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `gateway_id` bigint(20) unsigned NOT NULL,
    `invoice_id` bigint(20) unsigned DEFAULT NULL,
    `subscription_id` bigint(20) unsigned DEFAULT NULL,
    `transaction_id` varchar(100) NOT NULL,
    `amount` decimal(10,2) NOT NULL,
    `currency` varchar(3) DEFAULT 'IQD',
    `status` enum('pending','completed','failed','cancelled') DEFAULT 'pending',
    `payment_method` varchar(50) DEFAULT NULL,
    `payment_details` json DEFAULT NULL,
    `payer_name` varchar(255) DEFAULT NULL,
    `payer_phone` varchar(20) DEFAULT NULL,
    `receipt_number` varchar(50) DEFAULT NULL,
    `receipt_image` varchar(255) DEFAULT NULL,
    `notes` text DEFAULT NULL,
    `processed_by` bigint(20) unsigned DEFAULT NULL,
    `processed_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`gateway_id`) REFERENCES `payment_gateways` (`id`),
    FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إضافة جدول إيصالات الدفع النقدي
CREATE TABLE `cash_receipts` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `transaction_id` bigint(20) unsigned NOT NULL,
    `receipt_number` varchar(50) NOT NULL UNIQUE,
    `amount` decimal(10,2) NOT NULL,
    `received_from` varchar(255) NOT NULL,
    `received_by` bigint(20) unsigned NOT NULL,
    `payment_date` date NOT NULL,
    `notes` text DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`transaction_id`) REFERENCES `payment_transactions` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إضافة فهارس للمعاملات المالية
CREATE INDEX idx_payment_transaction_status ON payment_transactions(status);
CREATE INDEX idx_payment_dates ON payment_transactions(created_at, processed_at);
CREATE INDEX idx_payment_receipt ON payment_transactions(receipt_number);
CREATE INDEX idx_cash_receipt_number ON cash_receipts(receipt_number);
CREATE INDEX idx_cash_receipt_date ON cash_receipts(payment_date);

-- 14. جداول ربط cPanel
CREATE TABLE `cpanel_servers` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `server_id` bigint(20) unsigned NOT NULL,
    `username` varchar(100) NOT NULL,
    `password` varchar(255) NOT NULL,
    `api_token` varchar(255) DEFAULT NULL,
    `port` int DEFAULT 2083,
    `ssl` tinyint(1) DEFAULT 1,
    `last_sync` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`server_id`) REFERENCES `servers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cpanel_accounts` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `server_id` bigint(20) unsigned NOT NULL,
    `domain_id` bigint(20) unsigned NOT NULL,
    `username` varchar(100) NOT NULL,
    `password` varchar(255) NOT NULL,
    `package` varchar(100) DEFAULT NULL,
    `disk_limit` int DEFAULT NULL,
    `bandwidth_limit` int DEFAULT NULL,
    `status` enum('active','suspended','terminated') DEFAULT 'active',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`server_id`) REFERENCES `servers` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`domain_id`) REFERENCES `domains` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 15. جداول الباقات والخدمات
CREATE TABLE `services` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `slug` varchar(255) NOT NULL UNIQUE,
    `description` text DEFAULT NULL,
    `category` enum('web','hosting','marketing','development','support') NOT NULL,
    `price` decimal(10,2) NOT NULL,
    `recurring` tinyint(1) DEFAULT 0,
    `billing_cycle` enum('one_time','monthly','quarterly','semi_annual','annual') DEFAULT 'one_time',
    `features` json DEFAULT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `service_addons` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `service_id` bigint(20) unsigned NOT NULL,
    `name` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `price` decimal(10,2) NOT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إضافة فهارس جديدة
CREATE INDEX idx_payment_transaction_status ON payment_transactions(status);
CREATE INDEX idx_payment_dates ON payment_transactions(created_at);
CREATE INDEX idx_cpanel_account_status ON cpanel_accounts(status);
CREATE INDEX idx_service_category ON services(category);
CREATE INDEX idx_service_status ON services(is_active);

-- إدخال البيانات الأساسية لبوابات الدفع
INSERT INTO `payment_gateways` (`name`, `code`, `provider`, `settings`, `instructions`, `is_active`) VALUES
('زين كاش', 'zain_cash', 'zain_cash', 
'{
    "merchant_id": "61a75c42dff23f6a9e97a97e",
    "secret": "$2y$10$BeNhCt4fpq4sLgE9ZJf1suGN/87TtvJYk0TMxYjwMvCaDGAzLidZ6",
    "phone": "9647800533950",
    "environment": "production",
    "language": "ar",
    "currency": "IQD"
}', 
'قم بتحويل المبلغ عبر تطبيق زين كاش على الرقم 9647800533950', 
1),

('تبادل', 'tabadul', 'tabadul', 
'{
    "test": {
        "merchant_username": "phoenix_api",
        "merchant_password": "Phoenix@1234",
        "api_url": "https://epgtest.tabadul.iq:9444/epg/rest/",
        "gui_username": "phoenix_merch",
        "gui_password": "Phoenix@1234",
        "gui_url": "https://epgtest.tabadul.iq:9444/epg_gui/"
    },
    "production": {
        "api_url": "https://epg.tabadul.iq/epg/rest/",
        "merchant_username": "",
        "merchant_password": ""
    },
    "test_card": {
        "number": "4222450000980046",
        "expiry": "07/25",
        "cvv": "160"
    },
    "currency": "368",
    "language": "ar"
}',
'يمكنك الدفع باستخدام بطاقة الدفع الإلكتروني عبر بوابة تبادل',
1),

('محفظة qi', 'qi_wallet', 'qi', '{"merchant_id": "YOUR_MERCHANT_ID"}', 'قم بالدفع عبر محفظة qi.iq', 1),
('محفظة fib', 'fib_wallet', 'fib', '{"merchant_id": "YOUR_MERCHANT_ID"}', 'قم بالدفع عبر محفظة fib.iq', 1),
('دفع نقدي', 'cash', 'manual', NULL, 'يمكنك الدفع نقداً في مقر الشركة', 1);

-- إدخال البيانات الأساسية للخدمات
INSERT INTO `services` (`name`, `slug`, `description`, `category`, `price`, `recurring`, `billing_cycle`, `features`) VALUES
('تصميم موقع إلكتروني', 'web-design', 'تصميم موقع احترافي متجاوب', 'web', 2999.00, 0, '{"pages": 10, "responsive": true, "seo": true}'),
('استضافة متقدمة', 'advanced-hosting', 'استضافة مواقع احترافية', 'hosting', 299.00, 1, 'annual', '{"space": "20GB", "bandwidth": "200GB", "ssl": true}'),
('باقة التسويق الشاملة', 'marketing-pro', 'خدمات تسويق رقمي شاملة', 'marketing', 1999.00, 1, 'monthly', '{"social_media": true, "seo": true, "ads": true}'),
('دعم فني مميز', 'premium-support', 'دعم فني على مدار الساعة', 'support', 499.00, 1, 'monthly', '{"24_7": true, "response_time": "2h", "priority": true}');

-- إدخال البيانات الأساسية للإضافات
INSERT INTO `service_addons` (`service_id`, `name`, `description`, `price`) VALUES
(1, 'صفحات إضافية', 'إضافة 5 صفحات إضافية للموقع', 499.00),
(1, 'متجر إلكتروني', 'إضافة متجر إلكتروني للموقع', 1499.00),
(2, 'مساحة إضافية', 'إضافة 10GB مساحة تخزين', 99.00),
(3, 'حملة إعلانية', 'حملة إعلانية مدفوعة', 999.00);

-- تحديث جدول المعاملات ليشمل حقول زين كاش
ALTER TABLE `payment_transactions` 
ADD COLUMN `zain_cash_phone` varchar(15) DEFAULT NULL AFTER `payer_phone`,
ADD COLUMN `zain_cash_otp` varchar(10) DEFAULT NULL AFTER `zain_cash_phone`,
ADD COLUMN `zain_cash_transaction_id` varchar(100) DEFAULT NULL AFTER `zain_cash_otp`;

-- إضافة فهرس لرقم هاتف زين كاش
CREATE INDEX idx_zain_cash_phone ON payment_transactions(zain_cash_phone);

-- إضافة حقون خاصة بمعاملات تبادل
ALTER TABLE `payment_transactions` 
ADD COLUMN `tabadul_order_id` varchar(100) DEFAULT NULL AFTER `zain_cash_transaction_id`,
ADD COLUMN `tabadul_order_number` varchar(100) DEFAULT NULL AFTER `tabadul_order_id`,
ADD COLUMN `tabadul_status` varchar(50) DEFAULT NULL AFTER `tabadul_order_number`;

-- إضافة فهرس لمعاملات تبادل
CREATE INDEX idx_tabadul_order ON payment_transactions(tabadul_order_id);