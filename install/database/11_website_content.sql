-- جداول محتوى الموقع
CREATE TABLE `pages` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `slug` varchar(100) NOT NULL UNIQUE,
    `title` json NOT NULL, -- {"ar": "العنوان", "en": "Title"}
    `content` json NOT NULL,
    `meta_description` json DEFAULT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `team_members` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` json NOT NULL, -- {"ar": "الاسم", "en": "Name"}
    `role` json NOT NULL,
    `department` varchar(50) NOT NULL,
    `bio` json NOT NULL,
    `image` varchar(255) DEFAULT NULL,
    `social_links` json DEFAULT NULL,
    `sort_order` int DEFAULT 0,
    `is_active` tinyint(1) DEFAULT 1,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `testimonials` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `client_name` json NOT NULL,
    `position` json DEFAULT NULL,
    `company` json DEFAULT NULL,
    `content` json NOT NULL,
    `image` varchar(255) DEFAULT NULL,
    `rating` tinyint DEFAULT 5,
    `is_featured` tinyint(1) DEFAULT 0,
    `is_active` tinyint(1) DEFAULT 1,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `contact_messages` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `phone` varchar(20) DEFAULT NULL,
    `message` text NOT NULL,
    `status` enum('new','read','replied','archived') DEFAULT 'new',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إدخال البيانات الأولية للصفحات
INSERT INTO `pages` (`slug`, `title`, `content`, `meta_description`, `is_active`) VALUES
('about', 
    '{"ar": "من نحن", "en": "About Us"}',
    '{"ar": {"vision": "رؤيتنا", "mission": "رسالتنا", "values": "قيمنا"}, "en": {"vision": "Our Vision", "mission": "Our Mission", "values": "Our Values"}}',
    '{"ar": "تعرف على شركة فينيكس للحلول التقنية", "en": "Learn about Phoenix IT Solutions"}',
    1
),
('services',
    '{"ar": "خدماتنا", "en": "Our Services"}',
    '{"ar": {"title": "خدماتنا", "description": "نقدم مجموعة شاملة من الخدمات"}, "en": {"title": "Our Services", "description": "We provide comprehensive services"}}',
    '{"ar": "خدمات فينيكس للحلول التقنية", "en": "Phoenix IT Solutions Services"}',
    1
),
('privacy-policy', 
    '{"ar": "سياسة الخصوصية", "en": "Privacy Policy"}',
    '{"ar": {"content": "محتوى سياسة الخصوصية"}, "en": {"content": "Privacy Policy Content"}}',
    '{"ar": "سياسة الخصوصية - فينيكس للحلول التقنية", "en": "Privacy Policy - Phoenix IT Solutions"}',
    1
),
('terms', 
    '{"ar": "الشروط والأحكام", "en": "Terms & Conditions"}',
    '{"ar": {"content": "محتوى الشروط والأحكام"}, "en": {"content": "Terms & Conditions Content"}}',
    '{"ar": "الشروط والأحكام - فينيكس للحلول التقنية", "en": "Terms & Conditions - Phoenix IT Solutions"}',
    1
),
('faq', 
    '{"ar": "الأسئلة الشائعة", "en": "FAQ"}',
    '{"ar": {"content": "الأسئلة الشائعة وإجاباتها"}, "en": {"content": "Frequently Asked Questions and Answers"}}',
    '{"ar": "الأسئلة الشائعة - فينيكس للحلول التقنية", "en": "FAQ - Phoenix IT Solutions"}',
    1
);

-- إدخال البيانات الأولية للفريق
INSERT INTO `team_members` (`name`, `role`, `department`, `bio`, `social_links`, `is_active`) VALUES
(
    '{"ar": "همام الهلال", "en": "Humam Al-Hilal"}',
    '{"ar": "المؤسس والرئيس التنفيذي", "en": "Founder & CEO"}',
    'management',
    '{"ar": "خبرة واسعة في مجال تقنية المعلومات والتسويق الرقمي", "en": "Extensive experience in IT and digital marketing"}',
    '{"linkedin": "humam-alhilal", "twitter": "humamalhilal"}',
    1
),
(
    '{"ar": "مصطفى الفائز", "en": "Mustafa Al-Faiz"}',
    '{"ar": "مدير التقنية", "en": "CTO"}',
    'tech',
    '{"ar": "خبير في تطوير البرمجيات وإدارة المشاريع التقنية", "en": "Expert in software development and technical project management"}',
    '{"linkedin": "mustafa-faiz", "github": "mustafafaiz"}',
    1
);

-- إدخال البيانات الأولية للتقييمات
INSERT INTO `testimonials` (`client_name`, `position`, `company`, `content`, `rating`, `is_featured`) VALUES
(
    '{"ar": "أحمد العبيدي", "en": "Ahmed Al-Obaidi"}',
    '{"ar": "المدير التنفيذي", "en": "CEO"}',
    '{"ar": "شركة النور", "en": "Al-Noor Company"}',
    '{"ar": "خدمة ممتازة وفريق احترافي", "en": "Excellent service and professional team"}',
    5,
    1
),
(
    '{"ar": "سارة الحسني", "en": "Sara Al-Hassani"}',
    '{"ar": "مديرة التسويق", "en": "Marketing Manager"}',
    '{"ar": "مؤسسة الأمل", "en": "Al-Amal Foundation"}',
    '{"ar": "تجربة رائعة في التعامل مع فريق فينيكس", "en": "Great experience working with Phoenix team"}',
    5,
    1
);

-- إنشاء الفهارس
CREATE INDEX idx_page_slug ON pages(slug);
CREATE INDEX idx_team_department ON team_members(department);
CREATE INDEX idx_testimonial_featured ON testimonials(is_featured);
CREATE INDEX idx_contact_status ON contact_messages(status); 