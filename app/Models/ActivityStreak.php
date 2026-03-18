<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityStreak extends Model
{
    protected $fillable = [
        'user_id', 'current_streak', 'longest_streak',
        'last_activity_date', 'week_start_date', 'rest_days_used_this_week',
    ];

    protected function casts(): array
    {
        return [
            'last_activity_date' => 'date',
            'week_start_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Points multiplier: 1 + streak/10
     * A streak of 10 = 2× multiplier, 20 = 3×, etc.
     */
    public function multiplier(): float
    {
        return 1 + ($this->current_streak / 10);
    }

    /**
     * Calculate points earned for a given distance (km).
     * Points = distance × (1 + streak/10)
     */
    public function pointsFor(float $distanceKm): int
    {
        return (int) round($distanceKm * $this->multiplier());
    }
}
