<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SelfOtherDetails extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'self_other_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'self_assessment_id',
        'referred_by',
        'initial_contact',
        'proposal_email_sent',
        'welcome_email',
        'accounting_system',
        'profession',
        'website',
        'twitter_handle',
        'facebook_url',
        'linkedin_url',
        'instagram_handle',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'initial_contact' => 'date',
        'proposal_email_sent' => 'date',
        'welcome_email' => 'date',
    ];

    /**
     * Get the self assessment that owns these other details.
     */
    public function selfAssessment(): BelongsTo
    {
        return $this->belongsTo(SelfAssessment::class);
    }
}