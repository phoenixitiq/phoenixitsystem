        </main>
        
        <?php if ($current_step !== 'complete'): ?>
        <div class="progress-bar">
            <div class="progress" style="width: <?php echo Steps::getProgress(); ?>%"></div>
        </div>
        <?php endif; ?>
        
        <footer class="installer-footer">
            <div class="footer-content">
                <p>
                    <?php echo $lang['copyright']; ?> &copy; <?php echo date('Y'); ?>
                    <a href="https://phoenixitiq.com" target="_blank">PHOENIX IT & MARKETING LTD</a>
                </p>
                <p class="version">
                    <?php echo $lang['version']; ?>: <?php echo APP_VERSION; ?>
                </p>
            </div>
            
            <?php if ($current_step !== 'welcome'): ?>
            <div class="language-switcher">
                <a href="?lang=ar&step=<?php echo $current_step; ?>" class="<?php echo $lang_code === 'ar' ? 'active' : ''; ?>">العربية</a>
                <span>|</span>
                <a href="?lang=en&step=<?php echo $current_step; ?>" class="<?php echo $lang_code === 'en' ? 'active' : ''; ?>">English</a>
            </div>
            <?php endif; ?>
        </footer>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/install.js"></script>
    <?php if (isset($page_scripts)): ?>
        <?php foreach ($page_scripts as $script): ?>
        <script src="assets/js/<?php echo $script; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
