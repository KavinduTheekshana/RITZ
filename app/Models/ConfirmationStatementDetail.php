<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfirmationStatementDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'confirmation_statement_date', 'confirmation_statement_due',
        'latest_action', 'latest_action_date', 'records_received',
        'progress_note', 'officers', 'share_capital', 'shareholders',
        'people_with_significant_control'
    ];

    protected $casts = [
        'confirmation_statement_date' => 'date',
        'confirmation_statement_due' => 'date',
        'latest_action_date' => 'date',
        'records_received' => 'date'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
