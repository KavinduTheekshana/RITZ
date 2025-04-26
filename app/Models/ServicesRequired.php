<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicesRequired extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'accounts', 'bookkeeping', 'ct600_return', 'payroll',
        'auto_enrolment', 'vat_returns', 'management_accounts',
        'confirmation_statement', 'cis', 'p11d', 'fee_protection_service',
        'registered_address', 'bill_payment', 'consultation_advice',
        'software', 'annual_charge', 'monthly_charge'
    ];

    protected $casts = [
        'accounts' => 'decimal:2',
        'bookkeeping' => 'decimal:2',
        'ct600_return' => 'decimal:2',
        'payroll' => 'decimal:2',
        'auto_enrolment' => 'decimal:2',
        'vat_returns' => 'decimal:2',
        'management_accounts' => 'decimal:2',
        'confirmation_statement' => 'decimal:2',
        'cis' => 'decimal:2',
        'p11d' => 'decimal:2',
        'fee_protection_service' => 'decimal:2',
        'registered_address' => 'decimal:2',
        'bill_payment' => 'decimal:2',
        'consultation_advice' => 'decimal:2',
        'software' => 'decimal:2',
        'annual_charge' => 'decimal:2',
        'monthly_charge' => 'decimal:2'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
