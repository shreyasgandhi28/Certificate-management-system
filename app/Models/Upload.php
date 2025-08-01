<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Upload extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_id',
        'type',
        'original_filename',
        'stored_filename',
        'file_path',
        'file_hash',
        'mime_type',
        'file_size',
        'verification_status',
        'verifier_id',
        'verification_comments',  
        'verified_at',
        'uploader_ip'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'file_size' => 'integer'
    ];

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verifier_id');
    }

    public function getFileUrl(): string
    {
        return Storage::url($this->file_path);
    }

    public function getFileSizeFormatted(): string
    {
        return $this->formatBytes($this->file_size);
    }

    public function isVerified(): bool
    {
        return $this->verification_status === 'verified';
    }

    public function isPending(): bool
    {
        return $this->verification_status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->verification_status === 'rejected';
    }

    public function getTypeLabel(): string
    {
        return match($this->type) {
            'tenth' => '10th Certificate',
            'twelfth' => '12th Certificate', 
            'graduation' => 'Graduation Certificate',
            'masters' => 'Master\'s Certificate',
            default => 'Unknown'
        };
    }

    private function formatBytes($size, $precision = 2): string
    {
        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');
            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        }
        return $size;
    }
}
