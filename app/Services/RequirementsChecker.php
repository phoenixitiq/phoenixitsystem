<?php

namespace App\Services;

class RequirementsChecker
{
    public function check()
    {
        return [
            'PHP Version' => [
                'PHP >= 8.1' => version_compare(PHP_VERSION, '8.1.0', '>='),
            ],
            'PHP Extensions' => [
                'BCMath' => extension_loaded('bcmath'),
                'Ctype' => extension_loaded('ctype'),
                'JSON' => extension_loaded('json'),
                'Mbstring' => extension_loaded('mbstring'),
                'OpenSSL' => extension_loaded('openssl'),
                'PDO' => extension_loaded('pdo'),
                'Tokenizer' => extension_loaded('tokenizer'),
                'XML' => extension_loaded('xml'),
                'cURL' => extension_loaded('curl'),
                'GD' => extension_loaded('gd'),
            ],
            'Directory Permissions' => [
                'storage/app' => is_writable(storage_path('app')),
                'storage/framework' => is_writable(storage_path('framework')),
                'storage/logs' => is_writable(storage_path('logs')),
                'bootstrap/cache' => is_writable(base_path('bootstrap/cache')),
            ]
        ];
    }

    public function canProceed()
    {
        $requirements = $this->check();
        
        foreach ($requirements as $group => $items) {
            foreach ($items as $check => $passed) {
                if (!$passed) {
                    return false;
                }
            }
        }
        
        return true;
    }

    private function checkPHPversion()
    {
        return [
            'required' => '8.1.0',
            'current' => PHP_VERSION,
            'status' => version_compare(PHP_VERSION, '8.1.0', '>=')
        ];
    }

    private function checkExtensions()
    {
        $required = [
            'openssl',
            'pdo',
            'mbstring',
            'tokenizer',
            'xml',
            'curl',
            'gd',
            'zip'
        ];

        $results = [];
        foreach ($required as $extension) {
            $results[$extension] = extension_loaded($extension);
        }

        return $results;
    }

    private function checkDirectories()
    {
        $directories = [
            'storage/app' => '755',
            'storage/framework' => '755',
            'storage/logs' => '755',
            'bootstrap/cache' => '755',
            'public/uploads' => '755'
        ];

        $results = [];
        foreach ($directories as $directory => $permission) {
            $results[$directory] = [
                'exists' => file_exists(base_path($directory)),
                'writable' => is_writable(base_path($directory)),
                'permission' => substr(sprintf('%o', fileperms(base_path($directory))), -4)
            ];
        }

        return $results;
    }

    private function checkEnvironment()
    {
        return [
            'env_file' => file_exists(base_path('.env')),
            'key_generated' => config('app.key') !== null,
            'debug_mode' => config('app.debug'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
        ];
    }

    public function checkHRRequirements()
    {
        return [
            'work_shifts_enabled' => true,
            'multi_salary_payment' => true,
            'advance_payment' => true,
            'overtime_calculation' => true
        ];
    }

    public function checkFinancialRequirements()
    {
        return [
            'multi_currency' => true,
            'salary_management' => true,
            'advance_management' => true,
            'payment_tracking' => true
        ];
    }
} 