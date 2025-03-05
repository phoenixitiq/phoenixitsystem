<?php
/**
 * Phoenix IT System - Installation Status Handler
 * Copyright (c) 2024 PHOENIX IT & MARKETING LTD
 */

require_once '../includes/steps.php';
session_start();

header('Content-Type: application/json');

try {
    $steps = Steps::getAllSteps();
    $current_step = Steps::getCurrentStep();
    $progress = Steps::getProgress();
    
    $steps_status = [];
    
    foreach ($steps as $step_name => $step_info) {
        $status = Steps::getStepStatus($step_name);
        
        $steps_status[] = [
            'name' => $step_name,
            'title' => $step_info['title'],
            'status' => $status,
            'icon' => $step_info['icon'],
            'current' => ($step_name === $current_step)
        ];
    }
    
    echo json_encode([
        'success' => true,
        'progress' => $progress,
        'current_step' => $current_step,
        'steps' => $steps_status,
        'completed' => Steps::checkAllSteps(),
        'can_proceed' => Steps::canProceed($current_step)
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'حدث خطأ أثناء تحديث الحالة',
        'error' => $e->getMessage()
    ]);
} 