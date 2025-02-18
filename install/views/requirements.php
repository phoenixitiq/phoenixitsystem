<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متطلبات النظام - Phoenix IT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #04a887;
            --error-color: #dc3545;
            --success-color: #198754;
        }
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .requirements-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
        }
        .requirement-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        .status-icon {
            font-size: 1.5rem;
        }
        .status-success {
            color: var(--success-color);
        }
        .status-error {
            color: var(--error-color);
        }
    </style>
</head>
<body>
    <div class="requirements-container">
        <h2 class="mb-4 text-center">متطلبات النظام</h2>
        <?php
        $results = $checker->check();
        
        // عرض نتائج PHP
        showRequirement($results['php'], 'إصدار PHP');
        
        // عرض نتائج MySQL
        showRequirement($results['mysql'], 'قاعدة البيانات MySQL');
        
        // عرض نتائج Apache
        showRequirement($results['apache'], 'وحدات Apache');
        
        // عرض نتائج الامتدادات
        showRequirement($results['extensions'], 'امتدادات PHP المطلوبة');
        
        // عرض نتائج المجلدات
        showRequirement($results['directories'], 'صلاحيات المجلدات');
        ?>

        <div class="mt-4 d-flex justify-content-between">
            <a href="?step=welcome" class="btn btn-secondary">السابق</a>
            <?php if ($results['allMet']): ?>
                <a href="?step=database" class="btn btn-primary">التالي</a>
            <?php else: ?>
                <button class="btn btn-primary" disabled>التالي</button>
            <?php endif; ?>
        </div>
    </div>

    <?php
    function showRequirement($requirement, $title) {
        $status = $requirement['status'] ?? false;
        ?>
        <div class="requirement-card">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><?php echo $title; ?></h5>
                <span class="status-icon <?php echo $status ? 'status-success' : 'status-error'; ?>">
                    <i class="fas <?php echo $status ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i>
                </span>
            </div>
            <?php if (isset($requirement['current'])): ?>
                <div class="mt-2">
                    <small class="text-muted">الإصدار الحالي: <?php echo $requirement['current']; ?></small><br>
                    <small class="text-muted">الإصدار المطلوب: <?php echo $requirement['required']; ?></small>
                </div>
            <?php endif; ?>
            
            <?php if (isset($requirement['error'])): ?>
                <div class="mt-2 text-danger">
                    <small><?php echo $requirement['error']; ?></small>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
    ?>
</body>
</html> 