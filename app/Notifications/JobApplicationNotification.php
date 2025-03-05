<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\JobApplication;

class JobApplicationNotification extends Notification
{
    private $application;

    public function __construct(JobApplication $application)
    {
        $this->application = $application;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('تحديث حالة طلب التوظيف')
            ->line('تم تحديث حالة طلبك للوظيفة: ' . $this->application->position->title)
            ->line('الحالة الجديدة: ' . $this->getStatusInArabic())
            ->action('عرض التفاصيل', url('/applications/' . $this->application->id));
    }

    public function toArray($notifiable)
    {
        return [
            'application_id' => $this->application->id,
            'position_title' => $this->application->position->title,
            'status' => $this->application->status
        ];
    }

    private function getStatusInArabic()
    {
        return match($this->application->status) {
            'pending' => 'قيد المراجعة',
            'reviewed' => 'تمت المراجعة',
            'accepted' => 'مقبول',
            'rejected' => 'مرفوض',
            default => $this->application->status
        };
    }
} 