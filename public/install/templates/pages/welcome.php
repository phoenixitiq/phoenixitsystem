<div class="welcome-container">
    <!-- قسم الترحيب الرئيسي -->
    <div class="welcome-hero">
        <img src="assets/images/logo.png" alt="Phoenix IT" class="welcome-logo">
        <h1 class="welcome-title"><?php echo __('welcome'); ?></h1>
        <p class="welcome-subtitle"><?php echo __('system_description'); ?></p>
        
        <div class="welcome-actions">
            <a href="?page=requirements" class="btn btn-primary">
                <i class="fas fa-rocket"></i>
                <?php echo __('start_install'); ?>
            </a>
            <a href="https://docs.phoenixitiq.com" target="_blank" class="btn btn-secondary">
                <i class="fas fa-book"></i>
                <?php echo __('user_guide'); ?>
            </a>
        </div>
    </div>

    <!-- مميزات النظام -->
    <div class="features-grid">
        <?php foreach (['security', 'performance', 'responsive', 'customizable'] as $feature): ?>
        <div class="feature-card">
            <i class="fas fa-<?php echo getFeatureIcon($feature); ?>"></i>
            <h3><?php echo __("features.{$feature}.title"); ?></h3>
            <p><?php echo __("features.{$feature}.desc"); ?></p>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- خطوات التثبيت -->
    <div class="installation-steps">
        <h2><?php echo __('installation_steps'); ?></h2>
        <div class="steps-container">
            <?php foreach (getInstallationSteps() as $step): ?>
            <div class="step">
                <div class="step-number"><?php echo $step['number']; ?></div>
                <h4><?php echo $step['title']; ?></h4>
                <p><?php echo $step['description']; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div> 