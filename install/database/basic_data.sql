-- إدخال البيانات الأساسية للنظام

-- إعدادات النظام الأساسية
INSERT INTO settings (key, value, group_name, is_system) VALUES 
('site_name', 'Phoenix IT', 'general', true),
('site_description', 'نظام إدارة الخدمات التقنية', 'general', true),
('site_logo', '/assets/images/logo.png', 'general', true),
('site_favicon', '/assets/images/favicon.ico', 'general', true);

-- الأدوار الأساسية
INSERT INTO roles (name, name_ar, slug, description, is_system) VALUES 
('Super Admin', 'مدير النظام', 'super-admin', 'كامل الصلاحيات للنظام', true),
('Admin', 'مشرف', 'admin', 'صلاحيات إدارية', true);

-- الصلاحيات الأساسية
INSERT INTO permissions (name, name_ar, slug, module) VALUES 
('View Dashboard', 'عرض لوحة التحكم', 'view-dashboard', 'dashboard'),
('Manage Users', 'إدارة المستخدمين', 'manage-users', 'users');

-- إعدادات التصميم
INSERT INTO settings (key, value, group_name) VALUES 
('theme_colors', '{"primary":"#1e88e5","secondary":"#424242"}', 'theme'),
('fonts', '{"main":"Cairo","heading":"Tajawal"}', 'theme');

-- إدخال البيانات الأساسية
INSERT INTO users (name, email, password, role) VALUES 
('مدير النظام', 'admin@phoenixitiq.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- إدخال الإعدادات الأساسية
INSERT INTO settings (key, value) VALUES
('site_name', 'Phoenix IT'),
('site_description', 'نظام إدارة الخدمات التقنية'),
('site_email', 'info@phoenixitiq.com'),
('site_phone', '+964XXXXXXXX'),
('site_address', 'العراق'),
('site_logo', '/images/logo.svg'),
('site_favicon', '/images/favicon.svg'),
('default_language', 'ar'),
('timezone', 'Asia/Baghdad'); 