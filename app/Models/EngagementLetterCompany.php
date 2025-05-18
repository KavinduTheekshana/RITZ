<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EngagementLetterCompany extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'content',
        'file_path',
        'file_name',
        'sent_at',
        'sent_by',
        'signer_full_name',
        'signer_print_name',
        'signer_email',
        'signed_date',
        'ip',
        'browser_data',
        'signed_at',
        'signed_file_path',
        'is_signed',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'signed_date' => 'date',
        'signed_at' => 'datetime',
        'is_signed' => 'boolean',
        'browser_data' => 'array', // Cast browser_data to array for easy access
    ];

    /**
     * Get the company that owns the engagement letter.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Scope to get only signed engagement letters.
     */
    public function scopeSigned($query)
    {
        return $query->where('is_signed', true);
    }

    /**
     * Scope to get only unsigned engagement letters.
     */
    public function scopeUnsigned($query)
    {
        return $query->where('is_signed', false);
    }

    /**
     * Get the file URL for the original or signed document.
     */
    public function getFileUrlAttribute()
    {
        if ($this->is_signed && $this->signed_file_path) {
            return asset('storage/' . $this->signed_file_path);
        }

        return asset('storage/' . $this->file_path);
    }

    /**
     * Get the download filename.
     */
    public function getDownloadFilenameAttribute()
    {
        $companyName = str_replace(' ', '_', $this->company->company_name);
        $suffix = $this->is_signed ? '_signed' : '';

        return "engagement_letter_{$companyName}{$suffix}.pdf";
    }
}
