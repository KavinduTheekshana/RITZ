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
        'sent_at',
        'sent_by',
    ];

    /**
     * Get the company that owns the engagement letter.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
