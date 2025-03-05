<?php

namespace App\Http\Controllers;

use App\Services\SyncService;
use App\Services\BackupService;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    public function sync()
    {
        $result = app(SyncService::class)->syncSystem();
        
        if ($result['success']) {
            return back()->with('success', $result['message']);
        }
        
        return back()->with('error', $result['error']);
    }

    public function backup()
    {
        try {
            $filename = app(BackupService::class)->createBackup();
            return back()->with('success', 'تم إنشاء نسخة احتياطية بنجاح: ' . $filename);
        } catch (\Exception $e) {
            return back()->with('error', 'فشل إنشاء النسخة الاحتياطية: ' . $e->getMessage());
        }
    }
} 