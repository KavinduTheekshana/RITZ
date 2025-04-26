<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'referred_by', 'initial_contact', 'proposal_email_sent',
        'welcome_email', 'accounting_system', 'profession', 'website',
        'twitter_handle', 'facebook_url', 'linkedin_url', 'instagram_handle'
    ];

    protected $casts = [
        'initial_contact' => 'date',
        'proposal_email_sent' => 'date',
        'welcome_email' => 'date'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
