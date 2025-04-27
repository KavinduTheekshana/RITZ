<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SelfPayeDetails extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'self_assessment_id',
        'employers_reference',
        'accounts_office_reference',
        'years_required',
        'paye_frequency',
        'irregular_monthly_pay',
        'nil_eps',
        'number_of_employees',
        'salary_details',
        'first_pay_date',
        'rti_deadline',
        'paye_scheme_ceased',
        'paye_latest_action',
        'paye_latest_action_date',
        'paye_records_received',
        'paye_progress_note',
        'general_notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'irregular_monthly_pay' => 'boolean',
        'nil_eps' => 'boolean',
        'number_of_employees' => 'integer',
        'first_pay_date' => 'date',
        'rti_deadline' => 'date',
        'paye_scheme_ceased' => 'date',
        'paye_latest_action_date' => 'date',
        'paye_records_received' => 'date',
    ];

    /**
     * Get the self assessment that owns these PAYE details.
     */
    public function selfAssessment(): BelongsTo
    {
        return $this->belongsTo(SelfAssessment::class);
    }
}