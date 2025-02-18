-- جداول نظام الدفع
CREATE TABLE `payment_gateways` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `code` varchar(50) NOT NULL UNIQUE,
    `provider` varchar(100) NOT NULL,
    `settings` json DEFAULT NULL,
    `credentials` json DEFAULT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `test_mode` tinyint(1) DEFAULT 0,
    `instructions` text DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `payment_transactions` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `gateway_id` bigint(20) unsigned NOT NULL,
    `invoice_id` bigint(20) unsigned DEFAULT NULL,
    `subscription_id` bigint(20) unsigned DEFAULT NULL,
    `transaction_id` varchar(100) NOT NULL,
    `amount` decimal(10,2) NOT NULL,
    `currency` varchar(3) DEFAULT 'IQD',
    `status` enum('pending','completed','failed','cancelled') DEFAULT 'pending',
    `payment_method` varchar(50) DEFAULT NULL,
    `payment_details` json DEFAULT NULL,
    `payer_name` varchar(255) DEFAULT NULL,
    `payer_phone` varchar(20) DEFAULT NULL,
    -- حقول زين كاش
    `zain_cash_phone` varchar(15) DEFAULT NULL,
    `zain_cash_otp` varchar(10) DEFAULT NULL,
    `zain_cash_transaction_id` varchar(100) DEFAULT NULL,
    -- حقول تبادل
    `tabadul_order_id` varchar(100) DEFAULT NULL,
    `tabadul_order_number` varchar(100) DEFAULT NULL,
    `tabadul_status` varchar(50) DEFAULT NULL,
    -- حقول عامة
    `receipt_number` varchar(50) DEFAULT NULL,
    `receipt_image` varchar(255) DEFAULT NULL,
    `notes` text DEFAULT NULL,
    `processed_by` bigint(20) unsigned DEFAULT NULL,
    `processed_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`gateway_id`) REFERENCES `payment_gateways` (`id`),
    FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cash_receipts` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `transaction_id` bigint(20) unsigned NOT NULL,
    `receipt_number` varchar(50) NOT NULL UNIQUE,
    `amount` decimal(10,2) NOT NULL,
    `received_from` varchar(255) NOT NULL,
    `received_by` bigint(20) unsigned NOT NULL,
    `payment_date` date NOT NULL,
    `notes` text DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`transaction_id`) REFERENCES `payment_transactions` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول تتبع API الدفع
CREATE TABLE `payment_api_logs` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `gateway_id` bigint(20) unsigned NOT NULL,
    `transaction_id` bigint(20) unsigned DEFAULT NULL,
    `request_url` varchar(255) NOT NULL,
    `request_method` varchar(10) NOT NULL,
    `request_headers` json DEFAULT NULL,
    `request_body` json DEFAULT NULL,
    `response_code` int DEFAULT NULL,
    `response_body` json DEFAULT NULL,
    `error_message` text DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`gateway_id`) REFERENCES `payment_gateways` (`id`),
    FOREIGN KEY (`transaction_id`) REFERENCES `payment_transactions` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء الفهارس
CREATE INDEX idx_payment_transaction_status ON payment_transactions(status);
CREATE INDEX idx_payment_dates ON payment_transactions(created_at, processed_at);
CREATE INDEX idx_payment_receipt ON payment_transactions(receipt_number);
CREATE INDEX idx_zain_cash_phone ON payment_transactions(zain_cash_phone);
CREATE INDEX idx_tabadul_order ON payment_transactions(tabadul_order_id);
CREATE INDEX idx_cash_receipt_number ON cash_receipts(receipt_number);
CREATE INDEX idx_cash_receipt_date ON cash_receipts(payment_date);
CREATE INDEX idx_api_logs_date ON payment_api_logs(created_at);

-- إدخال بيانات بوابات الدفع
INSERT INTO `payment_gateways` (`name`, `code`, `provider`, `settings`, `instructions`, `is_active`) VALUES
('زين كاش', 'zain_cash', 'zain_cash', 
'{
    "merchant_id": "61a75c42dff23f6a9e97a97e",
    "secret": "$2y$10$BeNhCt4fpq4sLgE9ZJf1suGN/87TtvJYk0TMxYjwMvCaDGAzLidZ6",
    "phone": "9647800533950",
    "environment": "production",
    "language": "ar",
    "currency": "IQD"
}', 
'قم بتحويل المبلغ عبر تطبيق زين كاش على الرقم 9647800533950', 
1),

('تبادل', 'tabadul', 'tabadul', 
'{
    "test": {
        "merchant_username": "phoenix_api",
        "merchant_password": "Phoenix@1234",
        "api_url": "https://epgtest.tabadul.iq:9444/epg/rest/",
        "gui_username": "phoenix_merch",
        "gui_password": "Phoenix@1234",
        "gui_url": "https://epgtest.tabadul.iq:9444/epg_gui/"
    },
    "production": {
        "api_url": "https://epg.tabadul.iq/epg/rest/",
        "merchant_username": "",
        "merchant_password": ""
    },
    "test_card": {
        "number": "4222450000980046",
        "expiry": "07/25",
        "cvv": "160"
    },
    "currency": "368",
    "language": "ar"
}',
'يمكنك الدفع باستخدام بطاقة الدفع الإلكتروني عبر بوابة تبادل',
1),

('محفظة qi', 'qi_wallet', 'qi', '{"merchant_id": "YOUR_MERCHANT_ID"}', 'قم بالدفع عبر محفظة qi.iq', 1),
('محفظة fib', 'fib_wallet', 'fib', '{"merchant_id": "YOUR_MERCHANT_ID"}', 'قم بالدفع عبر محفظة fib.iq', 1),
('دفع نقدي', 'cash', 'manual', NULL, 'يمكنك الدفع نقداً في مقر الشركة', 1); 