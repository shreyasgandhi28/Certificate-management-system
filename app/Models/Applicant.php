<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Applicant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'gender',
        'date_of_birth',
        'token',
        'status',
        'educational_details',
        'notes',
        'submitted_at',
        'verification_started_at',
        'verification_started_by',
        'verification_completed_at',
        'verification_completed_by',
        'verification_notes',
        'rejected_at',
        'rejected_by',
        'rejection_reason',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'educational_details' => 'array',
        'submitted_at' => 'datetime',
        'verification_started_at' => 'datetime',
        'verification_completed_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->token)) {
                $model->token = Str::random(64);
            }
            if (empty($model->submitted_at)) {
                $model->submitted_at = now();
            }
        });
    }

    public function uploads(): HasMany
    {
        return $this->hasMany(Upload::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function latestCertificate(): HasOne
    {
        return $this->hasOne(Certificate::class)->latest();
    }

    public function getUploadByType(string $type): ?Upload
    {
        return $this->uploads()->where('type', $type)->first();
    }

    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }

    public function hasCertificate(): bool
    {
        return $this->certificates()->exists();
    }

    public function getVerifiedUploadsCount(): int
    {
        return $this->uploads()->where('verification_status', 'verified')->count();
    }

    public function getTotalUploadsCount(): int
    {
        return $this->uploads()->count();
    }

    public function getPublicUrl(): string
    {
        return route('apply.show', $this->token);
    }
}
