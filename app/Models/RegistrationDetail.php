<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'terms_signed_registration_fee_paid', 'fee',
        'letter_of_engagement_signed', 'money_laundering_complete', 'registration_64_8'
    ];

    protected $casts = [
        'terms_signed_registration_fee_paid' => 'boolean',
        'fee' => 'decimal:2',
        'letter_of_engagement_signed' => 'date',
        'money_laundering_complete' => 'boolean',
        'registration_64_8' => 'date'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
