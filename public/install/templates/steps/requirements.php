<?php
$requirements = new SystemRequirements();
$results = $requirements->checkAll();
$recommendations = $requirements->getRecommendations();
?>

<div class="step-content">
    <div class="step-indicator">
        <div class="step-item active">
            <div class="step-number">1</div>
            <div class="step-title">المتطلبات</div>
        </div>
        <div class="step-item">
            <div class="step-number">2</div>
            <div class="step-title">قاعدة البيانات</div>
        </div>
        <div class="step-item">
            <div class="step-number">3</div>
            <div class="step-title">المدير</div>
        </div>
        <div class="step-item">
            <div class="step-number">4</div>
            <div class="step-title">الإكمال</div>
        </div>
    </div>

    <h2>متطلبات النظام</h2>
    
    <div class="requirements-section">
        <h3>متطلبات PHP</h3>
        <div class="requirement-item">
            <span class="label">إصدار PHP:</span>
            <span class="value <?php echo $results['php']['version']['status'] ? 'success' : 'error'; ?>">
                <?php echo $results['php']['version']['current']; ?>
                (مطلوب: <?php echo $results['php']['version']['required']; ?>)
            </span>
        </div>

        <h4>الإضافات المطلوبة</h4>
        <div class="requirements-grid">
            <?php foreach ($results['php']['extensions'] as $ext => $info): ?>
            <div class="requirement-item">
                <span class="label"><?php echo $ext; ?>:</span>
                <span class="value <?php echo $info['status'] ? 'success' : 'error'; ?>">
                    <?php echo $info['message']; ?>
                </span>
            </div>
            <?php endforeach; ?>
        </div>

        <h4>إعدادات PHP</h4>
        <div class="requirements-grid">
            <?php foreach ($results['php']['settings'] as $setting => $info): ?>
            <div class="requirement-item">
                <span class="label"><?php echo $setting; ?>:</span>
                <span class="value <?php echo $info['status'] ? 'success' : 'error'; ?>">
                    <?php echo $info['current']; ?>
                    (مطلوب: <?php echo $info['required']; ?>)
                </span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="requirements-section">
        <h3>متطلبات قاعدة البيانات</h3>
        <?php if (isset($results['mysql']['error'])): ?>
            <div class="alert alert-error"><?php echo $results['mysql']['error']; ?></div>
        <?php else: ?>
            <div class="requirement-item">
                <span class="label">إصدار MySQL:</span>
                <span class="value <?php echo $results['mysql']['version']['status'] ? 'success' : 'error'; ?>">
                    <?php echo $results['mysql']['version']['current']; ?>
                    (مطلوب: <?php echo $results['mysql']['version']['required']; ?>)
                </span>
            </div>
        <?php endif; ?>
    </div>

    <div class="requirements-section">
        <h3>صلاحيات الملفات</h3>
        <div class="requirements-grid">
            <?php foreach ($results['filesystem'] as $path => $info): ?>
            <div class="requirement-item">
                <span class="label"><?php echo $path; ?>:</span>
                <span class="value <?php echo $info['status'] ? 'success' : 'error'; ?>">
                    <?php echo $info['writable'] ? 'قابل للكتابة' : 'غير قابل للكتابة'; ?>
                    (<?php echo $info['permission']; ?>)
                </span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if (!empty($recommendations)): ?>
    <div class="requirements-section">
        <h3>التوصيات</h3>
        <ul class="recommendations-list">
            <?php foreach ($recommendations as $recommendation): ?>
            <li><?php echo $recommendation; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="step-navigation">
        <?php
        $canProceed = !isset($results['critical_errors']) || empty($results['critical_errors']);
        if ($canProceed):
        ?>
        <button type="button" class="nav-button" data-step="database">التالي</button>
        <?php else: ?>
        <div class="alert alert-error">
            يرجى معالجة الأخطاء الحرجة قبل المتابعة
        </div>
        <?php endif; ?>
    </div>
</div>
