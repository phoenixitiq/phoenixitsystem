<?php

namespace App\Services\Backup;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use ZipArchive;

class BackupService
{
    protected $backupPath;
    protected $maxBackups;

    public function __construct()
    {
        $this->backupPath = config('backup.path');
        $this->maxBackups = config('backup.max_files', 5);
    }

    public function create()
    {
        $filename = 'backup_' . date('Y-m-d_H-i-s');
        
        // نسخ قاعدة البيانات
        $this->backupDatabase($filename);
        
        // نسخ الملفات
        $this->backupFiles($filename);
        
        // حذف النسخ القديمة
        $this->cleanOldBackups();
        
        return $filename;
    }

    protected function backupDatabase($filename)
    {
        $command = sprintf(
            'mysqldump -u%s -p%s %s > %s/%s.sql',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            $this->backupPath,
            $filename
        );
        
        exec($command);
    }

    protected function backupFiles($filename)
    {
        $zip = new ZipArchive();
        $zip->open($this->backupPath . '/' . $filename . '.zip', ZipArchive::CREATE);
        
        $directories = config('backup.directories', []);
        foreach ($directories as $directory) {
            $this->addDirectoryToZip($zip, storage_path($directory));
        }
        
        $zip->close();
    }

    protected function cleanOldBackups()
    {
        $files = glob($this->backupPath . '/*');
        if (count($files) > $this->maxBackups) {
            array_map('unlink', array_slice($files, 0, count($files) - $this->maxBackups));
        }
    }

    protected function addDirectoryToZip($zip, $path)
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $zip->addFile($file->getRealPath(), $file->getRelativePathname());
            }
        }
    }
} 