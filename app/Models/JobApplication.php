<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class JobApplication extends Model
{
    use Notifiable;

    protected $fillable = [
        'position_id',
        'name',
        'email',
        'phone',
        'cv_path',
        'status'
    ];

    public function position()
    {
        return $this->belongsTo(JobPosition::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function getCvAttribute()
    {
        return $this->attachments()
            ->where('mime_type', 'like', 'application/%')
            ->first();
    }

    public function routeNotificationForMail()
    {
        return $this->email;
    }
} 