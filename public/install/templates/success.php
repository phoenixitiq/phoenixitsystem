<?php include 'layout/header.php'; ?>

<div class="content complete-page">
    <div class="success-icon">
        <img src="assets/images/success.svg" alt="Success">
    </div>

    <h2><?php echo $lang['success_title']; ?></h2>
    <p><?php echo $lang['success_description']; ?></p>

    <div class="installation-details">
        <div class="detail-item">
            <strong><?php echo $lang['admin_email']; ?>:</strong>
            <span><?php echo $_SESSION['admin_email']; ?></span>
        </div>
        <div class="detail-item">
            <strong><?php echo $lang['admin_password']; ?>:</strong>
            <span><?php echo $lang['use_password_created']; ?></span>
        </div>
    </div>

    <div class="actions">
        <a href="../admin" class="btn btn-primary"><?php echo $lang['finish']; ?></a>
    </div>
</div>

<?php include 'layout/footer.php'; ?> 