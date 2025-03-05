<?php
$steps = [
    'welcome' => [
        'icon' => 'welcome.svg',
        'title' => $lang['welcome']
    ],
    'requirements' => [
        'icon' => 'requirements.svg',
        'title' => $lang['requirements']
    ],
    'database' => [
        'icon' => 'database.svg',
        'title' => $lang['database_settings']
    ],
    'admin' => [
        'icon' => 'admin.svg',
        'title' => $lang['admin_settings']
    ],
    'complete' => [
        'icon' => 'success.svg',
        'title' => $lang['complete']
    ]
];

$current_index = array_search($current_step, array_keys($steps));
?>

<div class="steps">
    <?php foreach ($steps as $step => $info): 
        $step_index = array_search($step, array_keys($steps));
        $class = '';
        
        if ($step === $current_step) {
            $class = 'active';
        } elseif ($step_index < $current_index) {
            $class = 'completed';
        }
    ?>
    <div class="step <?php echo $class; ?>">
        <img src="assets/images/<?php echo $info['icon']; ?>" alt="<?php echo $info['title']; ?>">
        <span class="step-label"><?php echo $info['title']; ?></span>
    </div>
    <?php endforeach; ?>
</div>
