<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

try {
    // التحقق من المتطلبات
    $requirements = checkSystemRequirements();
    $directories = checkWritePermissions();
    
    // التحقق من اكتمال جميع المتطلبات
    $allMet = true;
    foreach ($requirements as $requirement) {
        if (!$requirement) {
            $allMet = false;
            break;
        }
    }
    
    foreach ($directories as $directory) {
        if (!$directory) {
            $allMet = false;
            break;
        }
    }

    if ($allMet) {
        $steps = new Steps();
        $steps->completeStep('requirements');
        echo showSuccess('تم التحقق من المتطلبات بنجاح');
    } else {
        echo showError('لم يتم استيفاء جميع المتطلبات');
    }
} catch (Exception $e) {
    echo showError($e->getMessage());
} 