<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SelfInternalDetails extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'self_assessment_id',
        'internal_reference',
        'allocated_office',
        'client_grade',
        'client_risk_level',
        'notes',
        'urgent',
    ];

    /**
     * Get the self assessment that owns these internal details.
     */
    public function selfAssessment(): BelongsTo
    {
        return $this->belongsTo(SelfAssessment::class);
    }
}