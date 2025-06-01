<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyChatList extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
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
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'is_read' => 'boolean',
        'file_size' => 'integer',
    ];

    /**
     * Get the company that owns this chat message.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
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
        switch ($this->sender_type) {
            case 'admin':
                return $this->user ? $this->user->name : ($this->sender_name ?? 'Admin');
            case 'client':
                return $this->client ? $this->client->full_name : ($this->sender_name ?? 'Client');
            case 'system':
                return 'System';
            default:
                return $this->sender_name ?? 'Unknown';
        }
    }

    /**
     * Get the sender's avatar color based on type.
     */
    public function getSenderAvatarColorAttribute(): string
    {
        switch ($this->sender_type) {
            case 'admin':
                return 'bg-blue-500';
            case 'client':
                return 'bg-green-500';
            case 'system':
                return 'bg-gray-500';
            default:
                return 'bg-gray-400';
        }
    }

    /**
     * Check if message was sent by admin.
     */
    public function isFromAdmin(): bool
    {
        return $this->sender_type === 'admin';
    }

    /**
     * Check if message was sent by client.
     */
    public function isFromClient(): bool
    {
        return $this->sender_type === 'client';
    }

    /**
     * Scope for admin messages.
     */
    public function scopeFromAdmin($query)
    {
        return $query->where('sender_type', 'admin');
    }

    /**
     * Scope for client messages.
     */
    public function scopeFromClient($query)
    {
        return $query->where('sender_type', 'client');
    }

    /**
     * Scope for messages with files.
     */
    public function scopeWithFiles($query)
    {
        return $query->whereNotNull('file_path');
    }

    /**
     * Scope for unread messages.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}