<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SelfRegistrationDetails extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'self_registration_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'self_assessment_id',
        'terms_signed_registration_fee_paid',
        'fee',
        'letter_of_engagement_signed',
        'money_laundering_complete',
        'registration_64_8',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'terms_signed_registration_fee_paid' => 'boolean',
        'fee' => 'decimal:2',
        'letter_of_engagement_signed' => 'date',
        'money_laundering_complete' => 'boolean',
        'registration_64_8' => 'date',
    ];

    /**
     * Get the self assessment that owns these registration details.
     */
    public function selfAssessment(): BelongsTo
    {
        return $this->belongsTo(SelfAssessment::class);
    }
}