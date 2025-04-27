<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SelfAutoEnrolmentDetails extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'self_auto_enrolment_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'self_assessment_id',
        'latest_action',
        'latest_action_date',
        'records_received',
        'progress_note',
        'staging',
        'postponement_date',
        'tpr_opt_out_date',
        're_enrolment_date',
        'pension_provider',
        'pension_id',
        'declaration_of_compliance_due',
        'declaration_of_compliance_submission',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'latest_action_date' => 'date',
        'records_received' => 'date',
        'staging' => 'date',
        'postponement_date' => 'date',
        'tpr_opt_out_date' => 'date',
        're_enrolment_date' => 'date',
        'declaration_of_compliance_due' => 'date',
        'declaration_of_compliance_submission' => 'date',
    ];

    /**
     * Get the self assessment that owns these auto enrolment details.
     */
    public function selfAssessment(): BelongsTo
    {
        return $this->belongsTo(SelfAssessment::class);
    }
}