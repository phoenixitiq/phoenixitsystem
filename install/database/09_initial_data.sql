-- إدخال البيانات الأولية للأدوار
INSERT INTO `roles` (`name`, `slug`, `description`) VALUES
('مدير النظام', 'admin', 'صلاحيات كاملة للنظام'),
('مدير المبيعات', 'sales_manager', 'إدارة المبيعات والعملاء'),
('مدير المشاريع', 'project_manager', 'إدارة المشاريع والمهام'),
('محاسب', 'accountant', 'إدارة الفواتير والمدفوعات'),
('موظف دعم فني', 'support', 'التعامل مع تذاكر الدعم الفني'),
('مسوق', 'marketer', 'إدارة الحملات التسويقية'),
('عميل', 'client', 'صلاحيات العميل الأساسية');

-- إدخال البيانات الأولية للإعدادات
INSERT INTO `settings` (`key`, `value`, `group_name`, `is_system`) VALUES
('company_name', 'Phoenix System', 'general', 1),
('company_email', 'info@phoenix-sys.com', 'general', 1),
('company_phone', '+964 XXX XXX XXXX', 'general', 1),
('company_address', 'Baghdad, Iraq', 'general', 1),
('currency', 'IQD', 'financial', 1),
('tax_rate', '0', 'financial', 1),
('invoice_prefix', 'INV-', 'financial', 1),
('invoice_terms', 'الشروط والأحكام الافتراضية للفواتير', 'financial', 1),
('smtp_host', '', 'mail', 1),
('smtp_port', '', 'mail', 1),
('smtp_username', '', 'mail', 1),
('smtp_password', '', 'mail', 1),
('mail_from_address', '', 'mail', 1),
('mail_from_name', '', 'mail', 1);

-- إدخال البيانات الأولية لأقسام التذاكر
INSERT INTO `ticket_departments` (`name`, `description`, `is_active`) VALUES
('الدعم الفني', 'قسم الدعم الفني العام', 1),
('المبيعات', 'استفسارات المبيعات', 1),
('الحسابات', 'استفسارات الفواتير والمدفوعات', 1),
('الاستضافة', 'دعم خدمات الاستضافة', 1); 