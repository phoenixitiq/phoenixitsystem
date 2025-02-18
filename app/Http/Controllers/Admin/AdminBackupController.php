<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BackupService;
use App\Services\DaftraImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminBackupController extends Controller
{
    protected $backupService;
    protected $daftraImportService;

    public function __construct(BackupService $backupService, DaftraImportService $daftraImportService)
    {
        $this->backupService = $backupService;
        $this->daftraImportService = $daftraImportService;
    }

    public function index()
    {
        $backups = Storage::files('backups');
        return view('admin.backup.index', compact('backups'));
    }

    public function create()
    {
        try {
            $backup = $this->backupService->create();
            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء النسخة الاحتياطية بنجاح',
                'file' => $backup
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء النسخة الاحتياطية'
            ], 500);
        }
    }

    public function restore(Request $request)
    {
        try {
            $file = $request->file('backup_file');
            $result = $this->backupService->restore($file->path());

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم استعادة النسخة الاحتياطية بنجاح'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'فشل في استعادة النسخة الاحتياطية'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء استعادة النسخة الاحتياطية'
            ], 500);
        }
    }

    public function importFromDaftra(Request $request)
    {
        try {
            $file = $request->file('daftra_backup');
            $result = $this->backupService->importFromDaftra($file->path());

            return response()->json([
                'success' => true,
                'message' => 'تم استيراد البيانات من دفترة بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء استيراد البيانات من دفترة'
            ], 500);
        }
    }

    public function importDaftraBackup(Request $request)
    {
        try {
            $request->validate([
                'backup_file' => 'required|file|mimes:zip,json'
            ]);

            $file = $request->file('backup_file');
            $path = $file->storeAs('temp', 'daftra_backup_' . time() . '.' . $file->getClientOriginalExtension());

            $backupService = new BackupService();
            $backupService->importFromDaftra(storage_path('app/' . $path));

            return back()->with('success', 'تم استيراد النسخة الاحتياطية من دفترة بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
} 