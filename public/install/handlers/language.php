<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/security.php';

session_start();

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $lang = isset($_POST['lang']) ? $_POST['lang'] : null;
        
        if ($lang && in_array($lang, ['ar', 'en'])) {
            $_SESSION['lang'] = $lang;
            echo json_encode([
                'success' => true,
                'message' => $lang === 'ar' ? 'تم تغيير اللغة بنجاح' : 'Language changed successfully',
                'lang' => $lang
            ]);
            exit;
        }
    }
    
    throw new Exception('Invalid request');
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 