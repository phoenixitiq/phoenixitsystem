-- إضافة البيانات الأولية للنظام بعد التثبيت
INSERT INTO settings (key, value, group_name, is_system) VALUES 
('company_name', 'Phoenix IT', 'company', true),
('company_email', 'info@phoenixitiq.com', 'company', true),
('company_phone', '+964 XXX XXXX', 'company', true),
('company_address', 'Iraq, Baghdad', 'company', true),
('work_hours_type', 'shifts', 'hr', true),
('salary_payment_date', '25', 'hr', true),
('advance_payment_limit', '50', 'hr', true), -- 50% من الراتب كحد أقصى للسلفة
('overtime_rate', '1.5', 'hr', true),
('max_monthly_advance', '50', 'hr', true),
('max_loan_amount', '10000', 'hr', true),
('max_loan_installments', '24', 'hr', true),
('min_service_for_loan', '6', 'hr', true),
('loan_approval_chain', 'hr_manager,finance_manager,ceo', 'hr', true);

-- إضافة فترات الدوام الافتراضية
INSERT INTO work_shifts (name, name_en, start_time, end_time, hours_per_day) VALUES 
('الفترة الأولى', 'First Shift', '11:00', '19:00', 8),
('الفترة الثانية', 'Second Shift', '13:00', '21:00', 8),
('الفترة الثالثة', 'Third Shift', '14:00', '20:00', 6);

-- إضافة قوالب الإشعارات
INSERT INTO notification_templates (name, type, channel, subject, content, variables) VALUES 
('تذكير بموعد السداد - واتساب', 'payment_due', 'whatsapp',
 NULL,
 'مرحباً {recipient_name}،\nنود تذكيركم بموعد سداد {payment_type} بقيمة {amount} في تاريخ {due_date}.\nللدفع الآن: {payment_link}',
 '["recipient_name", "payment_type", "amount", "due_date", "payment_link"]'),

('فاتورة جديدة - بريد', 'invoice', 'email',
 'فاتورة جديدة #{invoice_number}',
 'مرحباً {recipient_name}،\n\nمرفق فاتورة رقم {invoice_number} بقيمة {amount}.\nتاريخ الاستحقاق: {due_date}\n\nللدفع الآن اضغط هنا: {payment_link}',
 '["recipient_name", "invoice_number", "amount", "due_date", "payment_link"]'),

('تأخر السداد - واتساب', 'payment_overdue', 'whatsapp',
 NULL,
 'تنبيه: تأخر سداد {payment_type} المستحق بتاريخ {due_date}. المبلغ المستحق: {amount}\nللدفع الآن: {payment_link}',
 '["payment_type", "due_date", "amount", "payment_link"]'),

('رابط الدفع - واتساب', 'payment_link', 'whatsapp',
 NULL,
 'مرحباً {recipient_name}،\nيمكنك الدفع الآن عبر الرابط التالي:\n{payment_link}\nالمبلغ المستحق: {amount}',
 '["recipient_name", "payment_link", "amount"]'),

('تذكير متأخرات - بريد', 'overdue_reminder', 'email',
 'تذكير: دفعات متأخرة',
 'عزيزي {recipient_name}،\n\nنود تذكيركم بوجود دفعات متأخرة:\n{overdue_details}\n\nالرجاء المبادرة بالسداد في أقرب وقت.\n\nللدفع الآن: {payment_link}',
 '["recipient_name", "overdue_details", "payment_link"]'); 