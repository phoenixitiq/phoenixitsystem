<?php

namespace App\Services;

use App\Models\JobApplication;
use Illuminate\Support\Facades\Mail;
use App\Mail\JobApplicationMail;

class EmailService
{
    public function sendApplicationStatusEmail(JobApplication $application)
    {
        $subject = match($application->status) {
            'accepted' => 'تم قبول طلب التوظيف',
            'rejected' => 'تحديث حول طلب التوظيف',
            default => 'استلام طلب التوظيف'
        };

        Mail::to($application->email)
            ->send(new JobApplicationMail($application, $subject));
    }
} 