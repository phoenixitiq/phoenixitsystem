<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            self::logActivity('create', "تم إنشاء {$model->getTable()} جديد");
        });

        static::updated(function ($model) {
            self::logActivity('update', "تم تحديث {$model->getTable()}");
        });

        static::deleted(function ($model) {
            self::logActivity('delete', "تم حذف {$model->getTable()}");
        });
    }

    protected static function logActivity($action, $description)
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now()
        ]);
    }
} 