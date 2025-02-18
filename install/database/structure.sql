-- إنشاء قاعدة البيانات بدعم UTF-8
CREATE DATABASE IF NOT EXISTS `phoenix_system` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `phoenix_system`;

-- التأكد من عدم وجود الجداول
SET FOREIGN_KEY_CHECKS = 0;

-- حذف الجداول إن وجدت
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `settings`;
DROP TABLE IF EXISTS `activities`;
DROP TABLE IF EXISTS `backups`;
DROP TABLE IF EXISTS `sync_logs`;
DROP TABLE IF EXISTS `system_logs`;
DROP TABLE IF EXISTS `departments`;
DROP TABLE IF EXISTS `employees`;
DROP TABLE IF EXISTS `attendance_records`;
DROP TABLE IF EXISTS `leaves`;
DROP TABLE IF EXISTS `tasks`;
DROP TABLE IF EXISTS `performance_reviews`;
DROP TABLE IF EXISTS `clients`;
DROP TABLE IF EXISTS `services`;
DROP TABLE IF EXISTS `service_features`;
DROP TABLE IF EXISTS `projects`;
DROP TABLE IF EXISTS `project_members`;
DROP TABLE IF EXISTS `project_tasks`;
DROP TABLE IF EXISTS `marketing_campaigns`;
DROP TABLE IF EXISTS `marketing_content`;
DROP TABLE IF EXISTS `marketing_analytics`;
DROP TABLE IF EXISTS `sales_opportunities`;
DROP TABLE IF EXISTS `sales_followups`;
DROP TABLE IF EXISTS `invoices`;
DROP TABLE IF EXISTS `invoice_items`;
DROP TABLE IF EXISTS `payments`;
DROP TABLE IF EXISTS `expenses`;
DROP TABLE IF EXISTS `financial_reports`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `inventory_movements`;
DROP TABLE IF EXISTS `purchase_orders`;
DROP TABLE IF EXISTS `purchase_order_items`;
DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `support_tickets`;
DROP TABLE IF EXISTS `ticket_replies`;
DROP TABLE IF EXISTS `ticket_attachments`;
DROP TABLE IF EXISTS `knowledge_base`;
DROP TABLE IF EXISTS `knowledge_base_comments`;
DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `messages`;
DROP TABLE IF EXISTS `message_attachments`;
DROP TABLE IF EXISTS `chat_rooms`;
DROP TABLE IF EXISTS `chat_room_members`;
DROP TABLE IF EXISTS `chat_messages`;
DROP TABLE IF EXISTS `roles`;
DROP TABLE IF EXISTS `permissions`;
DROP TABLE IF EXISTS `role_permissions`;
DROP TABLE IF EXISTS `login_logs`;
DROP TABLE IF EXISTS `failed_login_attempts`;
DROP TABLE IF EXISTS `verification_codes`;
DROP TABLE IF EXISTS `work_shifts`;
DROP TABLE IF EXISTS `employee_contracts`;
DROP TABLE IF EXISTS `overtime_records`;
DROP TABLE IF EXISTS `contract_templates`;
DROP TABLE IF EXISTS `salary_payments`;
DROP TABLE IF EXISTS `salary_advances`;
DROP TABLE IF EXISTS `advance_repayments`;
DROP TABLE IF EXISTS `salary_details`;
DROP TABLE IF EXISTS `loan_repayment_plans`;
DROP TABLE IF EXISTS `loan_installments`;
DROP TABLE IF EXISTS `payment_notifications`;
DROP TABLE IF EXISTS `notification_templates`;
DROP TABLE IF EXISTS `notification_logs`;
DROP TABLE IF EXISTS `notification_performance_reports`;
DROP TABLE IF EXISTS `notification_channel_performance`;
DROP TABLE IF EXISTS `system_performance_metrics`;
DROP TABLE IF EXISTS `setting_groups`;
DROP TABLE IF EXISTS `advanced_settings`;
DROP TABLE IF EXISTS `setting_changes_log`;
DROP TABLE IF EXISTS `page_customizations`;
DROP TABLE IF EXISTS `page_components`;
DROP TABLE IF EXISTS `component_library`;
DROP TABLE IF EXISTS `page_layouts`;

SET FOREIGN_KEY_CHECKS = 1;

-- إنشاء جداول النظام
-- جدول المستخدمين
CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin', 'super-admin') DEFAULT 'user',
    status BOOLEAN DEFAULT TRUE,
    remember_token VARCHAR(100),
    email_verified_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول النشاطات
CREATE TABLE IF NOT EXISTS activities (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    action VARCHAR(255) NOT NULL,
    description TEXT,
    model_type VARCHAR(255) NULL,
    model_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول النسخ الاحتياطية
CREATE TABLE IF NOT EXISTS backups (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    size BIGINT UNSIGNED,
    type ENUM('full', 'database', 'files') DEFAULT 'full',
    status ENUM('pending', 'running', 'completed', 'failed') DEFAULT 'pending',
    created_by BIGINT UNSIGNED,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول مجموعات الإعدادات
CREATE TABLE IF NOT EXISTS setting_groups (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    key VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    display_order INT DEFAULT 0,
    is_core BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول الإعدادات المتقدمة
CREATE TABLE IF NOT EXISTS advanced_settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    group_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    key VARCHAR(255) NOT NULL UNIQUE,
    value TEXT,
    type ENUM('text', 'number', 'boolean', 'json', 'array', 'select') NOT NULL,
    options JSON NULL,
    validation_rules VARCHAR(255),
    is_encrypted BOOLEAN DEFAULT FALSE,
    is_required BOOLEAN DEFAULT TRUE,
    is_visible BOOLEAN DEFAULT TRUE,
    description TEXT,
    default_value TEXT,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (group_id) REFERENCES setting_groups(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول الإعدادات
CREATE TABLE IF NOT EXISTS settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    key VARCHAR(255) NOT NULL UNIQUE,
    value TEXT,
    group_name VARCHAR(100) DEFAULT 'general',
    is_system BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول الأدوار
CREATE TABLE IF NOT EXISTS roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    name_ar VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    is_system BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول الصلاحيات
CREATE TABLE IF NOT EXISTS permissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    name_ar VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    module VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول صلاحيات الأدوار
CREATE TABLE IF NOT EXISTS role_permissions (
    role_id BIGINT UNSIGNED NOT NULL,
    permission_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول تخصيص الصفحات
CREATE TABLE IF NOT EXISTS page_customizations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_key VARCHAR(100) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    layout VARCHAR(100) DEFAULT 'default',
    content JSON,
    meta_data JSON,
    custom_css TEXT,
    custom_js TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول مكونات الصفحات
CREATE TABLE IF NOT EXISTS page_components (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_id BIGINT UNSIGNED NOT NULL,
    parent_id BIGINT UNSIGNED NULL,
    component_type VARCHAR(100) NOT NULL,
    component_key VARCHAR(100) NOT NULL,
    container_key VARCHAR(100),
    layout_position VARCHAR(100),
    title VARCHAR(255),
    content JSON,
    settings JSON,
    style_settings JSON,
    responsive_settings JSON,
    animation_settings JSON,
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (page_id) REFERENCES page_customizations(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES page_components(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء الفهارس
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_settings_group ON settings(group_name);
CREATE INDEX idx_activities_user ON activities(user_id);
CREATE INDEX idx_activities_created ON activities(created_at);
CREATE INDEX idx_backups_status ON backups(status);
CREATE INDEX idx_setting_groups_key ON setting_groups(key);
CREATE INDEX idx_advanced_settings_key ON advanced_settings(key);
CREATE INDEX idx_roles_slug ON roles(slug);
CREATE INDEX idx_permissions_slug ON permissions(slug);
CREATE INDEX idx_permissions_module ON permissions(module);
CREATE INDEX idx_page_customizations_key ON page_customizations(page_key);
CREATE INDEX idx_page_components_page ON page_components(page_id);
CREATE INDEX idx_page_components_parent ON page_components(parent_id);
CREATE INDEX idx_page_components_type ON page_components(component_type);

-- إضافة القيود
ALTER TABLE activities
    ADD CONSTRAINT fk_activities_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL;
-- ... (باقي القيود)

-- إدخال البيانات الأساسية
INSERT INTO users (name, email, password, role) VALUES 
('مدير النظام', 'admin@phoenixitiq.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super-admin');

INSERT INTO settings (key, value, group_name, is_system) VALUES 
('site_name', 'Phoenix IT', 'general', true),
('site_description', 'نظام إدارة الخدمات التقنية', 'general', true),
('backup_enabled', '1', 'backup', true),
('backup_frequency', 'daily', 'backup', true),
('mail_enabled', '1', 'mail', true),
('system_version', '1.0.0', 'system', true);

INSERT INTO setting_groups (name, key, description, is_core, display_order) VALUES 
('إعدادات الموقع', 'website', 'الإعدادات العامة للموقع', true, 0),
('إعدادات SEO', 'seo', 'إعدادات تحسين محركات البحث', true, 9),
('إعدادات اللغة والترجمة', 'localization', 'إعدادات اللغات والترجمة', true, 10),
('إعدادات التصميم', 'theme', 'إعدادات تصميم الموقع', true, 11),
('إعدادات القوائم', 'menus', 'إعدادات قوائم الموقع', false, 12);

-- ... (باقي البيانات الأساسية)

-- إدخال المزيد من البيانات الأساسية
INSERT INTO roles (name, name_ar, slug, description, is_system) VALUES 
('Super Admin', 'مدير النظام', 'super-admin', 'كامل الصلاحيات للنظام', true),
('Admin', 'مشرف', 'admin', 'صلاحيات إدارية', true),
('User', 'مستخدم', 'user', 'صلاحيات محدودة', true);

INSERT INTO permissions (name, name_ar, slug, module) VALUES 
('View Dashboard', 'عرض لوحة التحكم', 'view-dashboard', 'dashboard'),
('Manage Users', 'إدارة المستخدمين', 'manage-users', 'users'),
('Manage Settings', 'إدارة الإعدادات', 'manage-settings', 'settings'),
('Manage Pages', 'إدارة الصفحات', 'manage-pages', 'pages'),
('View Reports', 'عرض التقارير', 'view-reports', 'reports'),
('Manage Backups', 'إدارة النسخ الاحتياطية', 'manage-backups', 'backups');

-- ربط الصلاحيات بالأدوار
INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r
CROSS JOIN permissions p
WHERE r.slug = 'super-admin';

-- إضافة إعدادات متقدمة
INSERT INTO advanced_settings (group_id, name, key, value, type, is_required, description, default_value, display_order) VALUES 
(1, 'نظام الألوان', 'color_scheme', '{"primary":{"main":"#1e88e5","light":"#4b9fea","dark":"#1565c0"}}', 'json', true, 'نظام الألوان الأساسي للموقع', '', 1),
(1, 'تخصيص الواجهة', 'ui_customization', '{"borderRadius":"8px","spacing":{"unit":8}}', 'json', true, 'تخصيص عناصر واجهة المستخدم', '', 2),
(1, 'إعدادات الخطوط', 'typography_settings', '{"fontFamily":{"primary":"Cairo","secondary":"Tajawal"}}', 'json', true, 'إعدادات الخطوط والتايبوغرافي', '', 3);

-- جدول الأقسام
CREATE TABLE IF NOT EXISTS departments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    name_ar VARCHAR(255) NOT NULL,
    description TEXT,
    manager_id BIGINT UNSIGNED NULL,
    parent_id BIGINT UNSIGNED NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (manager_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (parent_id) REFERENCES departments(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول المشاريع
CREATE TABLE IF NOT EXISTS projects (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    client_id BIGINT UNSIGNED NULL,
    manager_id BIGINT UNSIGNED NULL,
    status ENUM('pending', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    start_date DATE,
    end_date DATE,
    budget DECIMAL(12, 2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (manager_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول المهام
CREATE TABLE IF NOT EXISTS tasks (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    project_id BIGINT UNSIGNED NULL,
    assigned_to BIGINT UNSIGNED NULL,
    status ENUM('todo', 'in_progress', 'review', 'completed') DEFAULT 'todo',
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    due_date DATE,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول التذاكر
CREATE TABLE IF NOT EXISTS tickets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    user_id BIGINT UNSIGNED NULL,
    assigned_to BIGINT UNSIGNED NULL,
    department_id BIGINT UNSIGNED NULL,
    status ENUM('open', 'in_progress', 'pending', 'resolved', 'closed') DEFAULT 'open',
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إضافة المزيد من الفهارس
CREATE INDEX idx_departments_manager ON departments(manager_id);
CREATE INDEX idx_projects_manager ON projects(manager_id);
CREATE INDEX idx_tasks_project ON tasks(project_id);
CREATE INDEX idx_tasks_assigned ON tasks(assigned_to);
CREATE INDEX idx_tickets_user ON tickets(user_id);
CREATE INDEX idx_tickets_assigned ON tickets(assigned_to);

-- إدخال بيانات الأقسام
INSERT INTO departments (name, name_ar, description) VALUES 
('Technical Support', 'الدعم الفني', 'قسم الدعم الفني وحل المشكلات'),
('Development', 'التطوير', 'قسم تطوير البرمجيات'),
('Network', 'الشبكات', 'قسم إدارة الشبكات والأنظمة'); 