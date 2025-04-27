<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SelfBusinessDetails extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'self_assessment_id',
        'trading_as',
        'trading_address',
        'commenced_trading',
        'cassed_trading',
        'registerd_for_sa',
        'turnover',
        'nature_of_business',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'commenced_trading' => 'date',
        'cassed_trading' => 'date',
        'registerd_for_sa' => 'date',
        'turnover' => 'decimal:2',
    ];

    /**
     * Get the self assessment that owns these business details.
     */
    public function selfAssessment(): BelongsTo
    {
        return $this->belongsTo(SelfAssessment::class);
    }
}