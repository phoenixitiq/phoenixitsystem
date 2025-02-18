-- إنشاء جدول المستخدمين
CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin', 'super-admin') DEFAULT 'user',
    status BOOLEAN DEFAULT TRUE,
    remember_token VARCHAR(100),
    email_verified_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول الإعدادات
CREATE TABLE IF NOT EXISTS settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    key VARCHAR(255) NOT NULL UNIQUE,
    value TEXT,
    group_name VARCHAR(100) DEFAULT 'general',
    is_system BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول النشاطات
CREATE TABLE IF NOT EXISTS activities (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    action VARCHAR(255) NOT NULL,
    description TEXT,
    model_type VARCHAR(255) NULL,
    model_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول النسخ الاحتياطية
CREATE TABLE IF NOT EXISTS backups (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    size BIGINT UNSIGNED,
    type ENUM('full', 'database', 'files') DEFAULT 'full',
    status ENUM('pending', 'running', 'completed', 'failed') DEFAULT 'pending',
    created_by BIGINT UNSIGNED,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول سجلات المزامنة
CREATE TABLE IF NOT EXISTS sync_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    service VARCHAR(100) NOT NULL,
    status ENUM('success', 'failed') NOT NULL,
    message TEXT,
    started_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول سجلات النظام
CREATE TABLE IF NOT EXISTS system_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    level VARCHAR(20) NOT NULL,
    message TEXT NOT NULL,
    context JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول الأقسام
CREATE TABLE IF NOT EXISTS departments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    manager_id BIGINT UNSIGNED NULL,
    parent_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (manager_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (parent_id) REFERENCES departments(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول الموظفين
CREATE TABLE IF NOT EXISTS employees (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    department_id BIGINT UNSIGNED NULL,
    position VARCHAR(255),
    employee_number VARCHAR(50) UNIQUE,
    join_date DATE,
    salary DECIMAL(10, 2),
    status ENUM('active', 'on_leave', 'terminated') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول الحضور والانصراف
CREATE TABLE IF NOT EXISTS attendance_records (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    employee_id BIGINT UNSIGNED NOT NULL,
    check_in TIMESTAMP NULL,
    check_out TIMESTAMP NULL,
    work_hours DECIMAL(5,2) NULL,
    status ENUM('present', 'absent', 'late', 'early_leave') DEFAULT 'present',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول الإجازات
CREATE TABLE IF NOT EXISTS leaves (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    employee_id BIGINT UNSIGNED NOT NULL,
    type ENUM('annual', 'sick', 'emergency', 'unpaid') NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    days_count INT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    reason TEXT,
    approved_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول المهام
CREATE TABLE IF NOT EXISTS tasks (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    assigned_to BIGINT UNSIGNED NULL,
    assigned_by BIGINT UNSIGNED NULL,
    due_date DATETIME NULL,
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    status ENUM('pending', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (assigned_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول تقييمات الأداء
CREATE TABLE IF NOT EXISTS performance_reviews (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    employee_id BIGINT UNSIGNED NOT NULL,
    reviewer_id BIGINT UNSIGNED NOT NULL,
    review_period VARCHAR(50) NOT NULL,
    rating DECIMAL(3,2) NOT NULL,
    strengths TEXT,
    improvements TEXT,
    goals TEXT,
    status ENUM('draft', 'submitted', 'approved') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewer_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول العملاء
CREATE TABLE IF NOT EXISTS clients (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE,
    phone VARCHAR(20),
    company_name VARCHAR(255),
    address TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    source VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول الخدمات
CREATE TABLE IF NOT EXISTS services (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    name_en VARCHAR(255),
    slug VARCHAR(255) UNIQUE,
    description TEXT,
    description_en TEXT,
    category VARCHAR(100),
    price DECIMAL(10, 2),
    duration INT,
    is_active BOOLEAN DEFAULT TRUE,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول مميزات الخدمات
CREATE TABLE IF NOT EXISTS service_features (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    service_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    title_en VARCHAR(255),
    description TEXT,
    description_en TEXT,
    is_featured BOOLEAN DEFAULT FALSE,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول المشاريع
CREATE TABLE IF NOT EXISTS projects (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    client_id BIGINT UNSIGNED NOT NULL,
    service_id BIGINT UNSIGNED NOT NULL,
    manager_id BIGINT UNSIGNED NULL,
    start_date DATE,
    end_date DATE,
    budget DECIMAL(12, 2),
    status ENUM('pending', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    progress INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    FOREIGN KEY (manager_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول فريق المشروع
CREATE TABLE IF NOT EXISTS project_members (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    project_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    role VARCHAR(100),
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول مهام المشروع
CREATE TABLE IF NOT EXISTS project_tasks (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    project_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    assigned_to BIGINT UNSIGNED NULL,
    start_date DATE,
    due_date DATE,
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
    progress INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول الحملات التسويقية
CREATE TABLE IF NOT EXISTS marketing_campaigns (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    start_date DATE,
    end_date DATE,
    budget DECIMAL(12, 2),
    status ENUM('draft', 'active', 'paused', 'completed') DEFAULT 'draft',
    platform VARCHAR(100),
    target_audience TEXT,
    kpis TEXT,
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول المحتوى التسويقي
CREATE TABLE IF NOT EXISTS marketing_content (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    campaign_id BIGINT UNSIGNED NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT,
    content_type ENUM('post', 'article', 'video', 'image') NOT NULL,
    platform VARCHAR(100),
    scheduled_at DATETIME,
    published_at DATETIME NULL,
    status ENUM('draft', 'scheduled', 'published') DEFAULT 'draft',
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (campaign_id) REFERENCES marketing_campaigns(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول تحليلات التسويق
CREATE TABLE IF NOT EXISTS marketing_analytics (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    campaign_id BIGINT UNSIGNED NULL,
    content_id BIGINT UNSIGNED NULL,
    platform VARCHAR(100),
    impressions INT DEFAULT 0,
    clicks INT DEFAULT 0,
    engagement INT DEFAULT 0,
    conversions INT DEFAULT 0,
    date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (campaign_id) REFERENCES marketing_campaigns(id) ON DELETE CASCADE,
    FOREIGN KEY (content_id) REFERENCES marketing_content(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول الفرص البيعية
CREATE TABLE IF NOT EXISTS sales_opportunities (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    client_id BIGINT UNSIGNED NOT NULL,
    service_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    value DECIMAL(12, 2),
    status ENUM('new', 'contacted', 'proposal', 'negotiation', 'won', 'lost') DEFAULT 'new',
    source VARCHAR(100),
    assigned_to BIGINT UNSIGNED NULL,
    expected_close_date DATE,
    actual_close_date DATE NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول المتابعات البيعية
CREATE TABLE IF NOT EXISTS sales_followups (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    opportunity_id BIGINT UNSIGNED NOT NULL,
    type ENUM('call', 'email', 'meeting', 'note') NOT NULL,
    description TEXT,
    scheduled_at DATETIME,
    completed_at DATETIME NULL,
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (opportunity_id) REFERENCES sales_opportunities(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول الفواتير
CREATE TABLE IF NOT EXISTS invoices (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    invoice_number VARCHAR(50) UNIQUE NOT NULL,
    client_id BIGINT UNSIGNED NOT NULL,
    project_id BIGINT UNSIGNED NULL,
    amount DECIMAL(12, 2) NOT NULL,
    tax_amount DECIMAL(12, 2) DEFAULT 0,
    total_amount DECIMAL(12, 2) NOT NULL,
    status ENUM('draft', 'sent', 'paid', 'overdue', 'cancelled') DEFAULT 'draft',
    due_date DATE NOT NULL,
    notes TEXT,
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول بنود الفاتورة
CREATE TABLE IF NOT EXISTS invoice_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    invoice_id BIGINT UNSIGNED NOT NULL,
    service_id BIGINT UNSIGNED NULL,
    description TEXT NOT NULL,
    quantity DECIMAL(10, 2) NOT NULL DEFAULT 1,
    unit_price DECIMAL(12, 2) NOT NULL,
    amount DECIMAL(12, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول المدفوعات
CREATE TABLE IF NOT EXISTS payments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    invoice_id BIGINT UNSIGNED NOT NULL,
    amount DECIMAL(12, 2) NOT NULL,
    payment_method ENUM('cash', 'bank_transfer', 'credit_card', 'other') NOT NULL,
    payment_date DATE NOT NULL,
    transaction_id VARCHAR(100),
    notes TEXT,
    status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول المصروفات
CREATE TABLE IF NOT EXISTS expenses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    amount DECIMAL(12, 2) NOT NULL,
    category VARCHAR(100) NOT NULL,
    expense_date DATE NOT NULL,
    payment_method ENUM('cash', 'bank_transfer', 'credit_card', 'other') NOT NULL,
    receipt_number VARCHAR(100),
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_by BIGINT UNSIGNED NULL,
    approved_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول التقارير المالية
CREATE TABLE IF NOT EXISTS financial_reports (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    report_type ENUM('income', 'expenses', 'profit_loss', 'tax') NOT NULL,
    period_start DATE NOT NULL,
    period_end DATE NOT NULL,
    total_amount DECIMAL(15, 2) NOT NULL,
    status ENUM('draft', 'final') DEFAULT 'draft',
    notes TEXT,
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول المنتجات
CREATE TABLE IF NOT EXISTS products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    name_en VARCHAR(255),
    slug VARCHAR(255) UNIQUE,
    description TEXT,
    description_en TEXT,
    category VARCHAR(100),
    price DECIMAL(10, 2),
    cost DECIMAL(10, 2),
    sku VARCHAR(100) UNIQUE,
    stock INT DEFAULT 0,
    min_stock INT DEFAULT 5,
    is_active BOOLEAN DEFAULT TRUE,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول حركات المخزون
CREATE TABLE IF NOT EXISTS inventory_movements (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    type ENUM('in', 'out') NOT NULL,
    quantity INT NOT NULL,
    reference_type VARCHAR(100),
    reference_id BIGINT UNSIGNED,
    notes TEXT,
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول طلبات الشراء
CREATE TABLE IF NOT EXISTS purchase_orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    supplier_id BIGINT UNSIGNED NOT NULL,
    total_amount DECIMAL(12, 2) NOT NULL,
    status ENUM('draft', 'pending', 'approved', 'received', 'cancelled') DEFAULT 'draft',
    notes TEXT,
    created_by BIGINT UNSIGNED NULL,
    approved_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول بنود طلبات الشراء
CREATE TABLE IF NOT EXISTS purchase_order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    purchase_order_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(12, 2) NOT NULL,
    total_price DECIMAL(12, 2) NOT NULL,
    received_quantity INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (purchase_order_id) REFERENCES purchase_orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول التقييمات
CREATE TABLE IF NOT EXISTS reviews (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    reviewable_type VARCHAR(255) NOT NULL,
    reviewable_id BIGINT UNSIGNED NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    title VARCHAR(255),
    comment TEXT,
    is_approved BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول تذاكر الدعم الفني
CREATE TABLE IF NOT EXISTS support_tickets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ticket_number VARCHAR(50) UNIQUE NOT NULL,
    client_id BIGINT UNSIGNED NOT NULL,
    subject VARCHAR(255) NOT NULL,
    description TEXT,
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    status ENUM('new', 'open', 'pending', 'resolved', 'closed') DEFAULT 'new',
    category VARCHAR(100),
    assigned_to BIGINT UNSIGNED NULL,
    resolved_at DATETIME NULL,
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول ردود التذاكر
CREATE TABLE IF NOT EXISTS ticket_replies (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ticket_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NULL,
    message TEXT NOT NULL,
    is_private BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES support_tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول مرفقات التذاكر
CREATE TABLE IF NOT EXISTS ticket_attachments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ticket_id BIGINT UNSIGNED NOT NULL,
    reply_id BIGINT UNSIGNED NULL,
    filename VARCHAR(255) NOT NULL,
    filepath TEXT NOT NULL,
    filesize INT NOT NULL,
    mime_type VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES support_tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (reply_id) REFERENCES ticket_replies(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول قاعدة المعرفة
CREATE TABLE IF NOT EXISTS knowledge_base (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    title_en VARCHAR(255),
    slug VARCHAR(255) UNIQUE,
    content TEXT,
    content_en TEXT,
    category VARCHAR(100),
    is_public BOOLEAN DEFAULT TRUE,
    views INT DEFAULT 0,
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول التعليقات على قاعدة المعرفة
CREATE TABLE IF NOT EXISTS knowledge_base_comments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    article_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NULL,
    comment TEXT NOT NULL,
    is_helpful BOOLEAN DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES knowledge_base(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول الإشعارات
CREATE TABLE IF NOT EXISTS notifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    type VARCHAR(255) NOT NULL,
    data JSON,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول الرسائل
CREATE TABLE IF NOT EXISTS messages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sender_id BIGINT UNSIGNED NOT NULL,
    receiver_id BIGINT UNSIGNED NOT NULL,
    subject VARCHAR(255),
    message TEXT NOT NULL,
    read_at TIMESTAMP NULL,
    deleted_by_sender BOOLEAN DEFAULT FALSE,
    deleted_by_receiver BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول مرفقات الرسائل
CREATE TABLE IF NOT EXISTS message_attachments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    message_id BIGINT UNSIGNED NOT NULL,
    filename VARCHAR(255) NOT NULL,
    filepath TEXT NOT NULL,
    filesize INT NOT NULL,
    mime_type VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (message_id) REFERENCES messages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول المحادثات
CREATE TABLE IF NOT EXISTS chat_rooms (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    type ENUM('direct', 'group') NOT NULL,
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول أعضاء المحادثات
CREATE TABLE IF NOT EXISTS chat_room_members (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    room_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    role ENUM('admin', 'member') DEFAULT 'member',
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_read_at TIMESTAMP NULL,
    FOREIGN KEY (room_id) REFERENCES chat_rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول رسائل المحادثات
CREATE TABLE IF NOT EXISTS chat_messages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    room_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    message TEXT NOT NULL,
    type ENUM('text', 'image', 'file') DEFAULT 'text',
    file_url VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (room_id) REFERENCES chat_rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول الأدوار
CREATE TABLE IF NOT EXISTS roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    name_ar VARCHAR(100),
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    is_system BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول الصلاحيات
CREATE TABLE IF NOT EXISTS permissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    name_ar VARCHAR(100),
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    module VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول العلاقة بين الأدوار والصلاحيات
CREATE TABLE IF NOT EXISTS role_permissions (
    role_id BIGINT UNSIGNED NOT NULL,
    permission_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول سجلات تسجيل الدخول
CREATE TABLE IF NOT EXISTS login_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    status ENUM('success', 'failed') NOT NULL,
    message VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول محاولات تسجيل الدخول الفاشلة
CREATE TABLE IF NOT EXISTS failed_login_attempts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255),
    ip_address VARCHAR(45),
    attempts INT DEFAULT 1,
    last_attempt_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    blocked_until TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول رموز التحقق
CREATE TABLE IF NOT EXISTS verification_codes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    code VARCHAR(6) NOT NULL,
    type ENUM('email', 'phone', 'password_reset') NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    used_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إدخال البيانات الأولية
INSERT INTO users (name, email, password, role) VALUES 
('مدير النظام', 'admin@phoenixitiq.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

INSERT INTO settings (key, value, group_name, is_system) VALUES 
('site_name', 'Phoenix IT', 'general', true),
('site_description', 'نظام إدارة الخدمات التقنية', 'general', true),
('backup_enabled', '1', 'backup', true),
('backup_frequency', 'daily', 'backup', true),
('mail_enabled', '1', 'mail', true),
('system_version', '1.0.0', 'system', true);

-- إنشاء الفهارس
CREATE INDEX idx_activities_user ON activities(user_id);
CREATE INDEX idx_activities_model ON activities(model_type, model_id);
CREATE INDEX idx_settings_group ON settings(group_name);
CREATE INDEX idx_backups_status ON backups(status);
CREATE INDEX idx_sync_logs_service ON sync_logs(service);
CREATE INDEX idx_system_logs_level ON system_logs(level);
CREATE INDEX idx_employees_department ON employees(department_id);
CREATE INDEX idx_attendance_employee ON attendance_records(employee_id);
CREATE INDEX idx_leaves_employee ON leaves(employee_id);
CREATE INDEX idx_tasks_assigned ON tasks(assigned_to);
CREATE INDEX idx_performance_employee ON performance_reviews(employee_id);
CREATE INDEX idx_clients_status ON clients(status);
CREATE INDEX idx_services_category ON services(category);
CREATE INDEX idx_services_active ON services(is_active);
CREATE INDEX idx_projects_client ON projects(client_id);
CREATE INDEX idx_projects_service ON projects(service_id);
CREATE INDEX idx_projects_status ON projects(status);
CREATE INDEX idx_project_tasks_project ON project_tasks(project_id);
CREATE INDEX idx_project_members_project ON project_members(project_id);
CREATE INDEX idx_campaigns_status ON marketing_campaigns(status);
CREATE INDEX idx_content_campaign ON marketing_content(campaign_id);
CREATE INDEX idx_content_status ON marketing_content(status);
CREATE INDEX idx_analytics_campaign ON marketing_analytics(campaign_id);
CREATE INDEX idx_analytics_content ON marketing_analytics(content_id);
CREATE INDEX idx_analytics_date ON marketing_analytics(date);
CREATE INDEX idx_opportunities_client ON sales_opportunities(client_id);
CREATE INDEX idx_opportunities_service ON sales_opportunities(service_id);
CREATE INDEX idx_opportunities_status ON sales_opportunities(status);
CREATE INDEX idx_followups_opportunity ON sales_followups(opportunity_id);
CREATE INDEX idx_invoices_client ON invoices(client_id);
CREATE INDEX idx_invoices_project ON invoices(project_id);
CREATE INDEX idx_invoices_status ON invoices(status);
CREATE INDEX idx_invoice_items_invoice ON invoice_items(invoice_id);
CREATE INDEX idx_invoice_items_service ON invoice_items(service_id);
CREATE INDEX idx_payments_invoice ON payments(invoice_id);
CREATE INDEX idx_payments_status ON payments(status);
CREATE INDEX idx_expenses_category ON expenses(category);
CREATE INDEX idx_expenses_date ON expenses(expense_date);
CREATE INDEX idx_financial_reports_type ON financial_reports(report_type);
CREATE INDEX idx_financial_reports_period ON financial_reports(period_start, period_end);
CREATE INDEX idx_products_category ON products(category);
CREATE INDEX idx_products_active ON products(is_active);
CREATE INDEX idx_inventory_product ON inventory_movements(product_id);
CREATE INDEX idx_inventory_reference ON inventory_movements(reference_type, reference_id);
CREATE INDEX idx_purchase_orders_supplier ON purchase_orders(supplier_id);
CREATE INDEX idx_purchase_orders_status ON purchase_orders(status);
CREATE INDEX idx_purchase_items_order ON purchase_order_items(purchase_order_id);
CREATE INDEX idx_purchase_items_product ON purchase_order_items(product_id);
CREATE INDEX idx_reviews_user ON reviews(user_id);
CREATE INDEX idx_reviews_reviewable ON reviews(reviewable_type, reviewable_id);
CREATE INDEX idx_tickets_client ON support_tickets(client_id);
CREATE INDEX idx_tickets_assigned ON support_tickets(assigned_to);
CREATE INDEX idx_tickets_status ON support_tickets(status);
CREATE INDEX idx_tickets_category ON support_tickets(category);
CREATE INDEX idx_ticket_replies_ticket ON ticket_replies(ticket_id);
CREATE INDEX idx_ticket_attachments_ticket ON ticket_attachments(ticket_id);
CREATE INDEX idx_knowledge_base_category ON knowledge_base(category);
CREATE INDEX idx_knowledge_base_public ON knowledge_base(is_public);
CREATE INDEX idx_knowledge_comments_article ON knowledge_base_comments(article_id);
CREATE INDEX idx_notifications_user ON notifications(user_id);
CREATE INDEX idx_notifications_read ON notifications(read_at);
CREATE INDEX idx_messages_sender ON messages(sender_id);
CREATE INDEX idx_messages_receiver ON messages(receiver_id);
CREATE INDEX idx_messages_read ON messages(read_at);
CREATE INDEX idx_message_attachments_message ON message_attachments(message_id);
CREATE INDEX idx_chat_rooms_type ON chat_rooms(type);
CREATE INDEX idx_chat_members_room ON chat_room_members(room_id);
CREATE INDEX idx_chat_members_user ON chat_room_members(user_id);
CREATE INDEX idx_chat_messages_room ON chat_messages(room_id);
CREATE INDEX idx_chat_messages_user ON chat_messages(user_id);
CREATE INDEX idx_chat_messages_created ON chat_messages(created_at);
CREATE INDEX idx_roles_slug ON roles(slug);
CREATE INDEX idx_permissions_slug ON permissions(slug);
CREATE INDEX idx_permissions_module ON permissions(module);
CREATE INDEX idx_login_logs_user ON login_logs(user_id);
CREATE INDEX idx_login_logs_status ON login_logs(status);
CREATE INDEX idx_failed_attempts_email ON failed_login_attempts(email);
CREATE INDEX idx_failed_attempts_ip ON failed_login_attempts(ip_address);
CREATE INDEX idx_verification_codes_user ON verification_codes(user_id);
CREATE INDEX idx_verification_codes_code ON verification_codes(code);

-- إدخال بيانات أولية للأقسام
INSERT INTO departments (name, description) VALUES 
('تطوير البرمجيات', 'قسم تطوير وصيانة البرمجيات'),
('التسويق الرقمي', 'قسم إدارة التسويق والحملات الرقمية'),
('خدمة العملاء', 'قسم دعم ومساعدة العملاء'),
('الموارد البشرية', 'قسم إدارة شؤون الموظفين');

-- إدخال بيانات أولية للخدمات
INSERT INTO services (name, name_en, slug, category, price, duration) VALUES 
('تصميم وتطوير المواقع', 'Web Development', 'web-development', 'development', 5000, 30),
('تطوير تطبيقات الجوال', 'Mobile Apps', 'mobile-apps', 'development', 8000, 45),
('التسويق الرقمي', 'Digital Marketing', 'digital-marketing', 'marketing', 3000, 30),
('إدارة وسائل التواصل', 'Social Media', 'social-media', 'marketing', 2000, 30);

-- إدخال بيانات أولية للحملات التسويقية
INSERT INTO marketing_campaigns (name, description, platform, status, created_by) VALUES 
('حملة رمضان 2024', 'حملة تسويقية خلال شهر رمضان', 'social_media', 'draft', 1),
('عروض نهاية العام', 'عروض وتخفيضات نهاية العام', 'email', 'draft', 1);

-- إدخال بيانات أولية للمصروفات
INSERT INTO expenses (title, description, amount, category, expense_date, payment_method, created_by) VALUES 
('إيجار المكتب', 'إيجار شهر يناير 2024', 5000.00, 'rent', '2024-01-01', 'bank_transfer', 1),
('فواتير الكهرباء', 'فاتورة شهر يناير 2024', 1200.00, 'utilities', '2024-01-15', 'bank_transfer', 1);

-- إدخال بيانات أولية للمنتجات
INSERT INTO products (name, name_en, slug, category, price, cost, sku, stock) VALUES 
('استضافة المواقع - خطة أساسية', 'Web Hosting - Basic Plan', 'web-hosting-basic', 'hosting', 500, 200, 'HOST-001', 100),
('استضافة المواقع - خطة متقدمة', 'Web Hosting - Pro Plan', 'web-hosting-pro', 'hosting', 1000, 400, 'HOST-002', 100),
('تصميم شعار احترافي', 'Professional Logo Design', 'logo-design', 'design', 800, 0, 'DESIGN-001', 999),
('قالب موقع جاهز', 'Website Template', 'website-template', 'templates', 300, 0, 'TEMP-001', 999);

-- إدخال بيانات أولية لقاعدة المعرفة
INSERT INTO knowledge_base (title, title_en, slug, category, content, content_en, created_by) VALUES 
('كيفية إنشاء موقع جديد', 'How to Create a New Website', 'how-to-create-website', 'websites', 'محتوى المقال بالعربية', 'Article content in English', 1),
('طريقة إعداد البريد الإلكتروني', 'How to Setup Email', 'email-setup-guide', 'email', 'محتوى المقال بالعربية', 'Article content in English', 1);

-- إدخال بيانات أولية للمحادثات
INSERT INTO chat_rooms (name, type, created_by) VALUES 
('غرفة المناقشات العامة', 'group', 1),
('غرفة الدعم الفني', 'group', 1);

-- إدخال بيانات أولية لأعضاء المحادثات
INSERT INTO chat_room_members (room_id, user_id, role) VALUES 
(1, 1, 'admin'),
(2, 1, 'admin');

-- إدخال بيانات أولية للأدوار
INSERT INTO roles (name, name_ar, slug, description, is_system) VALUES 
('Super Admin', 'مدير النظام', 'super-admin', 'كامل الصلاحيات للنظام', true),
('Admin', 'مشرف', 'admin', 'صلاحيات إدارية', true),
('Manager', 'مدير', 'manager', 'صلاحيات إدارة القسم', false),
('Employee', 'موظف', 'employee', 'صلاحيات الموظف العادي', false);

-- إدخال بيانات أولية للصلاحيات
INSERT INTO permissions (name, name_ar, slug, module) VALUES 
('View Dashboard', 'عرض لوحة التحكم', 'view-dashboard', 'dashboard'),
('Manage Users', 'إدارة المستخدمين', 'manage-users', 'users'),
('View Reports', 'عرض التقارير', 'view-reports', 'reports'),
('Manage Settings', 'إدارة الإعدادات', 'manage-settings', 'settings');

-- إدخال بيانات أولية للعلاقة بين الأدوار والصلاحيات
INSERT INTO role_permissions (role_id, permission_id) 
SELECT r.id, p.id 
FROM roles r 
CROSS JOIN permissions p 
WHERE r.slug = 'super-admin';

-- إنشاء جدول فترات الدوام
CREATE TABLE IF NOT EXISTS work_shifts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    name_en VARCHAR(100),
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    hours_per_day DECIMAL(4,2) NOT NULL,
    break_duration INT DEFAULT 60, -- مدة الاستراحة بالدقائق
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول عقود الموظفين
CREATE TABLE IF NOT EXISTS employee_contracts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    employee_id BIGINT UNSIGNED NOT NULL,
    contract_number VARCHAR(50) UNIQUE,
    contract_type ENUM('full_time', 'part_time', 'temporary', 'internship') NOT NULL,
    work_shift_id BIGINT UNSIGNED NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NULL, -- NULL للعقود غير محددة المدة
    base_salary DECIMAL(12, 2) NOT NULL,
    hourly_rate DECIMAL(10, 2), -- معدل الأجر بالساعة للعمل الإضافي
    weekly_hours INT NOT NULL DEFAULT 40, -- ساعات العمل الأسبوعية
    annual_leave_days INT NOT NULL DEFAULT 28, -- أيام الإجازة السنوية (حسب القانون البريطاني)
    sick_leave_days INT NOT NULL DEFAULT 28, -- أيام الإجازة المرضية
    notice_period INT NOT NULL DEFAULT 30, -- فترة الإشعار بالأيام
    probation_period INT DEFAULT 90, -- فترة التجربة بالأيام
    status ENUM('active', 'expired', 'terminated') DEFAULT 'active',
    termination_date DATE NULL,
    termination_reason TEXT NULL,
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    FOREIGN KEY (work_shift_id) REFERENCES work_shifts(id) ON DELETE RESTRICT,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول العمل الإضافي
CREATE TABLE IF NOT EXISTS overtime_records (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    employee_id BIGINT UNSIGNED NOT NULL,
    date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    hours DECIMAL(4,2) NOT NULL,
    rate DECIMAL(10, 2) NOT NULL, -- معدل الأجر للساعة الإضافية
    reason TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    approved_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إضافة فهارس جديدة
CREATE INDEX idx_work_shifts_active ON work_shifts(is_active);
CREATE INDEX idx_contracts_employee ON employee_contracts(employee_id);
CREATE INDEX idx_contracts_status ON employee_contracts(status);
CREATE INDEX idx_overtime_employee ON overtime_records(employee_id);
CREATE INDEX idx_overtime_date ON overtime_records(date);
CREATE INDEX idx_overtime_status ON overtime_records(status);

-- إدخال بيانات أولية لفترات الدوام
INSERT INTO work_shifts (name, name_en, start_time, end_time, hours_per_day) VALUES 
('الفترة الصباحية', 'Morning Shift', '11:00', '19:00', 8),
('فترة ما بعد الظهر', 'Afternoon Shift', '13:00', '21:00', 8),
('الفترة المسائية', 'Evening Shift', '14:00', '20:00', 6);

-- إنشاء جدول نماذج العقود
CREATE TABLE IF NOT EXISTS contract_templates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type ENUM('employment', 'subscription') NOT NULL,
    content_ar TEXT NOT NULL,
    content_en TEXT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إدخال نموذج عقد العمل باللغتين
INSERT INTO contract_templates (name, type, content_ar, content_en, created_by) VALUES 
('عقد عمل موظف', 'employment',
'عقد عمل

تم إبرام هذا العقد في {contract_date} بين كل من:

الطرف الأول: شركة {company_name}، سجل تجاري رقم {company_reg_no}، وعنوانها {company_address}، ويمثلها {company_representative} بصفته {representative_title} (ويشار إليها فيما يلي بـ "الشركة")

و

الطرف الثاني: السيد/ة {employee_name}، الجنسية {nationality}، يحمل هوية/جواز سفر رقم {id_number} (ويشار إليه فيما يلي بـ "الموظف")

1. طبيعة العمل والمنصب:
- يعين الموظف بمنصب {position} في قسم {department}
- يخضع الموظف لفترة تجربة مدتها {probation_period} يوماً
- ساعات العمل: {weekly_hours} ساعة أسبوعياً
- فترة الدوام: من الساعة {start_time} إلى الساعة {end_time}
- فترة الراحة: {break_duration} دقيقة يومياً

2. الراتب والمزايا:
- الراتب الأساسي: {base_salary} شهرياً
- بدل السكن: {housing_allowance} سنوياً
- بدل المواصلات: {transport_allowance} شهرياً
- معدل العمل الإضافي: {overtime_rate} للساعة

3. الإجازات:
- الإجازة السنوية: {annual_leave_days} يوم عمل مدفوعة الأجر
- الإجازة المرضية: {sick_leave_days} يوم مدفوعة الأجر
- إجازة الأمومة: وفقاً لقانون العمل
- العطلات الرسمية: مدفوعة الأجر حسب التقويم المعتمد

4. التأمينات والمزايا:
- التأمين الصحي: للموظف وعائلته حسب سياسة الشركة
- مكافأة نهاية الخدمة: وفقاً لقانون العمل
- المساهمة في صندوق التقاعد: حسب النظام المعمول به

5. إنهاء الخدمة:
- فترة الإشعار: {notice_period} يوماً
- يحق للشركة إنهاء العقد في حالات المخالفات الجسيمة
- يستحق الموظف مكافأة نهاية الخدمة حسب القانون

6. السرية وعدم المنافسة:
- يلتزم الموظف بالحفاظ على أسرار العمل
- يتعهد بعدم المنافسة لمدة سنة بعد انتهاء العقد

توقيع الطرف الأول: ____________
توقيع الطرف الثاني: ____________',

'EMPLOYMENT CONTRACT

This contract is made on {contract_date} between:

First Party: {company_name}, Commercial Registration No. {company_reg_no}, located at {company_address}, represented by {company_representative} as {representative_title} (hereinafter referred to as the "Company")

And

Second Party: Mr./Ms. {employee_name}, Nationality: {nationality}, ID/Passport No. {id_number} (hereinafter referred to as the "Employee")

1. Nature of Work and Position:
- Position: {position} in {department}
- Probation Period: {probation_period} days
- Working Hours: {weekly_hours} hours per week
- Work Shift: {start_time} to {end_time}
- Break Duration: {break_duration} minutes daily

2. Salary and Benefits:
- Basic Salary: {base_salary} monthly
- Housing Allowance: {housing_allowance} annually
- Transport Allowance: {transport_allowance} monthly
- Overtime Rate: {overtime_rate} per hour

3. Leave Entitlements:
- Annual Leave: {annual_leave_days} working days paid
- Sick Leave: {sick_leave_days} days paid
- Maternity Leave: As per Labor Law
- Public Holidays: Paid as per approved calendar

4. Insurance and Benefits:
- Health Insurance: For employee and family as per company policy
- End of Service Benefits: As per Labor Law
- Pension Contribution: As per applicable system

5. Termination:
- Notice Period: {notice_period} days
- Company may terminate for gross misconduct
- End of service benefits as per law

6. Confidentiality and Non-Competition:
- Employee shall maintain work secrets
- Non-compete for one year after contract end

First Party Signature: ____________
Second Party Signature: ____________');

-- إدخال نموذج عقد الاشتراك باللغتين
INSERT INTO contract_templates (name, type, content_ar, content_en, created_by) VALUES 
('عقد اشتراك خدمات', 'subscription',
'عقد اشتراك خدمات

تم إبرام هذا العقد في {contract_date} بين كل من:

الطرف الأول: شركة {company_name}، (ويشار إليها فيما يلي بـ "مزود الخدمة")

و

الطرف الثاني: {client_name}، (ويشار إليه فيما يلي بـ "المشترك")

1. الخدمات المقدمة:
- نوع الخدمة: {service_name}
- وصف الخدمة: {service_description}
- مدة الاشتراك: {subscription_duration} شهر
- تاريخ بدء الخدمة: {start_date}

2. الرسوم والدفع:
- قيمة الاشتراك: {subscription_fee} شهرياً/سنوياً
- طريقة الدفع: {payment_method}
- موعد الدفع: {payment_date} من كل شهر/سنة

3. التزامات مزود الخدمة:
- تقديم الخدمة بالجودة المتفق عليها
- توفير الدعم الفني خلال ساعات العمل
- الحفاظ على سرية بيانات المشترك

4. التزامات المشترك:
- سداد الرسوم في موعدها
- الاستخدام القانوني للخدمة
- عدم مشاركة حساب الخدمة مع الغير

5. إنهاء العقد:
- فترة الإشعار: {notice_period} يوماً
- شروط الإلغاء والاسترداد
- حالات الإنهاء الفوري

توقيع الطرف الأول: ____________
توقيع الطرف الثاني: ____________',

'SERVICE SUBSCRIPTION CONTRACT

This contract is made on {contract_date} between:

First Party: {company_name}, (hereinafter referred to as the "Service Provider")

And

Second Party: {client_name}, (hereinafter referred to as the "Subscriber")

1. Services Provided:
- Service Type: {service_name}
- Service Description: {service_description}
- Subscription Duration: {subscription_duration} months
- Service Start Date: {start_date}

2. Fees and Payment:
- Subscription Fee: {subscription_fee} monthly/annually
- Payment Method: {payment_method}
- Payment Date: {payment_date} of each month/year

3. Service Provider Obligations:
- Provide service at agreed quality
- Provide technical support during business hours
- Maintain subscriber data confidentiality

4. Subscriber Obligations:
- Pay fees on time
- Legal use of service
- No sharing of service account

5. Contract Termination:
- Notice Period: {notice_period} days
- Cancellation and refund terms
- Immediate termination cases

First Party Signature: ____________
Second Party Signature: ____________');

-- إضافة فهارس
CREATE INDEX idx_contract_templates_type ON contract_templates(type);
CREATE INDEX idx_contract_templates_active ON contract_templates(is_active);

-- إنشاء جدول الرواتب
CREATE TABLE IF NOT EXISTS salary_payments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    employee_id BIGINT UNSIGNED NOT NULL,
    payment_type ENUM('full', 'half', 'advance') NOT NULL,
    amount DECIMAL(12, 2) NOT NULL,
    payment_date DATE NOT NULL,
    payment_period VARCHAR(7) NOT NULL, -- Format: YYYY-MM
    payment_method ENUM('bank_transfer', 'cash', 'cheque') NOT NULL,
    reference_number VARCHAR(100),
    notes TEXT,
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    created_by BIGINT UNSIGNED NULL,
    approved_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول السلف والقروض
CREATE TABLE IF NOT EXISTS salary_advances (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    employee_id BIGINT UNSIGNED NOT NULL,
    amount DECIMAL(12, 2) NOT NULL,
    request_date DATE NOT NULL,
    reason TEXT,
    repayment_months INT DEFAULT 1, -- عدد أشهر السداد
    monthly_deduction DECIMAL(12, 2), -- قيمة الخصم الشهري
    remaining_amount DECIMAL(12, 2), -- المبلغ المتبقي
    status ENUM('pending', 'approved', 'rejected', 'completed') DEFAULT 'pending',
    approved_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول سداد السلف
CREATE TABLE IF NOT EXISTS advance_repayments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    advance_id BIGINT UNSIGNED NOT NULL,
    amount DECIMAL(12, 2) NOT NULL,
    payment_date DATE NOT NULL,
    payment_method ENUM('salary_deduction', 'cash', 'bank_transfer') NOT NULL,
    notes TEXT,
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (advance_id) REFERENCES salary_advances(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول تفاصيل الراتب
CREATE TABLE IF NOT EXISTS salary_details (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    payment_id BIGINT UNSIGNED NOT NULL,
    type ENUM('basic', 'allowance', 'deduction', 'overtime', 'advance_deduction') NOT NULL,
    description VARCHAR(255) NOT NULL,
    amount DECIMAL(12, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (payment_id) REFERENCES salary_payments(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إضافة فهارس جديدة
CREATE INDEX idx_salary_payments_employee ON salary_payments(employee_id);
CREATE INDEX idx_salary_payments_period ON salary_payments(payment_period);
CREATE INDEX idx_salary_payments_status ON salary_payments(status);
CREATE INDEX idx_salary_advances_employee ON salary_advances(employee_id);
CREATE INDEX idx_salary_advances_status ON salary_advances(status);
CREATE INDEX idx_advance_repayments_advance ON advance_repayments(advance_id);
CREATE INDEX idx_salary_details_payment ON salary_details(payment_id);
CREATE INDEX idx_salary_details_type ON salary_details(type);

-- تعديل جدول السلف والقروض
ALTER TABLE salary_advances 
ADD COLUMN advance_type ENUM('monthly_advance', 'loan') NOT NULL DEFAULT 'monthly_advance' AFTER amount,
ADD COLUMN deduction_type ENUM('current_month', 'installments') NOT NULL DEFAULT 'current_month' AFTER advance_type;

-- إنشاء جدول خطط السداد
CREATE TABLE IF NOT EXISTS loan_repayment_plans (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    advance_id BIGINT UNSIGNED NOT NULL,
    total_amount DECIMAL(12, 2) NOT NULL,
    installment_amount DECIMAL(12, 2) NOT NULL,
    total_installments INT NOT NULL,
    remaining_installments INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status ENUM('active', 'completed', 'defaulted') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (advance_id) REFERENCES salary_advances(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول أقساط القروض
CREATE TABLE IF NOT EXISTS loan_installments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    plan_id BIGINT UNSIGNED NOT NULL,
    installment_number INT NOT NULL,
    due_date DATE NOT NULL,
    amount DECIMAL(12, 2) NOT NULL,
    status ENUM('pending', 'paid', 'overdue') DEFAULT 'pending',
    payment_date DATE NULL,
    payment_reference VARCHAR(100),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (plan_id) REFERENCES loan_repayment_plans(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إضافة فهارس جديدة
CREATE INDEX idx_advances_type ON salary_advances(advance_type);
CREATE INDEX idx_advances_deduction ON salary_advances(deduction_type);
CREATE INDEX idx_loan_plans_advance ON loan_repayment_plans(advance_id);
CREATE INDEX idx_loan_plans_status ON loan_repayment_plans(status);
CREATE INDEX idx_installments_plan ON loan_installments(plan_id);
CREATE INDEX idx_installments_due_date ON loan_installments(due_date);
CREATE INDEX idx_installments_status ON loan_installments(status);

-- إنشاء جدول إشعارات المدفوعات
CREATE TABLE IF NOT EXISTS payment_notifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type ENUM('due_date', 'overdue', 'invoice', 'payment_reminder') NOT NULL,
    recipient_id BIGINT UNSIGNED NOT NULL,
    payment_id BIGINT UNSIGNED NULL,
    invoice_id BIGINT UNSIGNED NULL,
    advance_id BIGINT UNSIGNED NULL,
    loan_id BIGINT UNSIGNED NULL,
    channels JSON, -- ['whatsapp', 'email', 'sms']
    scheduled_at TIMESTAMP NOT NULL,
    sent_at TIMESTAMP NULL,
    status ENUM('pending', 'sent', 'failed') DEFAULT 'pending',
    error_message TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (recipient_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (payment_id) REFERENCES payments(id) ON DELETE SET NULL,
    FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE SET NULL,
    FOREIGN KEY (advance_id) REFERENCES salary_advances(id) ON DELETE SET NULL,
    FOREIGN KEY (loan_id) REFERENCES loan_repayment_plans(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول قوالب الإشعارات
CREATE TABLE IF NOT EXISTS notification_templates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL,
    channel ENUM('whatsapp', 'email', 'sms') NOT NULL,
    subject VARCHAR(255),
    content TEXT NOT NULL,
    variables JSON, -- المتغيرات المتاحة في القالب
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إضافة فهارس
CREATE INDEX idx_notifications_type ON payment_notifications(type);
CREATE INDEX idx_notifications_recipient ON payment_notifications(recipient_id);
CREATE INDEX idx_notifications_status ON payment_notifications(status);
CREATE INDEX idx_notifications_scheduled ON payment_notifications(scheduled_at);

-- تحديث جدول إشعارات المدفوعات
ALTER TABLE payment_notifications 
ADD COLUMN attempts INT DEFAULT 0 AFTER status,
ADD COLUMN data JSON AFTER channels,
ADD COLUMN notification_id VARCHAR(100) AFTER id,
ADD COLUMN provider_response TEXT AFTER error_message;

-- إنشاء جدول سجلات الإشعارات
CREATE TABLE IF NOT EXISTS notification_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    notification_id BIGINT UNSIGNED NOT NULL,
    attempt_number INT NOT NULL,
    channel VARCHAR(20) NOT NULL,
    status ENUM('success', 'failed') NOT NULL,
    provider_response TEXT,
    error_code VARCHAR(50),
    error_message TEXT,
    sent_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (notification_id) REFERENCES payment_notifications(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إضافة فهارس جديدة
CREATE INDEX idx_notification_logs_notification ON notification_logs(notification_id);
CREATE INDEX idx_notification_logs_status ON notification_logs(status);
CREATE INDEX idx_notification_logs_channel ON notification_logs(channel);
CREATE INDEX idx_notifications_notification_id ON payment_notifications(notification_id);

-- إنشاء جدول تقارير أداء الإشعارات
CREATE TABLE IF NOT EXISTS notification_performance_reports (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    report_date DATE NOT NULL,
    period ENUM('daily', 'weekly', 'monthly') NOT NULL,
    total_notifications INT UNSIGNED NOT NULL,
    successful_count INT UNSIGNED NOT NULL,
    failed_count INT UNSIGNED NOT NULL,
    success_rate DECIMAL(5,2) NOT NULL,
    avg_delivery_time DECIMAL(10,2) NOT NULL,
    peak_hour INT UNSIGNED,
    peak_hour_count INT UNSIGNED,
    retry_attempts INT UNSIGNED,
    retry_success_rate DECIMAL(5,2),
    channel_stats JSON,
    type_stats JSON,
    failure_analysis JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY idx_report_date_period (report_date, period)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إضافة فهارس للبحث والتحليل
CREATE INDEX idx_report_date ON notification_performance_reports(report_date);
CREATE INDEX idx_report_period ON notification_performance_reports(period);
CREATE INDEX idx_success_rate ON notification_performance_reports(success_rate); 

-- إنشاء جدول أداء قنوات الإشعارات
CREATE TABLE IF NOT EXISTS notification_channel_performance (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    channel VARCHAR(20) NOT NULL,
    date DATE NOT NULL,
    total_sent INT UNSIGNED NOT NULL DEFAULT 0,
    successful INT UNSIGNED NOT NULL DEFAULT 0,
    failed INT UNSIGNED NOT NULL DEFAULT 0,
    avg_delivery_time DECIMAL(10,2) NOT NULL DEFAULT 0,
    error_rate DECIMAL(5,2) NOT NULL DEFAULT 0,
    cost DECIMAL(10,2) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY idx_channel_date (channel, date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إضافة فهارس للأداء
CREATE INDEX idx_channel_performance_date ON notification_channel_performance(date);
CREATE INDEX idx_channel_performance_channel ON notification_channel_performance(channel);

-- إنشاء جدول أداء النظام
CREATE TABLE IF NOT EXISTS system_performance_metrics (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    check_date DATE NOT NULL,
    check_time TIME NOT NULL,
    metrics JSON NOT NULL, -- تخزين مقاييس الأداء
    alerts JSON, -- تخزين التنبيهات
    system_load DECIMAL(5,2), -- حمل النظام
    memory_usage DECIMAL(5,2), -- استخدام الذاكرة
    disk_usage DECIMAL(5,2), -- استخدام القرص
    queue_size INT, -- حجم قائمة الانتظار
    processing_time DECIMAL(10,2), -- وقت المعالجة
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY idx_check_datetime (check_date, check_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إضافة فهارس للبحث والتحليل
CREATE INDEX idx_performance_date ON system_performance_metrics(check_date);
CREATE INDEX idx_performance_load ON system_performance_metrics(system_load);
CREATE INDEX idx_performance_memory ON system_performance_metrics(memory_usage);

-- إضافة مجموعات إعدادات جديدة
INSERT INTO setting_groups (name, key, description, is_core, display_order) VALUES 
('إعدادات الموقع', 'website', 'الإعدادات العامة للموقع', true, 0),
('إعدادات SEO', 'seo', 'إعدادات تحسين محركات البحث', true, 9),
('إعدادات اللغة والترجمة', 'localization', 'إعدادات اللغات والترجمة', true, 10),
('إعدادات التصميم', 'theme', 'إعدادات تصميم الموقع', true, 11),
('إعدادات القوائم', 'menus', 'إعدادات قوائم الموقع', false, 12);

-- إضافة إعدادات الموقع الأساسية
INSERT INTO advanced_settings 
(group_id, name, key, value, type, is_required, description, default_value, display_order) VALUES
-- إعدادات الموقع
(1, 'اسم الموقع', 'site_name', 'Phoenix IT', 'text', true, 'اسم الموقع الرئيسي', 'Phoenix IT', 1),
(1, 'شعار الموقع', 'site_logo', '/images/logo.png', 'text', true, 'مسار شعار الموقع', '/images/logo.png', 2),
(1, 'أيقونة الموقع', 'site_favicon', '/images/favicon.ico', 'text', true, 'مسار أيقونة الموقع', '/images/favicon.ico', 3),
(1, 'وصف الموقع', 'site_description', 'نظام إدارة الخدمات التقنية', 'textarea', true, 'وصف عام للموقع', '', 4),
(1, 'معلومات التواصل', 'contact_info', '{"email":"info@phoenixitiq.com","phone":"+964XXXXXXXX"}', 'json', true, 'معلومات التواصل الرئيسية', '', 5),

-- إعدادات SEO
(9, 'العنوان الافتراضي', 'default_meta_title', '{site_name} | {page_title}', 'text', true, 'قالب عنوان الصفحات', '', 1),
(9, 'الوصف الافتراضي', 'default_meta_description', '', 'textarea', false, 'الوصف الافتراضي للصفحات', '', 2),
(9, 'الكلمات المفتاحية', 'default_meta_keywords', '', 'textarea', false, 'الكلمات المفتاحية الافتراضية', '', 3),
(9, 'روبوتات البحث', 'robots_txt', 'User-agent: *\nAllow: /', 'textarea', true, 'محتوى ملف robots.txt', '', 4),
(9, 'خريطة الموقع', 'sitemap_settings', '{"auto_generate": true,"frequency": "weekly"}', 'json', true, 'إعدادات خريطة الموقع', '', 5),

-- إعدادات اللغة والترجمة
(10, 'اللغة الافتراضية', 'default_language', 'ar', 'select', true, 'اللغة الافتراضية للموقع', 'ar', 1),
(10, 'اللغات المفعلة', 'enabled_languages', '["ar","en"]', 'json', true, 'قائمة اللغات المفعلة', '["ar"]', 2),
(10, 'اتجاه الموقع', 'site_direction', 'rtl', 'select', true, 'اتجاه عرض الموقع', 'rtl', 3),
(10, 'العملة الافتراضية', 'default_currency', 'IQD', 'select', true, 'العملة الافتراضية', 'IQD', 4),
(10, 'تنسيق التاريخ', 'date_format', 'Y-m-d', 'text', true, 'تنسيق عرض التاريخ', 'Y-m-d', 5),

-- إعدادات التصميم
(11, 'القالب الحالي', 'active_theme', 'default', 'select', true, 'القالب المستخدم حالياً', 'default', 1),
(11, 'الألوان الرئيسية', 'theme_colors', '{"primary":"#007bff","secondary":"#6c757d"}', 'json', true, 'ألوان التصميم الرئيسية', '', 2),
(11, 'الخطوط', 'fonts', '{"main":"Cairo","heading":"Tajawal"}', 'json', true, 'الخطوط المستخدمة', '', 3),
(11, 'CSS مخصص', 'custom_css', '', 'textarea', false, 'أكواد CSS إضافية', '', 4),
(11, 'JavaScript مخصص', 'custom_js', '', 'textarea', false, 'أكواد JavaScript إضافية', '', 5),

-- إعدادات القوائم والتذييل
(12, 'القائمة الرئيسية', 'main_menu', '[]', 'json', true, 'عناصر القائمة الرئيسية', '[]', 1),
(12, 'قائمة التذييل', 'footer_menu', '[]', 'json', true, 'عناصر قائمة التذييل', '[]', 2),
(12, 'نص التذييل', 'footer_text', 'جميع الحقوق محفوظة © {year} Phoenix IT', 'text', true, 'نص حقوق النشر', '', 3),
(12, 'روابط التواصل الاجتماعي', 'social_links', '[]', 'json', true, 'روابط مواقع التواصل الاجتماعي', '[]', 4),

-- إضافة إعدادات التصميم المتقدمة
INSERT INTO advanced_settings 
(group_id, name, key, value, type, is_required, description, default_value, display_order) VALUES
-- نظام الألوان الأساسي
(11, 'نظام الألوان', 'color_scheme', '{
    "primary": {
        "main": "#1e88e5",
        "light": "#4b9fea",
        "dark": "#1565c0",
        "contrast": "#ffffff"
    },
    "secondary": {
        "main": "#424242",
        "light": "#6d6d6d",
        "dark": "#1b1b1b",
        "contrast": "#ffffff"
    },
    "success": {
        "main": "#2e7d32",
        "light": "#4caf50",
        "dark": "#1b5e20",
        "contrast": "#ffffff"
    },
    "warning": {
        "main": "#ed6c02",
        "light": "#ff9800",
        "dark": "#e65100",
        "contrast": "#ffffff"
    },
    "error": {
        "main": "#d32f2f",
        "light": "#ef5350",
        "dark": "#c62828",
        "contrast": "#ffffff"
    },
    "info": {
        "main": "#0288d1",
        "light": "#03a9f4",
        "dark": "#01579b",
        "contrast": "#ffffff"
    },
    "background": {
        "default": "#ffffff",
        "paper": "#f5f5f5",
        "dark": "#121212"
    },
    "text": {
        "primary": "#1a1a1a",
        "secondary": "#666666",
        "disabled": "#9e9e9e"
    }
}', 'json', true, 'نظام الألوان الأساسي للموقع', '', 1),

-- تخصيص واجهة المستخدم
(11, 'تخصيص الواجهة', 'ui_customization', '{
    "borderRadius": "8px",
    "spacing": {
        "unit": 8,
        "xs": 4,
        "sm": 8,
        "md": 16,
        "lg": 24,
        "xl": 32
    },
    "shadows": {
        "sm": "0 2px 4px rgba(0,0,0,0.1)",
        "md": "0 4px 8px rgba(0,0,0,0.12)",
        "lg": "0 8px 16px rgba(0,0,0,0.14)"
    },
    "transitions": {
        "duration": {
            "short": 200,
            "standard": 300,
            "complex": 500
        },
        "easing": {
            "easeIn": "cubic-bezier(0.4, 0, 1, 1)",
            "easeOut": "cubic-bezier(0.0, 0, 0.2, 1)",
            "easeInOut": "cubic-bezier(0.4, 0, 0.2, 1)"
        }
    }
}', 'json', true, 'تخصيص عناصر واجهة المستخدم', '', 2),

-- تخصيص لوحة التحكم
(11, 'تخصيص لوحة التحكم', 'dashboard_customization', '{
    "layout": {
        "sidebarWidth": 280,
        "topbarHeight": 64,
        "contentPadding": 24,
        "footerHeight": 48
    },
    "sidebar": {
        "backgroundColor": "#1a1a1a",
        "textColor": "#ffffff",
        "activeItemColor": "#1e88e5",
        "hoverColor": "rgba(255,255,255,0.08)"
    },
    "topbar": {
        "backgroundColor": "#ffffff",
        "textColor": "#1a1a1a",
        "borderColor": "#e0e0e0"
    },
    "content": {
        "backgroundColor": "#f5f5f5",
        "cardBackground": "#ffffff",
        "tableBorder": "#e0e0e0",
        "tableHover": "#f5f5f5"
    }
}', 'json', true, 'تخصيص مظهر لوحة التحكم', '', 3),

-- تخصيص الخطوط
(11, 'إعدادات الخطوط', 'typography_settings', '{
    "fontFamily": {
        "primary": "Cairo",
        "secondary": "Tajawal",
        "monospace": "Consolas"
    },
    "fontSize": {
        "xs": "12px",
        "sm": "14px",
        "md": "16px",
        "lg": "18px",
        "xl": "20px",
        "h1": "32px",
        "h2": "28px",
        "h3": "24px",
        "h4": "20px",
        "h5": "18px",
        "h6": "16px"
    },
    "fontWeight": {
        "light": 300,
        "regular": 400,
        "medium": 500,
        "semibold": 600,
        "bold": 700
    },
    "lineHeight": {
        "tight": 1.2,
        "normal": 1.5,
        "relaxed": 1.8
    }
}', 'json', true, 'إعدادات الخطوط والتايبوغرافي', '', 4),

-- تخصيص الأزرار
(11, 'تصميم الأزرار', 'button_styles', '{
    "variants": {
        "contained": {
            "borderRadius": "8px",
            "padding": "8px 24px",
            "textTransform": "none"
        },
        "outlined": {
            "borderRadius": "8px",
            "padding": "7px 23px",
            "borderWidth": "1px"
        },
        "text": {
            "padding": "8px 16px"
        }
    },
    "sizes": {
        "small": {
            "fontSize": "12px",
            "padding": "6px 16px"
        },
        "medium": {
            "fontSize": "14px",
            "padding": "8px 24px"
        },
        "large": {
            "fontSize": "16px",
            "padding": "10px 32px"
        }
    }
}', 'json', true, 'تخصيص تصميم الأزرار', '', 5);

-- إضافة إعدادات تخصيص الجداول
INSERT INTO advanced_settings 
(group_id, name, key, value, type, is_required, description, default_value, display_order) VALUES
(11, 'تصميم الجداول', 'table_styles', '{
    "header": {
        "backgroundColor": "#f5f5f5",
        "textColor": "#1a1a1a",
        "fontSize": "14px",
        "fontWeight": 600,
        "padding": "12px 16px"
    },
    "cell": {
        "padding": "12px 16px",
        "borderColor": "#e0e0e0",
        "fontSize": "14px"
    },
    "row": {
        "hover": {
            "backgroundColor": "#f9f9f9"
        },
        "selected": {
            "backgroundColor": "#e3f2fd"
        }
    },
    "pagination": {
        "fontSize": "14px",
        "padding": "8px 12px"
    }
}', 'json', true, 'تخصيص تصميم الجداول', '', 6);

-- إنشاء جدول تخصيص الصفحات
CREATE TABLE IF NOT EXISTS page_customizations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_key VARCHAR(100) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    layout VARCHAR(100) DEFAULT 'default',
    content JSON,
    meta_data JSON,
    custom_css TEXT,
    custom_js TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول مكونات الصفحات
CREATE TABLE IF NOT EXISTS page_components (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_id BIGINT UNSIGNED NOT NULL,
    component_type VARCHAR(100) NOT NULL,
    component_key VARCHAR(100) NOT NULL,
    title VARCHAR(255),
    content JSON,
    settings JSON,
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (page_id) REFERENCES page_customizations(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إضافة فهارس
CREATE INDEX idx_page_customizations_key ON page_customizations(page_key);
CREATE INDEX idx_page_components_page ON page_components(page_id);
CREATE INDEX idx_page_components_type ON page_components(component_type);

-- إنشاء جدول مكتبة المكونات
CREATE TABLE IF NOT EXISTS component_library (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    key VARCHAR(100) NOT NULL UNIQUE,
    category VARCHAR(50) NOT NULL,
    icon VARCHAR(50),
    description TEXT,
    default_settings JSON,
    allowed_children JSON, -- أنواع المكونات المسموح بها داخل هذا المكون
    is_container BOOLEAN DEFAULT FALSE, -- هل يمكن إضافة مكونات داخله
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء جدول تخطيطات الصفحات
CREATE TABLE IF NOT EXISTS page_layouts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    key VARCHAR(100) NOT NULL UNIQUE,
    thumbnail VARCHAR(255),
    description TEXT,
    structure JSON, -- هيكل التخطيط (مناطق السحب والإفلات)
    default_components JSON, -- المكونات الافتراضية
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- تحديث جدول مكونات الصفحات
ALTER TABLE page_components
ADD COLUMN parent_id BIGINT UNSIGNED NULL AFTER page_id,
ADD COLUMN container_key VARCHAR(100) AFTER parent_id,
ADD COLUMN layout_position VARCHAR(100),
ADD COLUMN style_settings JSON,
ADD COLUMN responsive_settings JSON,
ADD COLUMN animation_settings JSON,
ADD FOREIGN KEY (parent_id) REFERENCES page_components(id) ON DELETE CASCADE;

-- إدخال البيانات الأساسية للمكونات
INSERT INTO component_library (name, key, category, icon, description, default_settings, is_container) VALUES 
-- مكونات النص والعناوين
('عنوان رئيسي', 'heading', 'text', 'heading-icon', 'عنصر عنوان قابل للتخصيص', '{"tag":"h1","align":"right","color":"#000000"}', false),
('فقرة نصية', 'paragraph', 'text', 'text-icon', 'كتلة نص قابلة للتحرير', '{"fontSize":"16px","lineHeight":"1.6"}', false),

-- مكونات التخطيط
('صف', 'row', 'layout', 'row-icon', 'صف يمكن تقسيمه إلى أعمدة', '{"columns":1,"gap":"20px"}', true),
('عمود', 'column', 'layout', 'column-icon', 'عمود داخل الصف', '{"width":"100%","padding":"15px"}', true),
('حاوية', 'container', 'layout', 'container-icon', 'حاوية مرنة للمكونات', '{"maxWidth":"1200px","margin":"auto"}', true),

-- مكونات الوسائط
('صورة', 'image', 'media', 'image-icon', 'عنصر صورة قابل للتخصيص', '{"width":"auto","height":"auto"}', false),
('فيديو', 'video', 'media', 'video-icon', 'مشغل فيديو', '{"autoplay":false,"controls":true}', false),
('معرض صور', 'gallery', 'media', 'gallery-icon', 'معرض صور قابل للتخصيص', '{"columns":3,"gap":"10px"}', true),

-- مكونات التفاعل
('زر', 'button', 'interactive', 'button-icon', 'زر قابل للتخصيص', '{"style":"primary","size":"medium"}', false),
('نموذج', 'form', 'interactive', 'form-icon', 'نموذج تفاعلي', '{"method":"post","layout":"vertical"}', true),
('قائمة', 'menu', 'navigation', 'menu-icon', 'قائمة تنقل', '{"orientation":"horizontal"}', true);

-- إدخال البيانات الأساسية للتخطيطات
INSERT INTO page_layouts (name, key, thumbnail, description, structure) VALUES 
('تخطيط افتراضي', 'default', '/layouts/default.png', 'تخطيط بسيط مع رأس وتذييل', 
    '{"regions":["header","main","sidebar","footer"],"grid":{"main":"70%","sidebar":"30%"}}'),
('صفحة هبوط', 'landing', '/layouts/landing.png', 'تخطيط لصفحات الهبوط', 
    '{"regions":["hero","features","content","cta","testimonials"],"fullWidth":true}'),
('مدونة', 'blog', '/layouts/blog.png', 'تخطيط لصفحات المدونة', 
    '{"regions":["header","content","sidebar","comments","related"],"grid":{"content":"75%","sidebar":"25%"}}');