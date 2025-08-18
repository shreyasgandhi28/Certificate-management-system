<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Certificate;

class CertificateEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $certificate;
    public $applicant;
    public $certificateUrl;

    /**
     * Create a new message instance.
     *
     * @param Certificate $certificate
     * @param string $certificateUrl
     */
    public function __construct(Certificate $certificate, string $certificateUrl)
    {
        $this->certificate = $certificate;
        $this->applicant = $certificate->applicant;
        $this->certificateUrl = $certificateUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Your Certificate is Ready - " . config('app.name'))
                    ->view('emails.certificate');
    }
}
