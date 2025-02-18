CREATE TABLE `employees` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` json NOT NULL, -- {"ar": "الاسم", "en": "Name"}
    `role` json NOT NULL, -- {"ar": "المنصب", "en": "Role"}
    `department` varchar(50) NOT NULL,
    `bio` json NOT NULL,
    `image` varchar(255) DEFAULT NULL,
    `email` varchar(255) DEFAULT NULL,
    `social_links` json DEFAULT NULL, -- {"linkedin": "", "twitter": "", "instagram": "", "behance": ""}
    `sort_order` int DEFAULT 0,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `employees_department_index` (`department`),
    KEY `employees_sort_order_index` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إدخال البيانات الأولية
INSERT INTO `employees` (`name`, `role`, `department`, `bio`, `email`, `social_links`, `sort_order`, `is_active`) VALUES
(
    '{"ar": "همام الهلال", "en": "Humam Al-Hilal"}',
    '{"ar": "المؤسس والرئيس التنفيذي", "en": "Founder & CEO"}',
    'management',
    '{"ar": "قائد ملهم يجمع بين الخبرة العالمية والرؤية المحلية", "en": "An inspiring leader combining global expertise with local vision"}',
    'humam@phoenixitiq.com',
    '{"linkedin": "humam-alhilal", "twitter": "humamalhilal"}',
    1,
    1
),
(
    '{"ar": "مصطفى الفائز", "en": "Mustafa Al-Faiz"}',
    '{"ar": "مدير التقنية", "en": "CTO"}',
    'tech',
    '{"ar": "خبير تقني متمرس يقود التطور التكنولوجي في الشركة", "en": "A seasoned technical expert leading the company\'s technological development"}',
    'mustafa@phoenixitiq.com',
    '{"linkedin": "mustafa-faiz", "github": "mustafafaiz"}',
    2,
    1
); 