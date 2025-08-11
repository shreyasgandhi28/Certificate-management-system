<?php

namespace App\Notifications;

use App\Models\UserInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserInvitationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public UserInvitation $invitation)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = url('/register?token=' . $this->invitation->token);
        return (new MailMessage)
            ->subject('You are invited to join')
            ->greeting('Hello ' . $this->invitation->name)
            ->line('You have been invited to join the Certificate Management System.')
            ->action('Accept Invitation', $url)
            ->line('This link expires on ' . $this->invitation->expires_at->toDayDateTimeString());
    }
}


