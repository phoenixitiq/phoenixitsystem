-- جداول الباقات والخدمات
CREATE TABLE `package_categories` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `slug` varchar(100) NOT NULL UNIQUE,
    `description` text DEFAULT NULL,
    `icon` varchar(50) DEFAULT NULL,
    `order` int DEFAULT 0,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `packages` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `category_id` bigint(20) unsigned NOT NULL,
    `name` varchar(100) NOT NULL,
    `slug` varchar(100) NOT NULL UNIQUE,
    `description` text DEFAULT NULL,
    `price` decimal(10,2) NOT NULL,
    `billing_cycle` enum('monthly','quarterly','semi_annual','annual') NOT NULL,
    `setup_fee` decimal(10,2) DEFAULT 0.00,
    `features` json NOT NULL,
    `specifications` json DEFAULT NULL,
    `is_featured` tinyint(1) DEFAULT 0,
    `is_active` tinyint(1) DEFAULT 1,
    `max_clients` int DEFAULT NULL,
    `sort_order` int DEFAULT 0,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`category_id`) REFERENCES `package_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `package_addons` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `package_id` bigint(20) unsigned NOT NULL,
    `name` varchar(100) NOT NULL,
    `description` text DEFAULT NULL,
    `price` decimal(10,2) NOT NULL,
    `billing_cycle` enum('one_time','monthly','quarterly','semi_annual','annual') NOT NULL,
    `features` json DEFAULT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إدخال البيانات الأساسية لفئات الباقات
INSERT INTO `package_categories` (`name`, `slug`, `description`, `icon`, `order`) VALUES
('استضافة المواقع', 'web-hosting', 'باقات استضافة المواقع الإلكترونية', 'fas fa-server', 1),
('تصميم المواقع', 'web-design', 'باقات تصميم وتطوير المواقع', 'fas fa-palette', 2),
('خدمات التسويق', 'marketing', 'باقات التسويق الرقمي', 'fas fa-bullhorn', 3),
('الدعم الفني', 'support', 'باقات خدمات الدعم الفني', 'fas fa-headset', 4);

-- إدخال البيانات الأساسية للباقات
INSERT INTO `packages` 
(`category_id`, `name`, `slug`, `description`, `price`, `billing_cycle`, `features`, `specifications`, `is_featured`) VALUES
-- باقات الاستضافة
(1, 'الباقة الأساسية', 'basic-hosting', 'مناسبة للمواقع الصغيرة', 50.00, 'monthly',
'{
    "مساحة تخزين": "5 GB",
    "نقل بيانات شهري": "50 GB",
    "قواعد بيانات": "5",
    "حسابات بريد": "10",
    "نطاقات فرعية": "5",
    "شهادة SSL": "مجاناً",
    "نسخ احتياطي": "أسبوعي",
    "لوحة تحكم": "cPanel"
}',
'{
    "cpu": "1 Core",
    "ram": "1 GB",
    "backup_retention": "7 days",
    "technology": ["PHP", "MySQL", "Apache"]
}', 0),

(1, 'الباقة المتقدمة', 'advanced-hosting', 'مناسبة للشركات المتوسطة', 100.00, 'monthly',
'{
    "مساحة تخزين": "20 GB",
    "نقل بيانات شهري": "200 GB",
    "قواعد بيانات": "unlimited",
    "حسابات بريد": "unlimited",
    "نطاقات فرعية": "unlimited",
    "شهادة SSL": "مجاناً",
    "نسخ احتياطي": "يومي",
    "لوحة تحكم": "cPanel",
    "IP مخصص": "نعم"
}',
'{
    "cpu": "2 Cores",
    "ram": "2 GB",
    "backup_retention": "14 days",
    "technology": ["PHP", "MySQL", "Apache", "Node.js"]
}', 1),

-- باقات تصميم المواقع
(2, 'موقع تعريفي', 'business-website', 'موقع احترافي للشركات', 2999.00, 'monthly',
'{
    "عدد الصفحات": "حتى 10 صفحات",
    "تصميم متجاوب": "نعم",
    "تحسين محركات البحث": "أساسي",
    "ربط وسائل التواصل": "نعم",
    "نموذج اتصال": "نعم",
    "خريطة جوجل": "نعم",
    "إحصائيات الزوار": "نعم",
    "دعم فني": "3 أشهر"
}',
'{
    "technology": ["HTML5", "CSS3", "JavaScript", "PHP"],
    "includes_hosting": true,
    "includes_domain": true,
    "delivery_time": "14 days"
}', 1);

-- إضافة الإضافات للباقات
INSERT INTO `package_addons` 
(`package_id`, `name`, `description`, `price`, `billing_cycle`, `features`) VALUES
(1, 'مساحة إضافية', '5GB مساحة تخزين إضافية', 20.00, 'monthly', 
'{"disk_space": "5GB"}'),

(1, 'نطاق إضافي', 'إضافة نطاق إضافي للاستضافة', 30.00, 'monthly',
'{"addon_domains": 1}'),

(2, 'IP مخصص', 'عنوان IP مخصص', 50.00, 'monthly',
'{"dedicated_ip": 1}'),

(3, 'صفحات إضافية', '5 صفحات إضافية للموقع', 999.00, 'one_time',
'{"extra_pages": 5}'); 