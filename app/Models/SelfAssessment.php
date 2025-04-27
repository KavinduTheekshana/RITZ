<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SelfAssessment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'assessment_name',
        'self_assessment_telephone',
        'self_assessment_email',
    ];

    /**
     * Get the client that owns the self assessment.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }



    /**
     * Get the internal details for this self assessment.
     */
    public function internalDetails(): HasOne
    {
        return $this->hasOne(SelfInternalDetails::class);
    }

    /**
     * Get the business details for this self assessment.
     */
    public function businessDetails(): HasOne
    {
        return $this->hasOne(SelfBusinessDetails::class);
    }

    /**
     * Get the income details for this self assessment.
     */
    public function incomeDetails(): HasOne
    {
        return $this->hasOne(SelfIncomeDetails::class);
    }

    /**
     * Get the previous accountant details for this self assessment.
     */
    public function previousAccountantDetails(): HasOne
    {
        return $this->hasOne(SelfPreviousAccountantDetails::class);
    }

    /**
     * Get the services required for this self assessment.
     */
    public function servicesRequired(): HasOne
    {
        return $this->hasOne(SelfServicesRequired::class);
    }

    /**
     * Get the accounts and returns details for this self assessment.
     */
    public function accountsAndReturnsDetails(): HasOne
    {
        return $this->hasOne(SelfAccountsAndReturnsDetails::class);
    }

    /**
     * Get the VAT details for this self assessment.
     */
    public function vatDetails(): HasOne
    {
        return $this->hasOne(SelfVatDetails::class);
    }

    /**
     * Get the PAYE details for this self assessment.
     */
    public function payeDetails(): HasOne
    {
        return $this->hasOne(SelfPayeDetails::class);
    }

    /**
     * Get the auto-enrolment details for this self assessment.
     */
    public function autoEnrolmentDetails(): HasOne
    {
        return $this->hasOne(SelfAutoEnrolmentDetails::class);
    }

    /**
     * Get the P11D details for this self assessment.
     */
    public function p11dDetails(): HasOne
    {
        return $this->hasOne(SelfP11dDetails::class);
    }

    /**
     * Get the registration details for this self assessment.
     */
    public function registrationDetails(): HasOne
    {
        return $this->hasOne(SelfRegistrationDetails::class);
    }

    /**
     * Get the other details for this self assessment.
     */
    public function otherDetails(): HasOne
    {
        return $this->hasOne(SelfOtherDetails::class);
    }
}