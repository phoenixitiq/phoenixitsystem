-- إنشاء جدول الإعدادات
CREATE TABLE `settings` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `key` varchar(50) NOT NULL,
    `value` text,
    `group_name` varchar(50) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء الفهارس
CREATE INDEX idx_settings_key ON settings(`key`);
CREATE INDEX idx_settings_group ON settings(group_name); 