<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SelfAccountsAndReturnsDetails extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'self_assessment_id',
        'accounts_period_end',
        'ch_year_end',
        'hmrc_year_end',
        'ch_accounts_next_due',
        'ct600_due',
        'corporation_tax_amount_due',
        'tax_due_hmrc_year_end',
        'ct_payment_reference',
        'tax_office',
        'companies_house_email_reminder',
        'accounts_latest_action',
        'accounts_latest_action_date',
        'accounts_records_received',
        'accounts_progress_note',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'accounts_period_end' => 'date',
        'ch_year_end' => 'date',
        'hmrc_year_end' => 'date',
        'ch_accounts_next_due' => 'date',
        'ct600_due' => 'date',
        'corporation_tax_amount_due' => 'decimal:2',
        'tax_due_hmrc_year_end' => 'date',
        'companies_house_email_reminder' => 'boolean',
        'accounts_latest_action_date' => 'date',
        'accounts_records_received' => 'date',
    ];

    /**
     * Get the self assessment that owns these accounts and returns details.
     */
    public function selfAssessment(): BelongsTo
    {
        return $this->belongsTo(SelfAssessment::class);
    }
}