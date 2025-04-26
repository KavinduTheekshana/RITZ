<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountsAndReturnsDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
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
        'accounts_progress_note'
    ];

    protected $casts = [
        'accounts_period_end' => 'date',
        'ch_year_end' => 'date',
        'hmrc_year_end' => 'date',
        'ch_accounts_next_due' => 'date',
        'ct600_due' => 'date',
        'tax_due_hmrc_year_end' => 'date',
        'accounts_latest_action_date' => 'date',
        'accounts_records_received' => 'date',
        'corporation_tax_amount_due' => 'decimal:2',
        'companies_house_email_reminder' => 'boolean'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
