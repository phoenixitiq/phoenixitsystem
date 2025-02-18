-- إنشاء قاعدة البيانات بدعم UTF-8
CREATE DATABASE IF NOT EXISTS `phoenix_system` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `phoenix_system`;

-- تعطيل فحص المفاتيح الأجنبية مؤقتاً
SET FOREIGN_KEY_CHECKS = 0;

-- حذف الجداول إن وجدت
DROP TABLE IF EXISTS `users`, `roles`, `permissions`, `permission_role`, `departments`;
DROP TABLE IF EXISTS `employees`, `attendance_records`, `leaves`, `documents`;
DROP TABLE IF EXISTS `clients`, `projects`, `tasks`, `subtasks`, `task_comments`;
DROP TABLE IF EXISTS `marketing_campaigns`, `social_media_posts`, `marketing_analytics`;
DROP TABLE IF EXISTS `tickets`, `ticket_responses`;
DROP TABLE IF EXISTS `payment_gateways`, `payment_transactions`, `cash_receipts`;
DROP TABLE IF EXISTS `servers`, `domains`, `cpanel_servers`, `cpanel_accounts`;
DROP TABLE IF EXISTS `services`, `service_addons`, `packages`, `subscriptions`;
DROP TABLE IF EXISTS `invoices`, `invoice_items`;
DROP TABLE IF EXISTS `settings`, `activity_logs`, `notifications`;

-- إعادة تفعيل فحص المفاتيح الأجنبية
SET FOREIGN_KEY_CHECKS = 1; 