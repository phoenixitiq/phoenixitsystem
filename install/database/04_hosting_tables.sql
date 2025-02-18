-- جداول الاستضافة والخوادم
CREATE TABLE `servers` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `hostname` varchar(255) NOT NULL,
    `ip_address` varchar(45) NOT NULL,
    `type` enum('shared','vps','dedicated','cloud') NOT NULL,
    `provider` varchar(100) DEFAULT NULL,
    `location` varchar(100) DEFAULT NULL,
    `specifications` json DEFAULT NULL,
    `status` enum('active','inactive','maintenance') DEFAULT 'active',
    `notes` text DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `server_resources` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `server_id` bigint(20) unsigned NOT NULL,
    `cpu_usage` decimal(5,2) DEFAULT NULL,
    `memory_usage` decimal(5,2) DEFAULT NULL,
    `disk_usage` decimal(5,2) DEFAULT NULL,
    `bandwidth_usage` decimal(10,2) DEFAULT NULL,
    `active_processes` int DEFAULT NULL,
    `load_average` varchar(50) DEFAULT NULL,
    `recorded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`server_id`) REFERENCES `servers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `domains` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `client_id` bigint(20) unsigned NOT NULL,
    `server_id` bigint(20) unsigned DEFAULT NULL,
    `name` varchar(255) NOT NULL,
    `registrar` varchar(100) DEFAULT NULL,
    `registration_date` date DEFAULT NULL,
    `expiry_date` date DEFAULT NULL,
    `auto_renew` tinyint(1) DEFAULT 0,
    `status` enum('active','expired','transferred','pending') DEFAULT 'active',
    `dns_management` tinyint(1) DEFAULT 0,
    `nameservers` json DEFAULT NULL,
    `dns_records` json DEFAULT NULL,
    `ssl_status` enum('none','active','expired') DEFAULT 'none',
    `ssl_expiry` date DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`server_id`) REFERENCES `servers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `hosting_packages` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `description` text DEFAULT NULL,
    `disk_space` int NOT NULL, -- بالميجابايت
    `bandwidth` int NOT NULL, -- بالميجابايت
    `domains_limit` int DEFAULT 1,
    `subdomain_limit` int DEFAULT 5,
    `database_limit` int DEFAULT 5,
    `email_accounts` int DEFAULT 5,
    `ftp_accounts` int DEFAULT 2,
    `features` json DEFAULT NULL,
    `price` decimal(10,2) NOT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `hosting_accounts` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `client_id` bigint(20) unsigned NOT NULL,
    `server_id` bigint(20) unsigned NOT NULL,
    `domain_id` bigint(20) unsigned NOT NULL,
    `package_id` bigint(20) unsigned NOT NULL,
    `username` varchar(32) NOT NULL,
    `password` varchar(255) NOT NULL,
    `status` enum('active','suspended','terminated') DEFAULT 'active',
    `suspension_reason` text DEFAULT NULL,
    `disk_usage` bigint DEFAULT 0,
    `bandwidth_usage` bigint DEFAULT 0,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`server_id`) REFERENCES `servers` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`domain_id`) REFERENCES `domains` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`package_id`) REFERENCES `hosting_packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `hosting_resources` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `account_id` bigint(20) unsigned NOT NULL,
    `disk_usage` bigint NOT NULL DEFAULT 0,
    `bandwidth_usage` bigint NOT NULL DEFAULT 0,
    `cpu_usage` decimal(5,2) DEFAULT NULL,
    `memory_usage` decimal(5,2) DEFAULT NULL,
    `recorded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`account_id`) REFERENCES `hosting_accounts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء الفهارس
CREATE INDEX idx_server_status ON servers(status);
CREATE INDEX idx_server_type ON servers(type);
CREATE INDEX idx_domain_name ON domains(name);
CREATE INDEX idx_domain_expiry ON domains(expiry_date);
CREATE INDEX idx_domain_status ON domains(status);
CREATE INDEX idx_hosting_username ON hosting_accounts(username);
CREATE INDEX idx_hosting_status ON hosting_accounts(status);
CREATE INDEX idx_resources_date ON hosting_resources(recorded_at);

-- إدخال البيانات الأساسية
INSERT INTO `hosting_packages` 
(`name`, `description`, `disk_space`, `bandwidth`, `domains_limit`, `price`, `features`) VALUES
('باقة أساسية', 'مناسبة للمواقع الصغيرة', 5000, 50000, 1, 50.00, 
'{"ssl": true, "backup": "weekly", "support": "email"}'),

('باقة متقدمة', 'مناسبة للشركات المتوسطة', 15000, 150000, 3, 100.00,
'{"ssl": true, "backup": "daily", "support": "24/7"}'),

('باقة احترافية', 'مناسبة للمشاريع الكبيرة', 50000, 500000, 10, 200.00,
'{"ssl": true, "backup": "daily", "support": "24/7", "dedicated_ip": true}'); 