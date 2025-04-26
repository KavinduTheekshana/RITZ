<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class P11dDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'next_p11d_return_due', 'latest_p11d_submitted',
        'latest_action', 'latest_action_date', 'records_received', 'progress_note'
    ];

    protected $casts = [
        'next_p11d_return_due' => 'date',
        'latest_p11d_submitted' => 'date',
        'latest_action_date' => 'date',
        'records_received' => 'date'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
