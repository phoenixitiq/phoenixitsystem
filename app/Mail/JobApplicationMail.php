<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\JobApplication;

class JobApplicationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $subject;

    public function __construct(JobApplication $application, string $subject)
    {
        $this->application = $application;
        $this->subject = $subject;
    }

    public function build()
    {
        return $this->subject($this->subject)
                    ->view('emails.job-application')
                    ->with([
                        'application' => $this->application,
                        'subject' => $this->subject
                    ]);
    }
} 