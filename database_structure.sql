-- إنشاء جداول قاعدة البيانات
CREATE DATABASE IF NOT EXISTS your_database_name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE your_database_name;

-- جدول المستخدمين
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- جدول الأدوار
CREATE TABLE roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    name_ar VARCHAR(50) NOT NULL,
    slug VARCHAR(50) UNIQUE NOT NULL,
    description TEXT,
    level INT DEFAULT 0, -- مستوى الصلاحية (0: عميل، 1: موظف، 2: مدير، 3: سوبر أدمن)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- إدخال الأدوار الأساسية
INSERT INTO roles (name, name_ar, slug, level, description) VALUES
('Super Admin', 'مدير النظام', 'super-admin', 3, 'Full system access'),
('Admin', 'مدير', 'admin', 2, 'Administrative access'),
('Employee', 'موظف', 'employee', 1, 'Employee access'),
('Agent', 'وكيل', 'agent', 1, 'Agent access'),
('Client', 'عميل', 'client', 0, 'Client access');

-- جدول الصلاحيات التفصيلية
CREATE TABLE permissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    name_ar VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    group_name VARCHAR(50),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- جدول ربط الأدوار بالصلاحيات
CREATE TABLE role_permissions (
    role_id BIGINT UNSIGNED,
    permission_id BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- جدول ربط المستخدمين بالأدوار
CREATE TABLE user_roles (
    user_id BIGINT UNSIGNED,
    role_id BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- تحديث جدول المستخدمين
ALTER TABLE users
ADD COLUMN status ENUM('active', 'inactive', 'suspended') DEFAULT 'active' AFTER role,
ADD COLUMN last_login TIMESTAMP NULL,
ADD COLUMN login_ip VARCHAR(45),
ADD COLUMN settings JSON,
ADD COLUMN preferences JSON;

-- جدول سجل النشاطات
CREATE TABLE activity_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED,
    action VARCHAR(100) NOT NULL,
    model_type VARCHAR(100),
    model_id BIGINT UNSIGNED,
    description TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- جدول الإحصائيات
CREATE TABLE statistics (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    key_name VARCHAR(100) NOT NULL,
    value JSON,
    period VARCHAR(20), -- daily, weekly, monthly, yearly
    date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_stat` (`key_name`, `period`, `date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- جدول الصلاحيات
CREATE TABLE permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- جدول ربط المستخدمين بالصلاحيات
CREATE TABLE user_permissions (
    user_id INT,
    permission_id INT,
    PRIMARY KEY (user_id, permission_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- جدول الإعدادات
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    key_name VARCHAR(255) UNIQUE NOT NULL,
    value TEXT,
    type ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE packages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'USD',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    package_type ENUM('social_media', 'web_development', 'marketing') NOT NULL,
    billing_cycle ENUM('monthly', 'quarterly', 'semi_annual', 'annual') NOT NULL,
    min_duration INT NOT NULL DEFAULT 1, -- بالشهور
    max_duration INT NOT NULL DEFAULT 12, -- بالشهور
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    posts_per_month INT DEFAULT 0,
    platforms JSON,
    includes_strategy BOOLEAN DEFAULT FALSE,
    includes_monitoring BOOLEAN DEFAULT FALSE,
    response_time VARCHAR(50),
    reports_frequency VARCHAR(50)
);

-- إنشاء جداول النظام
CREATE TABLE services (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    price DECIMAL(10,2),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE projects (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    client_id BIGINT UNSIGNED,
    status VARCHAR(50) DEFAULT 'pending',
    start_date DATE,
    end_date DATE,
    budget DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES users(id)
);

CREATE TABLE employees (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED,
    position VARCHAR(255) NOT NULL,
    department VARCHAR(255),
    bio TEXT,
    skills JSON,
    image VARCHAR(255),
    social_links JSON,
    is_team_member BOOLEAN DEFAULT FALSE,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- جدول الوظائف الشاغرة
CREATE TABLE job_positions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    requirements TEXT,
    department VARCHAR(255),
    status ENUM('open', 'closed') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- جدول طلبات التوظيف
CREATE TABLE job_applications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    position_id BIGINT UNSIGNED,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    cv_path VARCHAR(255),
    status ENUM('pending', 'reviewed', 'accepted', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (position_id) REFERENCES job_positions(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- جدول مناطق الوكلاء
CREATE TABLE agent_territories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    agent_id BIGINT UNSIGNED,
    territory_name VARCHAR(255),
    coverage_area TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    FOREIGN KEY (agent_id) REFERENCES agents(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- جدول تفاصيل الباقات
CREATE TABLE package_features (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    package_id BIGINT UNSIGNED,
    feature_name VARCHAR(255),
    feature_value TEXT,
    is_highlighted BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (package_id) REFERENCES packages(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- جدول الإشعارات
CREATE TABLE notifications (
    id CHAR(36) PRIMARY KEY,
    type VARCHAR(255) NOT NULL,
    notifiable_type VARCHAR(255) NOT NULL,
    notifiable_id BIGINT UNSIGNED NOT NULL,
    data TEXT NOT NULL,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`, `notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- جدول المرفقات
CREATE TABLE attachments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    attachable_type VARCHAR(255) NOT NULL,
    attachable_id BIGINT UNSIGNED NOT NULL,
    filename VARCHAR(255) NOT NULL,
    original_filename VARCHAR(255) NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    size INT UNSIGNED NOT NULL,
    path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `attachments_attachable_type_attachable_id_index` (`attachable_type`, `attachable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- جدول الاشتراكات
CREATE TABLE subscriptions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    package_id BIGINT UNSIGNED NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    duration INT NOT NULL, -- مدة العقد بالشهور
    billing_cycle ENUM('monthly', 'full_contract') NOT NULL,
    status ENUM('active', 'pending', 'expired', 'cancelled') DEFAULT 'pending',
    total_amount DECIMAL(10,2) NOT NULL,
    monthly_amount DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (package_id) REFERENCES packages(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- جدول المدفوعات
CREATE TABLE payments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    subscription_id BIGINT UNSIGNED NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_date DATE NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    transaction_id VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (subscription_id) REFERENCES subscriptions(id)
);

-- جدول إعدادات الشركة
CREATE TABLE company_settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    key_name VARCHAR(255) NOT NULL UNIQUE,
    value TEXT,
    group_name VARCHAR(50) DEFAULT 'general',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- إدخال البيانات الأساسية للشركة
INSERT INTO company_settings (key_name, value, group_name) VALUES
('company_name', 'شركة فينكس للحلول الرقمية', 'general'),
('company_slogan', 'نحول أعمالك إلى نجاحات رقمية', 'general'),
('company_description', 'نقدم حلول رقمية متكاملة لتطوير وتنمية أعمالك', 'general'),
('company_email', 'info@phoenixsys.com', 'contact'),
('company_phone', '+966500000000', 'contact'),
('company_address', 'الرياض، المملكة العربية السعودية', 'contact'),
('social_facebook', 'https://facebook.com/phoenixsys', 'social'),
('social_twitter', 'https://twitter.com/phoenixsys', 'social'),
('social_instagram', 'https://instagram.com/phoenixsys', 'social'),
('social_linkedin', 'https://linkedin.com/company/phoenixsys', 'social'),
('working_hours', '9:00 صباحاً - 6:00 مساءً', 'general'),
('working_days', 'الأحد - الخميس', 'general');

-- تحديث بيانات الشركة لتتوافق مع التصميم الجديد
UPDATE company_settings SET value = 'العنقاء لتكنولوجيا المعلومات والتسويق الإلكتروني' WHERE key_name = 'company_name';
UPDATE company_settings SET value = 'نحول أعمالك إلى نجاحات رقمية' WHERE key_name = 'company_slogan';

-- إضافة المزيد من البيانات
INSERT INTO company_settings (key_name, value, group_name) VALUES
-- معلومات الفروع
('branch_uk_address', 'London, United Kingdom', 'branches'),
('branch_uk_hours', 'Monday - Friday: 9:00 - 17:00', 'branches'),
('branch_iraq_address', 'Iraq - Anbar - Ramadi', 'branches'),
('branch_iraq_hours', 'Saturday - Thursday: 12:00 - 21:00', 'branches'),

-- معلومات التواصل الاجتماعي للفروع
('social_uk_facebook', 'phoenixuk', 'social'),
('social_uk_twitter', 'phoenixuk', 'social'),
('social_uk_linkedin', 'phoenix-uk', 'social'),
('social_iraq_facebook', 'phoenixiraq', 'social'),
('social_iraq_twitter', 'phoenixiraq', 'social'),
('social_iraq_instagram', 'phoenixiraq', 'social'),
('social_iraq_linkedin', 'phoenix-iraq', 'social'),
('social_iraq_tiktok', 'phoenixiraq', 'social'),

-- معلومات الفريق
('team_stats_clients', '150+', 'stats'),
('team_stats_projects', '300+', 'stats'),
('team_stats_experience', '10+', 'stats'),
('team_stats_team', '25+', 'stats'),

-- معلومات الخدمات
('services_social_media_count', '500+', 'stats'),
('services_websites_count', '200+', 'stats'),
('services_apps_count', '50+', 'stats'),
('services_designs_count', '1000+', 'stats');

-- إضافة المزيد من بيانات الاتصال السعودية
INSERT INTO company_settings (key_name, value, group_name) VALUES
('company_phone_ksa', '+966 55 555 5555', 'contact'),
('company_whatsapp', '+966 55 555 5555', 'contact'),
('company_address_ar', 'طريق الملك فهد، حي العليا، الرياض 12214، المملكة العربية السعودية', 'contact'),
('company_address_en', 'King Fahd Road, Al Olaya, Riyadh 12214, Saudi Arabia', 'contact'),
('company_cr', '1010XXXXXX', 'legal'), -- السجل التجاري
('company_vat', '302XXXXXX', 'legal'), -- الرقم الضريبي
('company_maroof', 'https://maroof.sa/XXXXX', 'legal'), -- رابط معروف
('company_location_lat', '24.7136', 'contact'),
('company_location_lng', '46.6753', 'contact'),
('company_map_url', 'https://goo.gl/maps/XXXXX', 'contact');

-- إضافة معلومات الدعم
INSERT INTO company_settings (key_name, value, group_name) VALUES
('support_hours', '9:00 صباحاً - 10:00 مساءً', 'support'),
('support_days', 'السبت - الخميس', 'support'),
('technical_support_number', '+966 55 555 5556', 'support'),
('sales_number', '+966 55 555 5557', 'support');

-- إضافة وسائل الدفع المتاحة
INSERT INTO company_settings (key_name, value, group_name) VALUES
('payment_methods', 'مدى,فيزا,ماستركارد,آبل باي,STCPay', 'payment'),
('bank_account_name', 'شركة فينكس للحلول الرقمية', 'payment'),
('bank_name', 'البنك الأهلي السعودي', 'payment'),
('bank_iban', 'SA00 0000 0000 0000 0000 0000', 'payment');

-- جدول المحتوى الديناميكي
CREATE TABLE content_blocks (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    key_name VARCHAR(255) NOT NULL UNIQUE,
    title_ar TEXT,
    title_en TEXT,
    content_ar TEXT,
    content_en TEXT,
    page VARCHAR(100),
    section VARCHAR(100),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- جدول التقييمات والمراجعات
CREATE TABLE reviews (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED,
    name VARCHAR(255),
    company VARCHAR(255),
    position VARCHAR(255),
    rating INT,
    review_text TEXT,
    is_featured BOOLEAN DEFAULT FALSE,
    is_approved BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- إضافة المزيد من البيانات للمحتوى
INSERT INTO content_blocks (key_name, title_ar, title_en, content_ar, content_en, page, section) VALUES
('home_hero', 'نحول أعمالك إلى نجاحات رقمية', 'Transforming Your Ideas Into Digital Success', 
'منذ 2018، نجمع بين الخبرة البريطانية والفهم العميق للسوق المحلي', 
'Since 2018, we\'ve combined British expertise with deep local market understanding',
'home', 'hero'),

('about_vision', 'رؤيتنا', 'Our Vision',
'نسعى لأن نكون الشريك الأول في التحول الرقمي',
'We strive to be the leading partner in digital transformation',
'about', 'vision'),

('about_mission', 'رسالتنا', 'Our Mission',
'تقديم حلول رقمية مبتكرة تساهم في نمو أعمال عملائنا',
'Providing innovative digital solutions that contribute to our clients\' business growth',
'about', 'mission');

-- إضافة تقييمات العملاء
INSERT INTO reviews (name, company, position, rating, review_text, is_featured, is_approved) VALUES
('محمد العبدالله', 'شركة التقنية المتقدمة', 'المدير التنفيذي', 5, 
'تجربة رائعة مع فريق محترف. النتائج كانت أفضل من المتوقع', TRUE, TRUE),

('Sarah Johnson', 'Tech Solutions Ltd', 'Marketing Director', 5,
'Exceptional service and outstanding results. Highly recommended!', TRUE, TRUE);

-- جدول المشاريع المنجزة
CREATE TABLE completed_projects (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title_ar VARCHAR(255) NOT NULL,
    title_en VARCHAR(255) NOT NULL,
    description_ar TEXT,
    description_en TEXT,
    client_name VARCHAR(255),
    completion_date DATE,
    category VARCHAR(100),
    image_path VARCHAR(255),
    link VARCHAR(255),
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- جدول الخدمات المقدمة
CREATE TABLE service_features (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    service_id BIGINT UNSIGNED,
    title_ar VARCHAR(255),
    title_en VARCHAR(255),
    description_ar TEXT,
    description_en TEXT,
    icon VARCHAR(50),
    is_featured BOOLEAN DEFAULT FALSE,
    display_order INT DEFAULT 0,
    FOREIGN KEY (service_id) REFERENCES services(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- جدول الفروع
CREATE TABLE branches (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name_ar VARCHAR(255),
    name_en VARCHAR(255),
    address_ar TEXT,
    address_en TEXT,
    phone VARCHAR(50),
    email VARCHAR(255),
    working_hours_ar VARCHAR(255),
    working_hours_en VARCHAR(255),
    location_lat DECIMAL(10,8),
    location_lng DECIMAL(11,8),
    is_main BOOLEAN DEFAULT FALSE,
    country_code VARCHAR(2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- إضافة بيانات الفروع
INSERT INTO branches (name_ar, name_en, address_ar, address_en, phone, email, working_hours_ar, working_hours_en, country_code, is_main) VALUES
('المقر الرئيسي - المملكة المتحدة', 'Head Office - United Kingdom', 
'Wenlock Road, London, N1 7GU', 'Wenlock Road, London, N1 7GU',
'+44 7488 893815', 'info@phoenixituk.com',
'الإثنين - الجمعة: ٩:٠٠ - ١٧:٠٠', 'Monday - Friday: 9:00 - 17:00',
'GB', TRUE),

('فرع العراق', 'Iraq Branch',
'العراق - الأنبار - الرمادي', 'Iraq - Anbar - Ramadi',
'+964 780 053 3950', 'info@phoenixitiq.com',
'السبت - الخميس: ١٢:٠٠ - ٢١:٠٠', 'Saturday - Thursday: 12:00 - 21:00',
'IQ', FALSE);

-- تحديث جدول الموظفين
ALTER TABLE employees ADD COLUMN work_type ENUM('remote', 'office') DEFAULT 'office';
ALTER TABLE employees ADD COLUMN work_schedule JSON; -- لتخزين جدول العمل المرن
ALTER TABLE employees ADD COLUMN timezone VARCHAR(50) DEFAULT 'Asia/Baghdad';

-- جدول تتبع العمل عن بعد
CREATE TABLE remote_work_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    employee_id BIGINT UNSIGNED,
    date DATE,
    login_time TIMESTAMP,
    logout_time TIMESTAMP,
    total_active_time INT, -- بالدقائق
    tasks_completed JSON,
    screenshots JSON, -- لقطات شاشة دورية (اختياري)
    activity_level INT, -- مستوى النشاط (0-100)
    status ENUM('active', 'idle', 'offline'),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES users(id)
);

-- جدول المهام اليومية
CREATE TABLE daily_tasks (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    employee_id BIGINT UNSIGNED,
    date DATE,
    task_description TEXT,
    estimated_hours DECIMAL(4,2),
    actual_hours DECIMAL(4,2),
    status ENUM('pending', 'in_progress', 'completed', 'reviewed'),
    review_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES users(id)
);

-- جدول الإجازات والأذونات
CREATE TABLE leaves (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    employee_id BIGINT UNSIGNED,
    leave_type ENUM('annual', 'sick', 'emergency', 'unpaid') NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    reason TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    approved_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES users(id),
    FOREIGN KEY (approved_by) REFERENCES users(id)
);

-- جدول تقارير الأداء
CREATE TABLE performance_reports (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    employee_id BIGINT UNSIGNED,
    period_start DATE,
    period_end DATE,
    attendance_score DECIMAL(4,2), -- تقييم الحضور والانضباط
    productivity_score DECIMAL(4,2), -- تقييم الإنتاجية
    quality_score DECIMAL(4,2), -- تقييم جودة العمل
    tasks_completed INT, -- عدد المهام المنجزة
    actual_working_hours DECIMAL(6,2), -- ساعات العمل الفعلية
    overtime_hours DECIMAL(4,2), -- ساعات العمل الإضافية
    late_count INT, -- عدد مرات التأخير
    absence_count INT, -- عدد مرات الغياب
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id)
);

-- جدول إعدادات الدوام
CREATE TABLE attendance_settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id BIGINT UNSIGNED,
    work_days SET('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'),
    weekend_days SET('Friday','Saturday'),
    flexible_hours BOOLEAN DEFAULT FALSE,
    overtime_allowed BOOLEAN DEFAULT TRUE,
    max_overtime_hours INT DEFAULT 20,
    late_threshold INT DEFAULT 15, -- حد التأخير بالدقائق
    early_leave_threshold INT DEFAULT 15, -- حد الخروج المبكر بالدقائق
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- جدول الجدول الأسبوعي
CREATE TABLE weekly_schedules (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    employee_id BIGINT UNSIGNED,
    shift_id BIGINT UNSIGNED,
    day_of_week ENUM('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'),
    is_working_day BOOLEAN DEFAULT TRUE,
    custom_start_time TIME,
    custom_end_time TIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id),
    FOREIGN KEY (shift_id) REFERENCES work_shifts(id)
);

-- إضافة جدول الدوام الإضافي
CREATE TABLE overtime_records (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    employee_id BIGINT UNSIGNED,
    date DATE NOT NULL,
    start_time TIMESTAMP,
    end_time TIMESTAMP,
    hours DECIMAL(4,2),
    type ENUM('weekday', 'weekend', 'holiday') NOT NULL,
    rate DECIMAL(3,2) DEFAULT 1.5, -- معدل الأجر الإضافي (1.5 = ساعة ونصف)
    reason TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    approved_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id),
    FOREIGN KEY (approved_by) REFERENCES users(id)
);

-- إضافة جدول العطل الرسمية
CREATE TABLE holidays (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    type ENUM('public', 'company', 'religious') NOT NULL,
    working_allowed BOOLEAN DEFAULT FALSE, -- هل يسمح بالعمل في هذا اليوم
    overtime_rate DECIMAL(3,2) DEFAULT 2.0, -- معدل الأجر في العطل (ضعف الراتب)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- تحديث جدول دوامات العمل
INSERT INTO work_shifts (name, start_time, end_time, working_hours, description) VALUES
('الدوام الصباحي', '11:00:00', '18:00:00', 7.00, 'دوام صباحي من 11 صباحاً إلى 6 مساءً'),
('الدوام المسائي', '14:00:00', '20:00:00', 6.00, 'دوام مسائي من 2 مساءً إلى 8 مساءً');

-- جدول مزامنة البيانات
CREATE TABLE data_syncs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL,
    status ENUM('pending', 'syncing', 'completed', 'failed'),
    started_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    records_synced INT DEFAULT 0,
    error_log TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- جدول النسخ الاحتياطية
CREATE TABLE backups (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    type VARCHAR(50) NOT NULL,
    size BIGINT,
    status ENUM('creating', 'completed', 'failed', 'uploaded'),
    cloud_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- جدول سجل النظام
CREATE TABLE system_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL,
    message TEXT,
    context JSON,
    level VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- جدول المحادثات الحية
CREATE TABLE chat_rooms (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    type ENUM('support', 'team', 'private') DEFAULT 'support',
    status ENUM('active', 'closed') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE chat_messages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    room_id BIGINT UNSIGNED,
    sender_id BIGINT UNSIGNED,
    message TEXT,
    message_type ENUM('text', 'file', 'image') DEFAULT 'text',
    file_url VARCHAR(255) NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (room_id) REFERENCES chat_rooms(id),
    FOREIGN KEY (sender_id) REFERENCES users(id)
);

-- جدول تفضيلات المستخدم
CREATE TABLE user_preferences (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED,
    language VARCHAR(10) DEFAULT 'ar',
    currency VARCHAR(3) DEFAULT 'SAR',
    timezone VARCHAR(50) DEFAULT 'Asia/Riyadh',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- جدول سجل IP
CREATE TABLE ip_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED,
    ip_address VARCHAR(45),
    country_code VARCHAR(2),
    country_name VARCHAR(100),
    city VARCHAR(100),
    currency_code VARCHAR(3),
    language_code VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- تحديث جدول العملات المدعومة
CREATE TABLE currencies (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(3) UNIQUE NOT NULL,
    name VARCHAR(50) NOT NULL,
    symbol VARCHAR(5) NOT NULL,
    exchange_rate DECIMAL(10,4) DEFAULT 1.0000,
    is_default BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- إدخال العملات المدعومة فقط
INSERT INTO currencies (code, name, symbol, exchange_rate, is_default) VALUES
('IQD', 'دينار عراقي', 'د.ع', 1.0000, TRUE),
('USD', 'دولار أمريكي', '$', 0.000685, FALSE),
('EUR', 'يورو', '€', 0.000632, FALSE);

-- تحديث جدول الأسعار لدعم العملات المحددة فقط
ALTER TABLE prices
    ADD COLUMN currency_code VARCHAR(3) DEFAULT 'USD',
    ADD CONSTRAINT fk_currency_code 
    FOREIGN KEY (currency_code) 
    REFERENCES currencies(code);

-- تحديث جدول المدفوعات لدعم العملات
ALTER TABLE payments 
ADD COLUMN currency_code VARCHAR(3) DEFAULT 'IQD',
ADD COLUMN original_amount DECIMAL(10,2),
ADD COLUMN converted_amount DECIMAL(10,2),
ADD CONSTRAINT fk_payment_currency 
FOREIGN KEY (currency_code) REFERENCES currencies(code);

-- جدول إدارة المشاريع الرقمية
CREATE TABLE digital_projects (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    client_id BIGINT UNSIGNED,
    type ENUM('web', 'mobile', 'marketing', 'social_media', 'seo', 'content'),
    status ENUM('planning', 'in_progress', 'review', 'completed'),
    start_date DATE,
    deadline DATE,
    budget DECIMAL(10,2),
    requirements TEXT,
    technical_specs JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id)
);

-- جدول التسويق الرقمي
CREATE TABLE marketing_campaigns (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    client_id BIGINT UNSIGNED,
    platforms JSON, -- ['facebook', 'instagram', 'google', etc.]
    budget DECIMAL(10,2),
    start_date DATE,
    end_date DATE,
    target_audience JSON,
    kpis JSON,
    results JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id)
);

-- جدول تحليلات المواقع
CREATE TABLE website_analytics (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    website_id BIGINT UNSIGNED,
    date DATE,
    visitors INT,
    page_views INT,
    bounce_rate DECIMAL(5,2),
    avg_session_duration INT,
    conversion_rate DECIMAL(5,2),
    source_data JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- جدول إدارة المحتوى
CREATE TABLE content_management (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    content_type ENUM('blog', 'social', 'email', 'website'),
    status ENUM('draft', 'review', 'published'),
    content TEXT,
    seo_data JSON,
    schedule_date DATETIME,
    author_id BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id)
);

-- جدول أذونات الخروج
CREATE TABLE permissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    employee_id BIGINT UNSIGNED,
    permission_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    reason TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    approved_by BIGINT UNSIGNED NULL,
    actual_return_time TIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES users(id),
    FOREIGN KEY (approved_by) REFERENCES users(id)
);

-- جدول عقود الموظفين
CREATE TABLE employee_contracts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    employee_id BIGINT UNSIGNED,
    contract_number VARCHAR(50) UNIQUE,
    start_date DATE,
    end_date DATE,
    salary DECIMAL(10,2),
    position VARCHAR(100),
    contract_type ENUM('full_time', 'part_time', 'temporary', 'project_based'),
    status ENUM('active', 'expired', 'terminated') DEFAULT 'active',
    benefits JSON, -- التأمين الصحي، الإجازات، البدلات، إلخ
    terms TEXT, -- شروط العقد
    signed_by_employee BOOLEAN DEFAULT FALSE,
    signed_by_company BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES users(id)
);

-- جدول سندات القبض
CREATE TABLE receipt_vouchers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    voucher_number VARCHAR(50) UNIQUE,
    date DATE,
    received_from VARCHAR(255),
    amount DECIMAL(10,2),
    currency_code VARCHAR(3),
    payment_method ENUM('cash', 'cheque', 'transfer', 'other'),
    payment_details JSON, -- تفاصيل إضافية مثل رقم الشيك أو التحويل
    description TEXT,
    created_by BIGINT UNSIGNED,
    approved_by BIGINT UNSIGNED NULL,
    status ENUM('draft', 'approved', 'cancelled') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (approved_by) REFERENCES users(id),
    FOREIGN KEY (currency_code) REFERENCES currencies(code)
);

-- جدول سندات الصرف
CREATE TABLE payment_vouchers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    voucher_number VARCHAR(50) UNIQUE,
    date DATE,
    paid_to VARCHAR(255),
    amount DECIMAL(10,2),
    currency_code VARCHAR(3),
    payment_method ENUM('cash', 'cheque', 'transfer', 'other'),
    payment_details JSON,
    description TEXT,
    created_by BIGINT UNSIGNED,
    approved_by BIGINT UNSIGNED NULL,
    status ENUM('draft', 'approved', 'cancelled') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (approved_by) REFERENCES users(id),
    FOREIGN KEY (currency_code) REFERENCES currencies(code)
);

-- جدول الكتب الرسمية
CREATE TABLE official_letters (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    letter_number VARCHAR(50) UNIQUE,
    letter_type ENUM('warning', 'termination', 'deduction', 'promotion', 'other'),
    date DATE,
    employee_id BIGINT UNSIGNED,
    subject VARCHAR(255),
    content TEXT,
    attachments JSON,
    action_required TEXT,
    action_deadline DATE NULL,
    created_by BIGINT UNSIGNED,
    approved_by BIGINT UNSIGNED NULL,
    status ENUM('draft', 'approved', 'delivered', 'cancelled') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES users(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (approved_by) REFERENCES users(id)
);

-- جدول قوالب الكتب الرسمية
CREATE TABLE letter_templates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    type ENUM('warning', 'termination', 'deduction', 'promotion', 'other'),
    content TEXT,
    variables JSON, -- المتغيرات التي يمكن تعديلها في القالب
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- جدول مصروفات الشركة
CREATE TABLE company_expenses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    expense_number VARCHAR(50) UNIQUE,
    date DATE,
    category ENUM('rent', 'utilities', 'salaries', 'maintenance', 'supplies', 'marketing', 'other'),
    amount DECIMAL(10,2),
    currency_code VARCHAR(3),
    description TEXT,
    recurring BOOLEAN DEFAULT FALSE,
    recurring_period ENUM('monthly', 'quarterly', 'yearly') NULL,
    next_due_date DATE NULL,
    attachments JSON,
    created_by BIGINT UNSIGNED,
    approved_by BIGINT UNSIGNED NULL,
    status ENUM('pending', 'approved', 'paid', 'cancelled') DEFAULT 'pending',
    payment_voucher_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (approved_by) REFERENCES users(id),
    FOREIGN KEY (currency_code) REFERENCES currencies(code),
    FOREIGN KEY (payment_voucher_id) REFERENCES payment_vouchers(id)
);

-- جدول فئات المصروفات
CREATE TABLE expense_categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    name_ar VARCHAR(100),
    budget DECIMAL(10,2),
    description TEXT,
    parent_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES expense_categories(id)
);

-- التحقق من وجود الجداول الأساسية
SELECT 
    table_name, 
    table_rows,
    create_time
FROM 
    information_schema.tables 
WHERE 
    table_schema = 'phoenix_db'
ORDER BY 
    create_time DESC;

-- التحقق من العلاقات بين الجداول
SELECT 
    table_name,
    column_name,
    referenced_table_name,
    referenced_column_name
FROM
    information_schema.key_column_usage
WHERE
    referenced_table_name IS NOT NULL
    AND table_schema = 'phoenix_db'; 