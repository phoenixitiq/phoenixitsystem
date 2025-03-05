<?php
$progress = $steps->getStepProgress();
$currentStep = $steps->getCurrentStep();
?>
<div class="installation-progress">
    <div class="progress-bar">
        <div class="progress" style="width: <?php echo $progress['percentage']; ?>%"></div>
    </div>
    
    <div class="steps-list">
        <?php foreach ($steps->getSteps() as $stepKey => $step): ?>
            <div class="step-item <?php echo $currentStep === $stepKey ? 'active' : ''; ?> 
                                <?php echo $step['status'] ? 'completed' : ''; ?>">
                <span class="step-icon"><?php echo $step['icon']; ?></span>
                <span class="step-title"><?php echo $step['title']; ?></span>
                <?php if ($step['status']): ?>
                    <span class="step-check">✓</span>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="progress-info">
        <span>اكتمل <?php echo $progress['completed']; ?> من <?php echo $progress['total']; ?> خطوات</span>
        <span><?php echo round($progress['percentage']); ?>%</span>
    </div>
</div>

<style>
.installation-progress {
    margin-bottom: 2rem;
}

.progress-bar {
    height: 4px;
    background: var(--border-color);
    border-radius: 2px;
    margin-bottom: 1rem;
}

.progress {
    height: 100%;
    background: var(--primary-color);
    border-radius: 2px;
    transition: width 0.3s ease;
}

.steps-list {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.step-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem;
    border-radius: 0.25rem;
    background: var(--background-color);
}

.step-item.active {
    background: var(--primary-color);
    color: white;
}

.step-item.completed {
    background: var(--success-color);
    color: white;
}

.step-icon {
    font-size: 1.25rem;
}

.progress-info {
    display: flex;
    justify-content: space-between;
    color: var(--text-color);
    font-size: 0.875rem;
}
</style> 