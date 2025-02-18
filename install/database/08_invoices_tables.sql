-- جداول الفواتير والمدفوعات
CREATE TABLE `invoices` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `client_id` bigint(20) unsigned NOT NULL,
    `project_id` bigint(20) unsigned DEFAULT NULL,
    `invoice_number` varchar(50) NOT NULL UNIQUE,
    `issue_date` date NOT NULL,
    `due_date` date NOT NULL,
    `subtotal` decimal(10,2) NOT NULL,
    `tax_rate` decimal(5,2) DEFAULT 0.00,
    `tax_amount` decimal(10,2) DEFAULT 0.00,
    `discount_type` enum('fixed','percentage') DEFAULT NULL,
    `discount_value` decimal(10,2) DEFAULT 0.00,
    `total` decimal(10,2) NOT NULL,
    `paid_amount` decimal(10,2) DEFAULT 0.00,
    `status` enum('draft','sent','paid','partially_paid','overdue','cancelled') DEFAULT 'draft',
    `notes` text DEFAULT NULL,
    `terms` text DEFAULT NULL,
    `created_by` bigint(20) unsigned NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `invoice_items` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `invoice_id` bigint(20) unsigned NOT NULL,
    `description` varchar(255) NOT NULL,
    `quantity` decimal(10,2) NOT NULL DEFAULT 1.00,
    `unit_price` decimal(10,2) NOT NULL,
    `tax_rate` decimal(5,2) DEFAULT 0.00,
    `tax_amount` decimal(10,2) DEFAULT 0.00,
    `discount_type` enum('fixed','percentage') DEFAULT NULL,
    `discount_value` decimal(10,2) DEFAULT 0.00,
    `total` decimal(10,2) NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء الفهارس
CREATE INDEX idx_invoice_number ON invoices(invoice_number);
CREATE INDEX idx_invoice_status ON invoices(status);
CREATE INDEX idx_invoice_dates ON invoices(issue_date, due_date);
CREATE INDEX idx_invoice_client ON invoices(client_id); 