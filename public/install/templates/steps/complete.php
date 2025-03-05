<?php
if (!$steps->checkAllSteps()) {
    redirect('?step=' . $steps->getPreviousIncompleteStep());
}
?>

<div class="step-content">
    <div class="complete-message">
        <div class="success-icon">✓</div>
        <h2>تم التثبيت بنجاح!</h2>
        <p>تم تثبيت النظام بنجاح ويمكنك الآن البدء في استخدامه.</p>
    </div>

    <div class="installation-details">
        <h3>معلومات هامة</h3>
        <div class="info-box">
            <h4>رابط لوحة التحكم</h4>
            <p><a href="../admin/login.php" target="_blank"><?php echo getBaseUrl(); ?>/admin/login.php</a></p>
        </div>
        
        <div class="info-box warning">
            <h4>خطوات ما بعد التثبيت</h4>
            <ul>
                <li>قم بحذف مجلد التثبيت <code>install</code> من الخادم</li>
                <li>قم بتغيير صلاحيات ملف <code>.env</code> إلى 644</li>
                <li>قم بعمل نسخة احتياطية من ملف <code>.env</code></li>
            </ul>
        </div>
    </div>

    <div class="step-buttons">
        <a href="../admin/login.php" class="btn btn-success">
            الذهاب إلى لوحة التحكم
        </a>
    </div>
</div>

<style>
.complete-message {
    text-align: center;
    margin-bottom: 2rem;
}

.success-icon {
    font-size: 4rem;
    color: var(--success-color);
    margin-bottom: 1rem;
}

.info-box {
    background: var(--background-color);
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1rem;
}

.info-box.warning {
    border-right: 4px solid var(--warning-color);
}

.info-box h4 {
    margin-bottom: 0.5rem;
    color: var(--text-color);
}

.info-box ul {
    margin: 0;
    padding-right: 1.5rem;
}

.info-box li {
    margin-bottom: 0.5rem;
}

.info-box code {
    background: rgba(0, 0, 0, 0.05);
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
    font-family: monospace;
}
</style>
