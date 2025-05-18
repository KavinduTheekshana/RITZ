<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_number',
        'company_name',
        'company_status',
        'company_type',
        'incorporation_date',
        'company_trading_as',
        'registered_address',
        'company_postal_address',
        'invoice_address_type',
        'company_email',
        'company_email_domain',
        'company_telephone',
        'turnover',
        'date_of_trading',
        'sic_code',
        'nature_of_business',
        'corporation_tax_office',
        'company_utr',
        'companies_house_authentication_code',
        'engagement'
    ];

    protected $casts = [
        'incorporation_date' => 'date',
        'date_of_trading' => 'date',
        'turnover' => 'decimal:2',
    ];

    public function clients()
    {
        return $this->belongsToMany(Client::class);
    }

    public function internalDetails()
    {
        return $this->hasOne(CompanyInternalDetail::class);
    }

    public function incomeDetails()
    {
        return $this->hasOne(IncomeDetail::class);
    }

    public function previousAccountantDetails()
    {
        return $this->hasOne(PreviousAccountantDetail::class);
    }

    public function servicesRequired()
    {
        return $this->hasOne(ServicesRequired::class);
    }

    public function accountsAndReturnsDetails()
    {
        return $this->hasOne(AccountsAndReturnsDetail::class);
    }

    public function confirmationStatementDetails()
    {
        return $this->hasOne(ConfirmationStatementDetail::class);
    }

    public function vatDetails()
    {
        return $this->hasOne(VatDetail::class);
    }

    public function payeDetails()
    {
        return $this->hasOne(PayeDetail::class);
    }

    public function autoEnrolmentDetails()
    {
        return $this->hasOne(AutoEnrolmentDetail::class);
    }

    public function p11dDetails()
    {
        return $this->hasOne(P11dDetail::class);
    }

    public function registrationDetails()
    {
        return $this->hasOne(RegistrationDetail::class);
    }

    public function otherDetails()
    {
        return $this->hasOne(OtherDetail::class);
    }
    public function engagementLetters()
    {
        return $this->hasMany(EngagementLetterCompany::class);
    }
}
