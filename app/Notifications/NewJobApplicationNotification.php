<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\JobApplication;

class NewJobApplicationNotification extends Notification
{
    use Queueable;

    protected $application;

    public function __construct(JobApplication $application)
    {
        $this->application = $application;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('طلب توظيف جديد')
            ->line("تم استلام طلب توظيف جديد من {$this->application->name}")
            ->line("الوظيفة: {$this->application->career->title_ar}")
            ->line("البريد الإلكتروني: {$this->application->email}")
            ->line("رقم الجوال: {$this->application->phone}")
            ->action('عرض الطلب', route('admin.applications.show', $this->application->id))
            ->line('شكراً لك');
    }
} 