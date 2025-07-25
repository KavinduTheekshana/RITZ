<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Client extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'title',
        'first_name',
        'middle_name',
        'last_name',
        'preferred_name',
        'date_of_birth',
        'deceased',
        'email',
        'password',
        'email_verified_at',
        'postal_address',
        'previous_address',
        'telephone_number',
        'mobile_number',
        'ni_number',
        'personal_utr_number',
        'terms_signed',
        'photo_id_verified',
        'address_verified',
        'marital_status',
        'nationality',
        'profile_photo_path',
        'preferred_language',
        'create_self_assessment_client',
        'client_does_their_own_sa',
        'password_mail'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'deceased' => 'date',
        'email_verified_at' => 'datetime',
        'terms_signed' => 'date',
        'photo_id_verified' => 'boolean',
        'address_verified' => 'boolean',
        'create_self_assessment_client' => 'boolean',
        'client_does_their_own_sa' => 'boolean',
        'password' => 'hashed', // Add this for automatic password hashing
    ];

    public function companies()
    {
        return $this->belongsToMany(Company::class);
    }

    /**
     * Get the client's full name.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        $name = collect([
            $this->title,
            $this->first_name,
            $this->middle_name,
            $this->last_name
        ])->filter()->join(' ');

        return $name ?: 'Unnamed Client';
    }

    /**
     * Get the client's formal name (Title LastName).
     *
     * @return string
     */
    public function getFormalNameAttribute(): string
    {
        $name = collect([
            $this->title,
            $this->last_name
        ])->filter()->join(' ');

        return $name ?: 'Unnamed Client';
    }

    public function selfAssessment(): HasOne
    {
        return $this->hasOne(SelfAssessment::class);
    }

    /**
     * Check if client has a self assessment
     */
    public function hasSelfAssessment(): bool
    {
        return $this->selfAssessment()->exists();
    }


public function chatMessages()
{
    return $this->hasMany(CompanyChatList::class);
}
}
