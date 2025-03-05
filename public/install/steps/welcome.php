<?php
defined('INSTALLER') || exit('No direct script access allowed');
?>

<div class="welcome-page">
    <h1>مرحباً بك في معالج التثبيت</h1>
    <p>سيساعدك هذا المعالج في تثبيت النظام خطوة بخطوة.</p>
    
    <div class="requirements">
        <h2>متطلبات النظام</h2>
        <ul>
            <li>PHP 7.4 أو أعلى</li>
            <li>قاعدة بيانات MySQL</li>
            <li>تفعيل PDO و mbstring</li>
        </ul>
    </div>
    
    <a href="?step=requirements" class="btn btn-primary">ابدأ التثبيت</a>
</div>

<!-- التنبيهات -->
<?php if (isset($_SESSION['error'])): ?>
<div class="alert alert-danger">
    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
</div>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
<div class="alert alert-success">
    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
</div>
<?php endif; ?> 