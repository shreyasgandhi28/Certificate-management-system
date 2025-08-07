<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    protected $fillable = [
        'applicant_id',
        'template_id',
        'serial_number',
        'pdf_path',
        'data',
        'status',
        'generated_by',
        'generated_at',
        'sent_at',
        'email_sent_at',
        'whatsapp_sent_at',
        'send_attempts',
        'last_error',
        'last_attempt_at',
    ];

    protected $casts = [
        'data' => 'array',
        'generated_at' => 'datetime',
        'sent_at' => 'datetime',
        'email_sent_at' => 'datetime',
        'whatsapp_sent_at' => 'datetime',
        'last_attempt_at' => 'datetime',
    ];

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(CertificateTemplate::class, 'template_id');
    }

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
