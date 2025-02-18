<div class="requirements">
    <h2>متطلبات النظام</h2>
    
    <?php if (!$requirements['allMet']): ?>
        <div class="alert alert-warning mb-4">
            <i class="fas fa-exclamation-triangle me-2"></i>
            يرجى تلبية جميع المتطلبات قبل المتابعة
        </div>
    <?php else: ?>
        <div class="alert alert-success mb-4">
            <i class="fas fa-check-circle me-2"></i>
            تم استيفاء جميع المتطلبات
        </div>
    <?php endif; ?>

    <div class="requirement-group mb-4">
        <h3>إصدار PHP</h3>
        <div class="requirement-item <?= $requirements['php'] ? 'success' : 'error' ?>">
            <div class="d-flex justify-content-between align-items-center">
                <span>PHP >= 8.1.0</span>
                <i class="fas <?= $requirements['php'] ? 'fa-check text-success' : 'fa-times text-danger' ?>"></i>
            </div>
            <small>النسخة الحالية: <?= PHP_VERSION ?></small>
        </div>
    </div>

    <div class="requirement-group mb-4">
        <h3>إضافات PHP المطلوبة</h3>
        <?php foreach ($requirements['extensions'] as $extension => $installed): ?>
            <div class="requirement-item <?= $installed ? 'success' : 'error' ?>">
                <div class="d-flex justify-content-between align-items-center">
                    <span><?= $extension ?></span>
                    <i class="fas <?= $installed ? 'fa-check text-success' : 'fa-times text-danger' ?>"></i>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="requirement-group mb-4">
        <h3>صلاحيات المجلدات</h3>
        <?php foreach ($requirements['directories'] as $directory => $writable): ?>
            <div class="requirement-item <?= $writable ? 'success' : 'error' ?>">
                <div class="d-flex justify-content-between align-items-center">
                    <span><?= $directory ?></span>
                    <i class="fas <?= $writable ? 'fa-check text-success' : 'fa-times text-danger' ?>"></i>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($requirements['allMet']): ?>
        <form method="post" action="?step=database">
            <button type="submit" class="btn btn-primary">متابعة</button>
        </form>
    <?php else: ?>
        <button class="btn btn-secondary" disabled>يرجى تلبية جميع المتطلبات</button>
    <?php endif; ?>
</div>

<style>
.requirement-group {
    background: #fff;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.requirement-item {
    padding: 12px;
    margin-bottom: 8px;
    border-radius: 4px;
    background: #f8f9fa;
    border-left: 4px solid transparent;
}

.requirement-item.success {
    border-left-color: var(--success-color);
}

.requirement-item.error {
    border-left-color: var(--error-color);
}

.requirement-item small {
    display: block;
    color: #6c757d;
    margin-top: 4px;
}
</style> 