<?php
$steps = [
    'welcome' => ['icon' => 'welcome.svg', 'title' => $lang['welcome']],
    'requirements' => ['icon' => 'requirements.svg', 'title' => $lang['requirements']],
    'database' => ['icon' => 'database.svg', 'title' => $lang['database_settings']],
    'admin' => ['icon' => 'admin.svg', 'title' => $lang['admin_settings']],
    'complete' => ['icon' => 'complete.svg', 'title' => $lang['complete']]
];

$currentStep = Installer::getCurrentStep();
?>
<div class="steps">
    <?php foreach ($steps as $step => $info): ?>
        <div class="step <?php echo $step === $currentStep ? 'active' : ''; ?>">
            <img src="assets/images/<?php echo $info['icon']; ?>" alt="<?php echo $info['title']; ?>">
            <span><?php echo $info['title']; ?></span>
        </div>
    <?php endforeach; ?>
</div> 