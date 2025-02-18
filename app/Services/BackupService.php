<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use ZipArchive;
use Spatie\Backup\Tasks\Backup\BackupJob;

class BackupService
{
    protected $backupPath;
    protected $maxBackups;

    public function __construct()
    {
        $this->backupPath = storage_path('backups');
        $this->maxBackups = config('backup.max_backups', 5);
    }

    public function create()
    {
        $filename = 'backup-' . Carbon::now()->format('Y-m-d-H-i-s') . '.sql';
        $tables = $this->getTables();
        $contents = $this->generateBackupContents($tables);
        
        Storage::disk('backups')->put($filename, $contents);
        
        return $filename;
    }

    private function getTables()
    {
        return DB::select('SHOW TABLES');
    }

    private function generateBackupContents($tables)
    {
        $contents = '';
        
        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            $contents .= $this->getTableBackup($tableName);
        }
        
        return $contents;
    }

    private function getTableBackup($table)
    {
        $structure = DB::select("SHOW CREATE TABLE `{$table}`")[0];
        $data = DB::table($table)->get();
        
        $backup = "DROP TABLE IF EXISTS `{$table}`;\n\n";
        $backup .= array_values((array)$structure)[1] . ";\n\n";
        
        if ($data->count()) {
            $backup .= $this->getTableDataBackup($table, $data);
        }
        
        return $backup;
    }

    private function getTableDataBackup($table, $data)
    {
        $backup = '';
        foreach ($data->chunk(100) as $chunk) {
            $backup .= $this->generateInsertStatements($table, $chunk);
        }
        return $backup;
    }

    private function generateInsertStatements($table, $records)
    {
        $values = [];
        foreach ($records as $record) {
            $values[] = '(' . implode(',', array_map(function ($value) {
                return is_null($value) ? 'NULL' : "'" . addslashes($value) . "'";
            }, (array)$record)) . ')';
        }
        
        return "INSERT INTO `{$table}` VALUES " . implode(",\n", $values) . ";\n\n";
    }

    public function restore($filename)
    {
        // فك تشفير النسخة
        $this->decryptBackup($filename);
        
        // استعادة قاعدة البيانات
        $this->restoreDatabase($filename);
        
        // استعادة الملفات
        $this->restoreFiles($filename);
    }

    public function schedule()
    {
        // جدولة نسخ احتياطية يومية
        $schedule->command('backup:clean')->daily()->at('01:00');
        $schedule->command('backup:run')->daily()->at('02:00');
    }

    public function importFromDaftra($daftraBackupPath)
    {
        try {
            // التحقق من صحة الملف
            if (!file_exists($daftraBackupPath)) {
                throw new \Exception('ملف النسخة الاحتياطية غير موجود');
            }

            // فك ضغط الملف إذا كان مضغوطاً
            $extractedPath = $this->extractDaftraBackup($daftraBackupPath);

            // قراءة محتوى النسخة الاحتياطية
            $daftraData = $this->parseDaftraBackup($extractedPath);

            DB::beginTransaction();

            // تحويل وإدخال البيانات
            $this->importUsers($daftraData['users'] ?? []);
            $this->importEmployees($daftraData['employees'] ?? []);
            $this->importSettings($daftraData['settings'] ?? []);
            $this->importDocuments($daftraData['documents'] ?? []);

            DB::commit();

            // تنظيف الملفات المؤقتة
            $this->cleanup($extractedPath);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('فشل استيراد النسخة الاحتياطية: ' . $e->getMessage());
        }
    }

    private function extractDaftraBackup($backupPath)
    {
        $zip = new ZipArchive;
        $extractPath = storage_path('app/temp/daftra_import_' . time());

        if ($zip->open($backupPath) === TRUE) {
            $zip->extractTo($extractPath);
            $zip->close();
            return $extractPath;
        }

        throw new \Exception('فشل فك ضغط ملف النسخة الاحتياطية');
    }

    private function parseDaftraBackup($path)
    {
        $data = [];

        // قراءة ملف المستخدمين
        if (file_exists($path . '/users.json')) {
            $data['users'] = json_decode(file_get_contents($path . '/users.json'), true);
        }

        // قراءة ملف الموظفين
        if (file_exists($path . '/employees.json')) {
            $data['employees'] = json_decode(file_get_contents($path . '/employees.json'), true);
        }

        // قراءة ملف الإعدادات
        if (file_exists($path . '/settings.json')) {
            $data['settings'] = json_decode(file_get_contents($path . '/settings.json'), true);
        }

        // قراءة المستندات والمرفقات
        if (is_dir($path . '/documents')) {
            $data['documents'] = $this->scanDocuments($path . '/documents');
        }

        return $data;
    }

    private function importUsers($users)
    {
        foreach ($users as $userData) {
            // تحويل بيانات المستخدم لتتوافق مع نظامنا
            $user = [
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => bcrypt($userData['password'] ?? '123456'),
                'phone' => $userData['phone'] ?? null,
                'role' => $this->mapDaftraRole($userData['role']),
                'is_active' => $userData['is_active'] ?? true,
                'created_at' => $userData['created_at'] ?? now(),
                'updated_at' => now()
            ];

            DB::table('users')->updateOrInsert(
                ['email' => $user['email']],
                $user
            );
        }
    }

    private function importEmployees($employees)
    {
        foreach ($employees as $employeeData) {
            // تحويل بيانات الموظف لتتوافق مع نظامنا
            $employee = [
                'user_id' => $this->getUserIdByEmail($employeeData['email']),
                'department' => $employeeData['department'],
                'position' => $employeeData['position'],
                'join_date' => $employeeData['join_date'],
                'salary' => $employeeData['salary'],
                'created_at' => $employeeData['created_at'] ?? now(),
                'updated_at' => now()
            ];

            DB::table('employees')->updateOrInsert(
                ['user_id' => $employee['user_id']],
                $employee
            );
        }
    }

    private function importSettings($settings)
    {
        foreach ($settings as $key => $value) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                [
                    'value' => $value,
                    'updated_at' => now()
                ]
            );
        }
    }

    private function importDocuments($documents)
    {
        foreach ($documents as $doc) {
            if (file_exists($doc['path'])) {
                $newPath = 'uploads/' . basename($doc['path']);
                Storage::disk('public')->put(
                    $newPath,
                    file_get_contents($doc['path'])
                );
            }
        }
    }

    private function mapDaftraRole($role)
    {
        $roleMap = [
            'admin' => 'admin',
            'super_admin' => 'super-admin',
            'user' => 'user',
            'employee' => 'employee'
        ];

        return $roleMap[$role] ?? 'user';
    }

    private function getUserIdByEmail($email)
    {
        return DB::table('users')
            ->where('email', $email)
            ->value('id');
    }

    private function cleanup($path)
    {
        if (is_dir($path)) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );

            foreach ($files as $fileinfo) {
                $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
                $todo($fileinfo->getRealPath());
            }

            rmdir($path);
        }
    }

    public function createBackup()
    {
        try {
            $date = Carbon::now()->format('Y-m-d_H-i-s');
            $filename = "backup_{$date}.zip";
            
            $zip = new ZipArchive();
            $zip->open($this->backupPath . '/' . $filename, ZipArchive::CREATE);

            // نسخ قاعدة البيانات
            $this->backupDatabase($zip);

            // نسخ الملفات
            $this->backupFiles($zip);

            $zip->close();

            // تنظيف النسخ القديمة
            $this->cleanOldBackups();

            // تسجيل النسخة الاحتياطية
            DB::table('backups')->insert([
                'filename' => $filename,
                'type' => 'full',
                'size' => filesize($this->backupPath . '/' . $filename),
                'status' => 'completed',
                'created_at' => now()
            ]);

            return $filename;

        } catch (\Exception $e) {
            \Log::error('Backup failed: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function backupDatabase($zip)
    {
        $tables = DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            $data = DB::table($tableName)->get();
            $zip->addFromString(
                "database/{$tableName}.json",
                json_encode($data)
            );
        }
    }

    protected function backupFiles($zip)
    {
        $directories = [
            'uploads',
            'documents',
            'reports'
        ];

        foreach ($directories as $dir) {
            $this->addDirectoryToZip($zip, storage_path($dir), $dir);
        }
    }

    protected function addDirectoryToZip($zip, $path, $relativePath = '')
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $zip->addFile(
                    $filePath,
                    $relativePath . '/' . substr($filePath, strlen($path) + 1)
                );
            }
        }
    }

    protected function encryptLatestBackup()
    {
        // Implementation of encryptLatestBackup method
    }

    protected function uploadToCloud()
    {
        // Implementation of uploadToCloud method
    }

    protected function decryptBackup($filename)
    {
        // Implementation of decryptBackup method
    }

    protected function restoreDatabase($filename)
    {
        // Implementation of restoreDatabase method
    }

    protected function restoreFiles($filename)
    {
        // Implementation of restoreFiles method
    }
} 