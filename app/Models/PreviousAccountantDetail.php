<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreviousAccountantDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'clearance_required', 'accountant_email_address',
        'accountant_details', 'send_first_clearance_email',
        'automatically_request_every', 'last_requested', 'information_received'
    ];

    protected $casts = [
        'clearance_required' => 'boolean',
        'send_first_clearance_email' => 'date',
        'last_requested' => 'date',
        'information_received' => 'boolean'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
