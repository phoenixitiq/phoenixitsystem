-- جداول نظام التذاكر
CREATE TABLE `ticket_departments` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `description` text DEFAULT NULL,
    `manager_id` bigint(20) unsigned DEFAULT NULL,
    `email` varchar(255) DEFAULT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tickets` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `department_id` bigint(20) unsigned NOT NULL,
    `client_id` bigint(20) unsigned NOT NULL,
    `subject` varchar(255) NOT NULL,
    `description` text NOT NULL,
    `priority` enum('low','medium','high','urgent') DEFAULT 'medium',
    `status` enum('open','in_progress','waiting','resolved','closed') DEFAULT 'open',
    `assigned_to` bigint(20) unsigned DEFAULT NULL,
    `last_reply_at` timestamp NULL DEFAULT NULL,
    `resolved_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`department_id`) REFERENCES `ticket_departments` (`id`),
    FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `ticket_replies` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `ticket_id` bigint(20) unsigned NOT NULL,
    `user_id` bigint(20) unsigned NOT NULL,
    `message` text NOT NULL,
    `attachments` json DEFAULT NULL,
    `is_private` tinyint(1) DEFAULT 0,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء الفهارس
CREATE INDEX idx_ticket_status ON tickets(status);
CREATE INDEX idx_ticket_priority ON tickets(priority);
CREATE INDEX idx_ticket_dates ON tickets(created_at, last_reply_at); 