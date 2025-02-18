CREATE TABLE `contact_messages` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `phone` varchar(20) DEFAULT NULL,
    `message` text NOT NULL,
    `office` enum('uk','iraq') NOT NULL DEFAULT 'iraq',
    `status` enum('new','read','replied','archived') DEFAULT 'new',
    `notes` text DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `contact_messages_status_index` (`status`),
    KEY `contact_messages_office_index` (`office`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 