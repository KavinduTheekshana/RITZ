<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SelfP11dDetails extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'self_p11d_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'self_assessment_id',
        'next_p11d_return_due',
        'latest_p11d_submitted',
        'latest_action',
        'latest_action_date',
        'records_received',
        'progress_note',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'next_p11d_return_due' => 'date',
        'latest_p11d_submitted' => 'date',
        'latest_action_date' => 'date',
        'records_received' => 'date',
    ];

    /**
     * Get the self assessment that owns these P11D details.
     */
    public function selfAssessment(): BelongsTo
    {
        return $this->belongsTo(SelfAssessment::class);
    }
}