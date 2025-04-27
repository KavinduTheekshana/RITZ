<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SelfPreviousAccountantDetails extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'self_assessment_id',
        'clearance_required',
        'accountant_email_address',
        'accountant_details',
        'send_first_clearance_email',
        'automatically_request_every',
        'last_requested',
        'information_received',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'clearance_required' => 'boolean',
        'send_first_clearance_email' => 'date',
        'last_requested' => 'date',
        'information_received' => 'boolean',
    ];

    /**
     * Get the self assessment that owns these previous accountant details.
     */
    public function selfAssessment(): BelongsTo
    {
        return $this->belongsTo(SelfAssessment::class);
    }
}