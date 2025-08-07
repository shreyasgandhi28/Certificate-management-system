<?php

namespace App\Notifications;

use App\Models\Applicant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicantSubmittedNotification extends Notification implements ShouldQueue
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
            ->subject('We received your application')
            ->greeting('Hello ' . $this->applicant->name . ',')
            ->line('Thank you for submitting your application. Our team will verify your documents shortly.')
            ->line('You can revisit your application using your unique link:')
            ->action('View Application', route('apply.show', $this->applicant->token))
            ->line('Application ID: ' . str_pad((string) $this->applicant->id, 6, '0', STR_PAD_LEFT));
    }
}


