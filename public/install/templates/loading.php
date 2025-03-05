<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-content">
        <div class="loading-spinner"></div>
        <p class="loading-text">جاري التحميل...</p>
    </div>
</div>

<style>
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s;
}

.loading-overlay.active {
    opacity: 1;
    visibility: visible;
}

.loading-content {
    text-align: center;
}

.loading-spinner {
    width: 50px;
    height: 50px;
    border: 4px solid var(--border-color);
    border-top-color: var(--primary-color);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 1rem;
}

.loading-text {
    color: var(--text-color);
    font-weight: 500;
}
</style>

<script>
const loadingOverlay = {
    show() {
        document.getElementById('loadingOverlay').classList.add('active');
    },
    hide() {
        document.getElementById('loadingOverlay').classList.remove('active');
    }
};

// إضافة إلى مدير التثبيت
if (window.installationManager) {
    window.installationManager.loading = loadingOverlay;
}
</script> 