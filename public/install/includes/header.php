<?php
defined('INSTALLER') || exit('No direct script access allowed');
$lang = Language::getInstance();
?>
<!DOCTYPE html>
<html dir="<?php echo $lang->getDirection(); ?>" lang="<?php echo $lang->getCurrentLang(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $lang->get('system_name'); ?> - <?php echo $lang->get('installation'); ?></title>
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/install.css">
    <?php if ($lang->getCurrentLang() === 'ar'): ?>
    <link rel="stylesheet" href="assets/css/rtl.css">
    <?php endif; ?>
</head>
<body class="lang-<?php echo $lang->getCurrentLang(); ?>">
    <?php echo $lang->getLanguageSelector(); ?>
    <div class="container"> 