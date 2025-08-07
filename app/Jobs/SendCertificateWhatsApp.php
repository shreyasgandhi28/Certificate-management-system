<?php

namespace App\Jobs;

use App\Models\Certificate;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class SendCertificateWhatsApp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Certificate $certificate)
    {
    }

    public function handle(WhatsAppService $whatsAppService): void
    {
        $applicant = $this->certificate->applicant;

        try {
            $message = "Hello {$applicant->name}, your certificate ({$this->certificate->serial_number}) is ready. You can download it from the email or contact admin for a copy.";

            // Expect applicant phone to be E.164 or convert as needed; here we assume it's already valid
            $whatsAppService->sendMessage($applicant->phone, $message);

            $this->certificate->forceFill([
                'whatsapp_sent_at' => now(),
                'status' => 'sent_whatsapp',
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
            throw $e;
        }
    }
}


