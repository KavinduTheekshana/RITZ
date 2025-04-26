<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoEnrolmentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
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
        'declaration_of_compliance_submission'
    ];

    protected $casts = [
        'latest_action_date' => 'date',
        'records_received' => 'date',
        'staging' => 'date',
        'postponement_date' => 'date',
        'tpr_opt_out_date' => 'date',
        're_enrolment_date' => 'date',
        'declaration_of_compliance_due' => 'date',
        'declaration_of_compliance_submission' => 'date'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
