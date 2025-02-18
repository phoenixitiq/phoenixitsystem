CREATE TABLE `page_sections` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `page_identifier` varchar(50) NOT NULL, -- home, about, services, etc.
    `section_identifier` varchar(50) NOT NULL, -- hero, features, services, etc.
    `title` json NOT NULL,
    `subtitle` json DEFAULT NULL,
    `description` json DEFAULT NULL,
    `content` json DEFAULT NULL, -- For flexible content like features, stats, etc.
    `image` varchar(255) DEFAULT NULL,
    `background_image` varchar(255) DEFAULT NULL,
    `sort_order` int DEFAULT 0,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `page_section_unique` (`page_identifier`, `section_identifier`),
    KEY `page_sections_sort_order_index` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إدخال البيانات الأولية للصفحة الرئيسية
INSERT INTO `page_sections` (`page_identifier`, `section_identifier`, `title`, `subtitle`, `description`, `content`, `sort_order`) VALUES
('home', 'hero',
    '{"ar": {"line1": "نحول أفكارك إلى", "line2": "نجاحات رقمية"}, "en": {"line1": "Transforming Your Ideas Into", "line2": "Digital Success"}}',
    NULL,
    '{"ar": "منذ 2018، نجمع بين الخبرة البريطانية والفهم العميق للسوق المحلي. نحول أفكارك إلى نجاحات رقمية من خلال حلول تقنية مبتكرة وفعالة.", "en": "Since 2018, we\'ve combined British expertise with deep local market understanding. We transform your ideas into digital success through innovative and effective technical solutions."}',
    NULL,
    1
),
('home', 'features',
    '{"ar": "مميزاتنا", "en": "Our Features"}',
    NULL,
    NULL,
    '{"ar": [
        {"title": "خبرة عالمية", "description": "نجمع بين الخبرة البريطانية والفهم العميق للسوق المحلي"},
        {"title": "فريق متخصص", "description": "فريق من المتخصصين في مجالات التسويق الرقمي والتقنية"},
        {"title": "حلول مبتكرة", "description": "نقدم حلولاً مبتكرة تناسب احتياجات عملائنا"}
    ], "en": [
        {"title": "Global Expertise", "description": "Combining British expertise with deep local market understanding"},
        {"title": "Specialized Team", "description": "Team of specialists in digital marketing and technology"},
        {"title": "Innovative Solutions", "description": "Providing innovative solutions tailored to our clients\' needs"}
    ]}',
    2
); 