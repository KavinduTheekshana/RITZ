<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyInternalDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'internal_reference', 'allocated_office',
        'client_grade', 'client_risk_level', 'notes', 'urgent'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
