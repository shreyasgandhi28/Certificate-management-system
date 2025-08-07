<?php

namespace App\Jobs;

use App\Models\Certificate;
use App\Notifications\CertificateGeneratedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SendCertificateEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Certificate $certificate)
    {
    }

    public function handle(): void
    {
        $applicant = $this->certificate->applicant;

        try {
            Notification::route('mail', $applicant->email)
                ->notify(new CertificateGeneratedNotification($this->certificate));

            $this->certificate->forceFill([
                'email_sent_at' => now(),
                'status' => 'sent_email',
                'send_attempts' => $this->certificate->send_attempts + 1,
                'last_error' => null,
                'last_attempt_at' => now(),
            ])->save();
        } catch (\Throwable $e) {
            $this->certificate->forceFill([
                'send_attempts' => $this->certificate->send_attempts + 1,
                'last_error' => $e->getMessage(),
                'last_attempt_at' => now(),
                'status' => 'failed',
            ])->save();
            throw $e; // Let retry logic handle
        }
    }
}


