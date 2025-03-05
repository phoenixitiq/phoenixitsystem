<?php 
include 'layout/header.php';
$requirements = checkRequirements();
$can_continue = true;
?>

<div class="step-content requirements">
    <h2>متطلبات النظام</h2>
    <div class="requirements-check">
        <div class="requirement-item">
            <span class="label">إصدار PHP</span>
            <span class="status" id="php-version"></span>
        </div>
        <div class="requirement-item">
            <span class="label">PDO Extension</span>
            <span class="status" id="pdo-ext"></span>
        </div>
        <div class="requirement-item">
            <span class="label">Curl Extension</span>
            <span class="status" id="curl-ext"></span>
        </div>
        <div class="requirement-item">
            <span class="label">GD Extension</span>
            <span class="status" id="gd-ext"></span>
        </div>
        <div class="requirement-item">
            <span class="label">صلاحيات المجلدات</span>
            <div id="folder-permissions"></div>
        </div>
    </div>
</div>

<div class="actions">
    <a href="?step=welcome" class="btn btn-secondary"><?php echo $lang['previous']; ?></a>
    <?php if ($requirements_met): ?>
    <a href="?step=database" class="btn btn-primary"><?php echo $lang['next']; ?></a>
    <?php endif; ?>
</div>

<?php include 'layout/footer.php'; ?>
