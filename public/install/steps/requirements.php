<?php
$requirements = [
    'php' => [
        'version' => '7.4.0',
        'current' => PHP_VERSION,
        'status' => version_compare(PHP_VERSION, '7.4.0', '>=')
    ],
    'extensions' => [
        'pdo' => extension_loaded('pdo'),
        'pdo_mysql' => extension_loaded('pdo_mysql'),
        'mbstring' => extension_loaded('mbstring'),
        'json' => extension_loaded('json'),
        'curl' => extension_loaded('curl')
    ],
    'directories' => [
        'storage/logs' => is_writable(ROOT_PATH . '/storage/logs'),
        'storage/cache' => is_writable(ROOT_PATH . '/storage/cache'),
        'storage/uploads' => is_writable(ROOT_PATH . '/storage/uploads')
    ]
];

$all_requirements_met = true;
foreach ($requirements as $type => $checks) {
    if ($type === 'php' && !$checks['status']) {
        $all_requirements_met = false;
    } elseif ($type !== 'php') {
        foreach ($checks as $status) {
            if (!$status) {
                $all_requirements_met = false;
                break;
            }
        }
    }
}
?>

<div class="requirements-check">
    <h2><?php echo __('requirements'); ?></h2>
    
    <!-- عرض نتائج الفحص -->
</div> 