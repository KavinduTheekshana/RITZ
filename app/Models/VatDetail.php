<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VatDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'vat_frequency', 'vat_period_end', 'next_return_due',
        'vat_bill_amount', 'vat_bill_due', 'latest_action', 'latest_action_date',
        'records_received', 'progress_note', 'vat_member_state', 'vat_number',
        'vat_address', 'date_of_registration', 'effective_date', 'estimated_turnover',
        'applied_for_mtd', 'mtd_ready', 'transfer_of_going_concern',
        'involved_in_other_businesses', 'direct_debit', 'standard_scheme',
        'cash_accounting_scheme', 'retail_scheme', 'margin_scheme', 'flat_rate',
        'flat_rate_category', 'month_of_last_quarter_submitted',
        'box_5_of_last_quarter_submitted', 'general_notes'
    ];

    protected $casts = [
        'vat_period_end' => 'date',
        'next_return_due' => 'date',
        'vat_bill_due' => 'date',
        'latest_action_date' => 'date',
        'records_received' => 'date',
        'date_of_registration' => 'date',
        'effective_date' => 'date',
        'applied_for_mtd' => 'date',
        'vat_bill_amount' => 'decimal:2',
        'estimated_turnover' => 'decimal:2',
        'mtd_ready' => 'boolean',
        'transfer_of_going_concern' => 'boolean',
        'involved_in_other_businesses' => 'boolean',
        'direct_debit' => 'boolean',
        'standard_scheme' => 'boolean',
        'cash_accounting_scheme' => 'boolean',
        'retail_scheme' => 'boolean',
        'margin_scheme' => 'boolean',
        'flat_rate' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
