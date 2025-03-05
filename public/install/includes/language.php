<?php
class Language {
    private static $instance = null;
    private $translations = [];
    private $currentLang = 'ar';
    private $fallbackLang = 'en';
    
    private function __construct() {
        $this->setLanguage();
        $this->loadTranslations();
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function setLanguage() {
        // تحديد اللغة من URL
        if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar', 'en'])) {
            $this->currentLang = $_GET['lang'];
            $_SESSION['install_lang'] = $this->currentLang;
        } 
        // تحديد اللغة من الجلسة
        elseif (isset($_SESSION['install_lang'])) {
            $this->currentLang = $_SESSION['install_lang'];
        }
        // تحديد اللغة من متصفح المستخدم
        else {
            $browserLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'ar', 0, 2);
            $this->currentLang = $browserLang === 'ar' ? 'ar' : 'en';
            $_SESSION['install_lang'] = $this->currentLang;
        }
    }
    
    private function loadTranslations() {
        // تحميل اللغة الحالية
        $langFile = INSTALL_PATH . '/languages/' . $this->currentLang . '.php';
        if (file_exists($langFile)) {
            $this->translations = require $langFile;
        }
        
        // تحميل اللغة الاحتياطية إذا كانت بعض الترجمات مفقودة
        if ($this->currentLang !== $this->fallbackLang) {
            $fallbackFile = INSTALL_PATH . '/languages/' . $this->fallbackLang . '.php';
            if (file_exists($fallbackFile)) {
                $fallbackTranslations = require $fallbackFile;
                $this->translations = array_merge($fallbackTranslations, $this->translations);
            }
        }
    }
    
    public function get($key, $default = '') {
        return $this->translations[$key] ?? $default;
    }
    
    public function getCurrentLang() {
        return $this->currentLang;
    }
    
    public function getDirection() {
        return $this->currentLang === 'ar' ? 'rtl' : 'ltr';
    }
    
    public function getLanguageSelector() {
        $currentUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $html = '<div class="language-selector">';
        $html .= '<a href="' . $currentUrl . '?lang=ar" class="lang-btn' . ($this->currentLang === 'ar' ? ' active' : '') . '">عربي</a>';
        $html .= '<a href="' . $currentUrl . '?lang=en" class="lang-btn' . ($this->currentLang === 'en' ? ' active' : '') . '">English</a>';
        $html .= '</div>';
        return $html;
    }
} 