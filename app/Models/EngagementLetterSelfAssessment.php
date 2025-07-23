<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EngagementLetterSelfAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'self_assessment_id',
        'content',
        'file_name',
        'file_path',
        'sent_at',
        'sent_by',
        'signer_full_name',
        'signer_print_name',
        'signer_email',
        'ip',
        'browser_data',
        'signed_date',
        'signed_at',
        'signed_file_path',
        'is_signed',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'signed_at' => 'datetime',
        'signed_date' => 'date',
        'is_signed' => 'boolean',
    ];

    /**
     * Get the self assessment that owns this engagement letter.
     */
    public function selfAssessment(): BelongsTo
    {
        return $this->belongsTo(SelfAssessment::class);
    }

    /**
     * Check if this engagement letter is signed.
     */
    public function isSigned(): bool
    {
        return $this->is_signed;
    }

    /**
     * Get the file URL if there's a PDF.
     */
    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }

    /**
     * Get the signed file URL if there's a signed PDF.
     */
    public function getSignedFileUrlAttribute(): ?string
    {
        return $this->signed_file_path ? asset('storage/' . $this->signed_file_path) : null;
    }

    /**
     * Sign the engagement letter.
     */
    public function sign(array $signatureData): bool
    {
        $this->fill([
            'signer_full_name' => $signatureData['signer_full_name'],
            'signer_print_name' => $signatureData['signer_print_name'],
            'signer_email' => $signatureData['signer_email'],
            'ip' => $signatureData['ip'],
            'browser_data' => $signatureData['browser_data'],
            'signed_date' => $signatureData['signed_date'],
            'signed_at' => now(),
            'is_signed' => true,
        ]);

        return $this->save();
    }
}