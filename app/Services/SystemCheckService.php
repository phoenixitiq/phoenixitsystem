<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

class SystemCheckService
{
    public function runDiagnostics()
    {
        $results = [
            'system' => $this->checkSystem(),
            'database' => $this->checkDatabase(),
            'storage' => $this->checkStorage(),
            'cache' => $this->checkCache(),
            'email' => $this->checkEmail(),
            'services' => $this->checkServices(),
            'security' => $this->checkSecurity(),
            'modules' => $this->checkModules(),
        ];

        return [
            'success' => !in_array(false, array_column($results, 'status')),
            'results' => $results
        ];
    }

    private function checkSystem()
    {
        $checks = [
            'php_version' => version_compare(PHP_VERSION, '8.1.0', '>='),
            'extensions' => [
                'pdo_mysql' => extension_loaded('pdo_mysql'),
                'openssl' => extension_loaded('openssl'),
                'mbstring' => extension_loaded('mbstring'),
                'tokenizer' => extension_loaded('tokenizer'),
                'xml' => extension_loaded('xml'),
                'ctype' => extension_loaded('ctype'),
                'json' => extension_loaded('json'),
                'gd' => extension_loaded('gd'),
                'zip' => extension_loaded('zip'),
                'curl' => extension_loaded('curl')
            ],
            'permissions' => [
                'storage/app' => is_writable(storage_path('app')),
                'storage/framework' => is_writable(storage_path('framework')),
                'storage/logs' => is_writable(storage_path('logs')),
                'bootstrap/cache' => is_writable(base_path('bootstrap/cache'))
            ],
            'env' => file_exists(base_path('.env'))
        ];

        return [
            'status' => !in_array(false, array_merge(
                [$checks['php_version']],
                $checks['extensions'],
                $checks['permissions'],
                [$checks['env']]
            )),
            'details' => $checks
        ];
    }

    private function checkDatabase()
    {
        try {
            // فحص الاتصال بقاعدة البيانات
            DB::connection()->getPdo();
            
            // فحص الجداول الرئيسية
            $requiredTables = [
                'users', 'roles', 'permissions', 'settings', 
                'employees', 'departments', 'attendance',
                'leaves', 'documents', 'backups'
            ];
            
            $existingTables = collect(DB::select('SHOW TABLES'))->map(function($val) {
                return array_values((array)$val)[0];
            })->toArray();

            $missingTables = array_diff($requiredTables, $existingTables);

            return [
                'status' => empty($missingTables),
                'details' => [
                    'connection' => true,
                    'missing_tables' => $missingTables
                ]
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'details' => [
                    'connection' => false,
                    'error' => $e->getMessage()
                ]
            ];
        }
    }

    private function checkStorage()
    {
        $disks = ['local', 'public', 's3'];
        $results = [];

        foreach ($disks as $disk) {
            try {
                $results[$disk] = [
                    'status' => Storage::disk($disk)->exists('.gitignore'),
                    'writable' => Storage::disk($disk)->put('test.txt', 'test'),
                    'readable' => Storage::disk($disk)->exists('test.txt'),
                    'deletable' => Storage::disk($disk)->delete('test.txt')
                ];
            } catch (\Exception $e) {
                $results[$disk] = [
                    'status' => false,
                    'error' => $e->getMessage()
                ];
            }
        }

        return [
            'status' => !in_array(false, array_column($results, 'status')),
            'details' => $results
        ];
    }

    private function checkCache()
    {
        try {
            // فحص Redis إذا كان مستخدماً
            if (config('cache.default') === 'redis') {
                Redis::ping();
            }

            // فحص الكاش
            Cache::put('test', 'test', 1);
            $testCache = Cache::get('test') === 'test';
            Cache::forget('test');

            return [
                'status' => $testCache,
                'details' => [
                    'driver' => config('cache.default'),
                    'working' => $testCache
                ]
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'details' => [
                    'error' => $e->getMessage()
                ]
            ];
        }
    }

    private function checkEmail()
    {
        try {
            $config = config('mail');
            return [
                'status' => !empty($config['host']) && !empty($config['port']),
                'details' => [
                    'driver' => $config['default'],
                    'host' => $config['host'],
                    'port' => $config['port'],
                    'encryption' => $config['encryption']
                ]
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'details' => [
                    'error' => $e->getMessage()
                ]
            ];
        }
    }

    private function checkServices()
    {
        $services = [
            'backup' => $this->checkBackupService(),
            'sync' => $this->checkSyncService(),
            'queue' => $this->checkQueueService(),
            'scheduler' => $this->checkScheduler()
        ];

        return [
            'status' => !in_array(false, array_column($services, 'status')),
            'details' => $services
        ];
    }

    private function checkSecurity()
    {
        $checks = [
            'app_key' => !empty(config('app.key')),
            'debug' => !config('app.debug'),
            'https' => request()->secure(),
            'csrf' => in_array('web', Route::current()->middleware()),
            'passwords' => $this->checkPasswordSecurity(),
            'permissions' => $this->checkFilePermissions()
        ];

        return [
            'status' => !in_array(false, $checks),
            'details' => $checks
        ];
    }

    private function checkModules()
    {
        $modules = [
            'attendance' => $this->checkAttendanceModule(),
            'documents' => $this->checkDocumentsModule(),
            'reports' => $this->checkReportsModule(),
            'api' => $this->checkApiModule()
        ];

        return [
            'status' => !in_array(false, array_column($modules, 'status')),
            'details' => $modules
        ];
    }

    // ... المزيد من الفحوصات التفصيلية لكل وحدة
} 