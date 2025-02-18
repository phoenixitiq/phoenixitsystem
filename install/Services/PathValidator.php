<?php

namespace Install\Services;

class PathValidator
{
    /**
     * المسارات المسموح بها
     */
    private array $allowedPaths = [
        'install',
        'public',
        'storage',
        'bootstrap',
        'resources',
        'database'
    ];

    /**
     * امتدادات الملفات المسموح بها
     */
    private array $allowedExtensions = [
        'php',
        'css',
        'js',
        'svg',
        'json',
        'sql'
    ];

    /**
     * التحقق من صحة المسار
     */
    public function isValidPath(string $path): bool
    {
        // تنظيف المسار
        $path = $this->cleanPath($path);
        
        // التحقق من أن المسار ليس فارغاً
        if (empty($path)) {
            return false;
        }

        // التحقق من عدم وجود محاولة للخروج من المسار الرئيسي
        if (str_contains($path, '..')) {
            return false;
        }

        // التحقق من المسار الرئيسي
        $rootPath = $this->getRootSegment($path);
        if (!in_array($rootPath, $this->allowedPaths)) {
            return false;
        }

        // التحقق من امتداد الملف إذا كان موجوداً
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        if ($extension && !in_array(strtolower($extension), $this->allowedExtensions)) {
            return false;
        }

        return true;
    }

    /**
     * التحقق من وجود المسار وإمكانية الوصول إليه
     */
    public function exists(string $path): bool
    {
        if (!$this->isValidPath($path)) {
            return false;
        }

        $fullPath = $this->getFullPath($path);
        return file_exists($fullPath) && is_readable($fullPath);
    }

    /**
     * الحصول على المسار الكامل
     */
    public function getFullPath(string $path): string
    {
        $path = $this->cleanPath($path);
        return dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . $path;
    }

    /**
     * تنظيف المسار
     */
    private function cleanPath(string $path): string
    {
        // إزالة المسافات الزائدة
        $path = trim($path);
        
        // توحيد فواصل المسار
        $path = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path);
        
        // إزالة الفواصل المتكررة
        $path = preg_replace('#' . preg_quote(DIRECTORY_SEPARATOR, '#') . '+#', DIRECTORY_SEPARATOR, $path);
        
        // إزالة الفاصلة في البداية إذا وجدت
        return ltrim($path, DIRECTORY_SEPARATOR);
    }

    /**
     * الحصول على الجزء الرئيسي من المسار
     */
    private function getRootSegment(string $path): string
    {
        $segments = explode(DIRECTORY_SEPARATOR, $this->cleanPath($path));
        return $segments[0] ?? '';
    }

    /**
     * إضافة مسار مسموح به
     */
    public function addAllowedPath(string $path): void
    {
        $path = trim($path);
        if (!empty($path) && !in_array($path, $this->allowedPaths)) {
            $this->allowedPaths[] = $path;
        }
    }

    /**
     * إضافة امتداد مسموح به
     */
    public function addAllowedExtension(string $extension): void
    {
        $extension = strtolower(trim($extension));
        if (!empty($extension) && !in_array($extension, $this->allowedExtensions)) {
            $this->allowedExtensions[] = $extension;
        }
    }
} 