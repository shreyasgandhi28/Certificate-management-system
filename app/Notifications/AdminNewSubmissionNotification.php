<?php

namespace App\Notifications;

use App\Models\Applicant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminNewSubmissionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private Applicant $applicant)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New certificate application submitted')
            ->line('Applicant: ' . $this->applicant->name)
            ->line('Email: ' . $this->applicant->email)
            ->line('Phone: ' . $this->applicant->phone)
            ->action('Open Applicant', route('admin.applicants.show', $this->applicant))
            ->line('Submitted at: ' . $this->applicant->submitted_at->format('M d, Y g:i A'));
    }
}


