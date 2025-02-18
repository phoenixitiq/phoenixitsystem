-- إنشاء جدول الإعدادات
CREATE TABLE `settings` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `key` varchar(50) NOT NULL,
    `value` text,
    `group_name` varchar(50) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `settings_key_unique` (`key`),
    KEY `settings_group_name_index` (`group_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إدخال الإعدادات الافتراضية
INSERT INTO `settings` (`key`, `value`, `group_name`, `created_at`, `updated_at`) VALUES
('company_name', 'Phoenix IT', 'general', NOW(), NOW()),
('company_email', 'info@phoenixitiq.com', 'general', NOW(), NOW()),
('company_phone', '+964 000 0000', 'general', NOW(), NOW()),
('company_address', 'Iraq, Baghdad', 'general', NOW(), NOW()),
('system_version', '1.0.0', 'system', NOW(), NOW()),
('backup_enabled', '1', 'system', NOW(), NOW()),
('backup_frequency', 'daily', 'system', NOW(), NOW()),
('mail_from_name', 'Phoenix IT', 'mail', NOW(), NOW()),
('mail_from_address', 'no-reply@phoenixitiq.com', 'mail', NOW(), NOW()); 