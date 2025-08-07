<?php

namespace App\Services;

use Twilio\Rest\Client as TwilioClient;

class WhatsAppService
{
    public function __construct(
        private ?TwilioClient $twilio = null
    ) {
        if ($this->twilio === null && config('services.twilio.sid')) {
            $this->twilio = new TwilioClient(
                config('services.twilio.sid'),
                config('services.twilio.token')
            );
        }
    }

    public function sendMessage(string $toPhoneE164, string $message): void
    {
        if (!$this->twilio) {
            return; // no-op in dev if Twilio not configured
        }

        $from = 'whatsapp:' . config('services.twilio.whatsapp_from');
        $to = 'whatsapp:' . $toPhoneE164;

        $this->twilio->messages->create($to, [
            'from' => $from,
            'body' => $message,
        ]);
    }
}


