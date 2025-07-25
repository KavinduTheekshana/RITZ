<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage; 

class SelfAssessmentChatList extends Model
{
    use HasFactory;

    protected $fillable = [
        'self_assessment_id',
        'user_id',
        'client_id',
        'sender_type', // 'admin', 'client', 'system'
        'sender_name',
        'sender_email',
        'message',
        'file_path',
        'file_name',
        'file_size',
        'file_type',
        'is_read',
        'sent_at',
        'requires_signature',
        'is_signed',
        'signer_full_name',
        'signer_print_name',
        'signer_email',
        'signer_ip',
        'signer_browser_data',
        'signed_at',
        'signed_file_path',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'signed_at' => 'datetime',
        'is_read' => 'boolean',
        'is_signed' => 'boolean',
        'requires_signature' => 'boolean',
        'file_size' => 'integer',
    ];

    /**
     * Check if this message needs a signature
     */
    public function needsSignature(): bool
    {
        return $this->requires_signature && !$this->is_signed && $this->file_path;
    }

    /**
     * Get the signed file URL
     */
    public function getSignedFileUrlAttribute()
    {
        if ($this->signed_file_path) {
            return Storage::url($this->signed_file_path);
        }
        return null;
    }

    /**
     * Check if file is a PDF
     */
    public function isPdf(): bool
    {
        return $this->file_type === 'application/pdf';
    }

    /**
     * Get the self assessment that owns this chat message.
     */
    public function selfAssessment(): BelongsTo
    {
        return $this->belongsTo(SelfAssessment::class);
    }

    /**
     * Get the user who sent this message (if sent by admin).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the client who sent this message (if sent by client).
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the file URL if there's an attachment.
     */
    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }

    /**
     * Get formatted file size.
     */
    public function getFormattedFileSizeAttribute(): ?string
    {
        if (!$this->file_size) {
            return null;
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    /**
     * Check if this message has a file attachment.
     */
    public function hasFile(): bool
    {
        return !empty($this->file_path);
    }

    /**
     * Get the sender's display name.
     */
    public function getSenderDisplayNameAttribute(): string
    {
        if ($this->sender_type === 'system') {
            return 'System';
        }

        return $this->sender_name ?: 'Unknown';
    }

    /**
     * Check if message is from admin.
     */
    public function isFromAdmin(): bool
    {
        return $this->sender_type === 'admin';
    }

    /**
     * Check if message is from client.
     */
    public function isFromClient(): bool
    {
        return $this->sender_type === 'client';
    }

    /**
     * Check if message is from system.
     */
    public function isFromSystem(): bool
    {
        return $this->sender_type === 'system';
    }

    /**
 * Get the proper download URL for normal attachments
 */
public function getDownloadUrlAttribute(): ?string
{
    if (!$this->file_path) {
        return null;
    }
    
    // For admin (when accessed from Filament)
    if (auth()->check()) {
        return route('admin.chat.download.self-assessment', $this->id);
    }
    
    // For client
    return route('client.self-assessment.chat.download', $this->id);
}
}