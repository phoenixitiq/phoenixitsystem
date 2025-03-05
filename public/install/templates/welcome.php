<?php include 'layout/header.php'; ?>

<div class="welcome-container">
    <!-- قسم الترحيب -->
    <div class="welcome-hero">
        <img src="assets/images/logo-large.png" alt="Phoenix IT" class="welcome-logo">
        <h1>مرحباً بك في نظام Phoenix IT</h1>
        <p class="lead">النظام الأمثل لإدارة خدمات تقنية المعلومات</p>
        
        <!-- أزرار البدء -->
        <div class="action-buttons">
            <a href="?step=requirements" class="btn btn-primary btn-lg start-btn">
                <i class="fas fa-rocket"></i>
                ابدأ التثبيت
            </a>
            <a href="https://phoenixitq.com/docs" target="_blank" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-book"></i>
                الدليل الإرشادي
            </a>
        </div>

        <!-- خطوات التثبيت -->
        <div class="installation-steps">
            <div class="step-item active">
                <div class="step-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="step-content">
                    <h3>فحص المتطلبات</h3>
                    <p>التحقق من توافق النظام</p>
                </div>
            </div>
            <div class="step-item">
                <div class="step-icon">
                    <i class="fas fa-database"></i>
                </div>
                <div class="step-content">
                    <h3>قاعدة البيانات</h3>
                    <p>إعداد الاتصال بقاعدة البيانات</p>
                </div>
            </div>
            <div class="step-item">
                <div class="step-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="step-content">
                    <h3>حساب المدير</h3>
                    <p>إنشاء حساب المسؤول</p>
                </div>
            </div>
            <div class="step-item">
                <div class="step-icon">
                    <i class="fas fa-flag-checkered"></i>
                </div>
                <div class="step-content">
                    <h3>إكمال التثبيت</h3>
                    <p>تهيئة النظام والانتهاء</p>
                </div>
            </div>
        </div>
    </div>

    <!-- مميزات النظام -->
    <div class="features-section">
        <h2>مميزات النظام</h2>
        <div class="features-grid">
            <div class="feature-card">
                <i class="fas fa-tasks"></i>
                <h3>إدارة التذاكر</h3>
                <p>نظام متكامل لإدارة وتتبع طلبات الدعم الفني</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-users"></i>
                <h3>إدارة المستخدمين</h3>
                <p>إدارة الصلاحيات والمستخدمين بكفاءة عالية</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-chart-line"></i>
                <h3>التقارير والإحصائيات</h3>
                <p>تقارير تفصيلية ولوحة تحكم متقدمة</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-cogs"></i>
                <h3>قابلية التخصيص</h3>
                <p>إمكانية تخصيص النظام حسب احتياجاتك</p>
            </div>
        </div>
    </div>

    <!-- متطلبات النظام -->
    <div class="requirements-section">
        <h2>متطلبات النظام</h2>
        <div class="requirements-grid">
            <div class="req-item">
                <i class="fab fa-php"></i>
                <span>PHP 7.4+</span>
            </div>
            <div class="req-item">
                <i class="fas fa-database"></i>
                <span>MySQL 5.7+</span>
            </div>
            <div class="req-item">
                <i class="fas fa-server"></i>
                <span>Apache/Nginx</span>
            </div>
            <div class="req-item">
                <i class="fas fa-memory"></i>
                <span>512MB RAM</span>
            </div>
        </div>
    </div>
</div>

<div class="content welcome-content">
    <h1><?php echo $lang['welcome_title']; ?></h1>
    <p><?php echo $lang['welcome_description']; ?></p>

    <div class="system-requirements">
        <h3><?php echo $lang['system_requirements']; ?></h3>
        <ul>
            <li>PHP >= 8.2</li>
            <li>MySQL >= 8.0</li>
            <li>OpenSSL PHP Extension</li>
            <li>PDO PHP Extension</li>
            <li>Mbstring PHP Extension</li>
        </ul>
    </div>

    <div class="language-selector">
        <a href="/install/?lang=ar&step=welcome" class="btn <?php echo $lang_code === 'ar' ? 'btn-primary' : 'btn-secondary'; ?>">العربية</a>
        <a href="/install/?lang=en&step=welcome" class="btn <?php echo $lang_code === 'en' ? 'btn-primary' : 'btn-secondary'; ?>">English</a>
    </div>

    <div class="actions">
        <a href="/install/?step=requirements&lang=<?php echo $lang_code; ?>" class="btn btn-primary"><?php echo $lang['next']; ?></a>
    </div>
</div>

<div class="step-content welcome">
    <h1>مرحباً بك في معالج التثبيت</h1>
    <p>سيساعدك هذا المعالج في تثبيت النظام خطوة بخطوة.</p>
    <div class="requirements-list">
        <h3>قبل البدء، تأكد من توفر:</h3>
        <ul>
            <li>PHP 7.4 أو أعلى</li>
            <li>قاعدة بيانات MySQL</li>
            <li>بيانات الاتصال بقاعدة البيانات</li>
            <li>صلاحيات الكتابة على المجلدات المطلوبة</li>
        </ul>
    </div>
</div>

<?php include 'layout/footer.php'; ?>
