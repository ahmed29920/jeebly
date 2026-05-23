<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'condition',
        'discount_type',
        'discount_value',
        'is_active',
        'start_date',
        'end_date',
        'image',
    ];

    protected $casts = [
        'condition' => 'array',
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'image' => 'string',
    ];

    /**
     * Check if offer is currently active (date + status)
     */
    public function getIsCurrentlyActiveAttribute(): bool
    {
        $now = Carbon::now();
        return $this->is_active &&
            (!$this->start_date || $this->start_date <= $now) &&
            (!$this->end_date || $this->end_date >= $now);
    }

    /**
     * Helper to extract specific condition fields safely
     */
    public function getConditionValue(string $key, $default = null)
    {
        return $this->condition[$key] ?? $default;
    }
}
