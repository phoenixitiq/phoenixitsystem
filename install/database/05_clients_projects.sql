-- جداول العملاء والمشاريع
CREATE TABLE `clients` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint(20) unsigned DEFAULT NULL,
    `company_name` varchar(255) DEFAULT NULL,
    `contact_name` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `phone` varchar(20) NOT NULL,
    `address` text DEFAULT NULL,
    `city` varchar(100) DEFAULT NULL,
    `country` varchar(100) DEFAULT NULL,
    `tax_number` varchar(50) DEFAULT NULL,
    `status` enum('active','inactive','blocked') DEFAULT 'active',
    `source` varchar(50) DEFAULT NULL,
    `notes` text DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `client_documents` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `client_id` bigint(20) unsigned NOT NULL,
    `type` varchar(50) NOT NULL,
    `title` varchar(255) NOT NULL,
    `file_path` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `uploaded_by` bigint(20) unsigned NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `projects` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `client_id` bigint(20) unsigned NOT NULL,
    `name` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `start_date` date DEFAULT NULL,
    `deadline` date DEFAULT NULL,
    `completed_at` timestamp NULL DEFAULT NULL,
    `status` enum('pending','in_progress','completed','on_hold','cancelled') DEFAULT 'pending',
    `priority` enum('low','medium','high','urgent') DEFAULT 'medium',
    `budget` decimal(10,2) DEFAULT NULL,
    `manager_id` bigint(20) unsigned DEFAULT NULL,
    `progress` tinyint DEFAULT 0,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `project_members` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `project_id` bigint(20) unsigned NOT NULL,
    `user_id` bigint(20) unsigned NOT NULL,
    `role` varchar(50) DEFAULT 'member',
    `created_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_project_member` (`project_id`, `user_id`),
    FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tasks` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `project_id` bigint(20) unsigned NOT NULL,
    `title` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `assigned_to` bigint(20) unsigned DEFAULT NULL,
    `status` enum('pending','in_progress','completed','on_hold') DEFAULT 'pending',
    `priority` enum('low','medium','high','urgent') DEFAULT 'medium',
    `start_date` date DEFAULT NULL,
    `due_date` date DEFAULT NULL,
    `completed_at` timestamp NULL DEFAULT NULL,
    `progress` tinyint DEFAULT 0,
    `created_by` bigint(20) unsigned NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `task_comments` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `task_id` bigint(20) unsigned NOT NULL,
    `user_id` bigint(20) unsigned NOT NULL,
    `comment` text NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء الفهارس
CREATE INDEX idx_client_status ON clients(status);
CREATE INDEX idx_client_email ON clients(email);
CREATE INDEX idx_client_phone ON clients(phone);
CREATE INDEX idx_project_status ON projects(status);
CREATE INDEX idx_project_dates ON projects(start_date, deadline);
CREATE INDEX idx_task_status ON tasks(status);
CREATE INDEX idx_task_dates ON tasks(start_date, due_date);
CREATE INDEX idx_task_priority ON tasks(priority); 