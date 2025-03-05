<?php
require_once '../includes/installer.php';
require_once '../includes/error_handler.php';

// التحقق من متطلبات النظام
$requirements = [
    'php_version' => version_compare(PHP_VERSION, '7.4.0', '>='),
    'pdo' => extension_loaded('pdo'),
    'mysql' => extension_loaded('pdo_mysql'),
    'writeable' => is_writable('../'),
    'mod_rewrite' => in_array('mod_rewrite', apache_get_modules())
];

if (in_array(false, $requirements)) {
    displayError($lang['requirements_not_met']);
    exit;
}

header('Location: ../index.php?step=database'); 