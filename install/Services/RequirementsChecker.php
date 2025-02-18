<?php

namespace Install\Services;

class RequirementsChecker
{
    private $basePath;
    private $requirements;

    public function __construct()
    {
        $this->basePath = dirname(dirname(__DIR__));
        $this->requirements = [
            'php' => [
                'version' => '8.1.0',
                'extensions' => [
                    'bcmath',
                    'ctype',
                    'fileinfo',
                    'json',
                    'mbstring',
                    'openssl',
                    'pdo',
                    'tokenizer',
                    'xml',
                    'curl',
                    'gd'
                ]
            ],
            'mysql' => [
                'min_version' => '5.7.0'
            ],
            'apache' => [
                'modules' => [
                    'mod_rewrite',
                    'mod_headers'
                ]
            ],
            'directories' => [
                'storage/app' => '755',
                'storage/framework' => '755',
                'storage/logs' => '755',
                'bootstrap/cache' => '755',
                'public/uploads' => '755'
            ]
        ];
    }

    public function check()
    {
        $results = [
            'php' => $this->checkPHP(),
            'mysql' => $this->checkMySQL(),
            'apache' => $this->checkApache(),
            'extensions' => $this->checkExtensions(),
            'directories' => $this->checkDirectories(),
        ];

        // تحقق من جميع النتائج وتأكد من وجود مفتاح status
        $allMet = true;
        foreach ($results as $key => $result) {
            // إذا كان المفتاح status غير موجود، أضفه مع القيمة false
            if (!isset($result['status'])) {
                $results[$key]['status'] = false;
                $allMet = false;
            } elseif (!$result['status']) {
                $allMet = false;
            }
        }
        
        $results['allMet'] = $allMet;
        return $results;
    }

    private function checkPHP()
    {
        $minVersion = $this->requirements['php']['version'];
        return [
            'name' => 'PHP',
            'current' => PHP_VERSION,
            'required' => $minVersion,
            'status' => version_compare(PHP_VERSION, $minVersion, '>=')
        ];
    }

    private function checkMySQL()
    {
        try {
            $pdo = new \PDO(
                "mysql:host=localhost", 
                'root',
                ''
            );
            $version = $pdo->query('select version()')->fetchColumn();
            return [
                'name' => 'MySQL',
                'current' => $version,
                'required' => $this->requirements['mysql']['min_version'],
                'status' => version_compare($version, $this->requirements['mysql']['min_version'], '>=')
            ];
        } catch (\Exception $e) {
            // إضافة تفاصيل الاستثناء هنا
            return [
                'name' => 'MySQL',
                'current' => 'غير متصل',
                'required' => $this->requirements['mysql']['min_version'],
                'status' => false,
                'error' => $e->getMessage() // إضافة رسالة الخطأ
            ];
        }
    }

    private function checkApache()
    {
        $modules = $this->requirements['apache']['modules'];
        $loaded = [];

        // التأكد من وجود دالة apache_get_modules
        if (function_exists('apache_get_modules')) {
            $loadedModules = apache_get_modules();
            foreach ($modules as $module) {
                $loaded[$module] = in_array($module, $loadedModules);
            }
        } else {
            // إذا كانت الدالة غير موجودة نفترض أن الوحدات غير متوافقة
            foreach ($modules as $module) {
                $loaded[$module] = false;
            }
        }
        
        return [
            'name' => 'Apache Modules',
            'modules' => $loaded,
            'status' => !in_array(false, $loaded)
        ];
    }

    private function checkExtensions()
    {
        $required = $this->requirements['php']['extensions'];
        $results = [];
        foreach ($required as $extension) {
            $results[$extension] = extension_loaded($extension);
        }
        return [
            'name' => 'PHP Extensions',
            'extensions' => $results,
            'status' => !in_array(false, $results)
        ];
    }

    private function checkDirectories()
    {
        $directories = $this->requirements['directories'];
        $results = [];
        foreach ($directories as $directory => $permission) {
            $path = $this->basePath . '/' . $directory;

            // التحقق من وجود المجلدات
            if (!file_exists($path)) {
                $results[$directory] = [
                    'path' => $path,
                    'error' => 'المجلد غير موجود',
                    'writable' => false,
                    'status' => false,
                    'required_permission' => $permission,
                    'current_permission' => '000'
                ];
            } else {
                $isWritable = is_writable($path);
                $currentPermission = $this->getPermission($path);

                $results[$directory] = [
                    'path' => $path,
                    'writable' => $isWritable,
                    'required_permission' => $permission,
                    'current_permission' => $currentPermission,
                    'status' => $isWritable && $currentPermission === $permission
                ];
            }
        }

        $writableStatus = true;
        foreach ($results as $result) {
            if (!$result['status']) {
                $writableStatus = false;
                break;
            }
        }

        return [
            'name' => 'Directory Permissions',
            'directories' => $results,
            'status' => $writableStatus
        ];
    }

    private function getPermission($path)
    {
        if (!file_exists($path)) {
            return '000';
        }
        return substr(sprintf('%o', fileperms($path)), -4);
    }

    public function getRequiredDirectories()
    {
        return $this->requirements['directories'];
    }

    public function getMinimumPHPVersion()
    {
        return $this->requirements['php']['version'];
    }

    public function getMinimumMySQLVersion()
    {
        return $this->requirements['mysql']['min_version'];
    }
}
