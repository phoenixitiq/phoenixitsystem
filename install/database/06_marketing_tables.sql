-- جداول التسويق والمحتوى
CREATE TABLE `marketing_campaigns` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `start_date` date NOT NULL,
    `end_date` date DEFAULT NULL,
    `budget` decimal(10,2) DEFAULT NULL,
    `status` enum('draft','active','completed','cancelled') DEFAULT 'draft',
    `target_audience` json DEFAULT NULL,
    `goals` json DEFAULT NULL,
    `metrics` json DEFAULT NULL,
    `manager_id` bigint(20) unsigned DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `social_media_accounts` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `platform` varchar(50) NOT NULL,
    `account_name` varchar(100) NOT NULL,
    `account_url` varchar(255) NOT NULL,
    `credentials` json DEFAULT NULL,
    `status` enum('active','inactive') DEFAULT 'active',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `social_media_posts` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `campaign_id` bigint(20) unsigned DEFAULT NULL,
    `account_id` bigint(20) unsigned NOT NULL,
    `content` text NOT NULL,
    `media_urls` json DEFAULT NULL,
    `scheduled_at` timestamp NULL DEFAULT NULL,
    `published_at` timestamp NULL DEFAULT NULL,
    `status` enum('draft','scheduled','published','failed') DEFAULT 'draft',
    `engagement_metrics` json DEFAULT NULL,
    `created_by` bigint(20) unsigned NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`campaign_id`) REFERENCES `marketing_campaigns` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`account_id`) REFERENCES `social_media_accounts` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء الفهارس
CREATE INDEX idx_campaign_status ON marketing_campaigns(status);
CREATE INDEX idx_campaign_dates ON marketing_campaigns(start_date, end_date);
CREATE INDEX idx_social_post_status ON social_media_posts(status);
CREATE INDEX idx_social_post_schedule ON social_media_posts(scheduled_at); 