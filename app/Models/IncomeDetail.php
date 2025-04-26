<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'previous', 'current', 'ir_35_notes'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
