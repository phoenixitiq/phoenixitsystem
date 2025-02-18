<?php

namespace Install;

class RequirementsChecker
{
    private $requirements;

    public function __construct()
    {
        $this->requirements = require __DIR__ . '/config/requirements.php';
    }

    public function check()
    {
        $results = [
            'php' => $this->checkPHP(),
            'extensions' => $this->checkExtensions(),
            'directories' => $this->checkDirectories(),
            'status' => true,
            'errors' => []
        ];

        // التحقق من النتائج
        if (!$results['php']['status']) {
            $results['status'] = false;
            $results['errors'][] = 'إصدار PHP غير متوافق';
        }

        foreach ($results['extensions'] as $extension => $installed) {
            if (!$installed) {
                $results['status'] = false;
                $results['errors'][] = "الإضافة {$extension} غير مثبتة";
            }
        }

        foreach ($results['directories'] as $directory => $writable) {
            if (!$writable) {
                $results['status'] = false;
                $results['errors'][] = "المجلد {$directory} غير قابل للكتابة";
            }
        }

        return $results;
    }

    private function checkPHP()
    {
        return [
            'version' => PHP_VERSION,
            'status' => version_compare(PHP_VERSION, $this->requirements['php'], '>=')
        ];
    }

    private function checkExtensions()
    {
        $results = [];
        foreach ($this->requirements['extensions'] as $extension) {
            $results[$extension] = extension_loaded($extension);
        }
        return $results;
    }

    private function checkDirectories()
    {
        $results = [];
        foreach ($this->requirements['directories'] as $directory) {
            $path = dirname(__DIR__) . '/' . $directory;
            $results[$directory] = is_writable($path);
        }
        return $results;
    }
} 