<?php

namespace App\Models;

use Database\Factories\RaceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id', 'name', 'date', 'location', 'country',
    'distance', 'distance_unit', 'modality', 'status',
    'finish_time', 'position_overall', 'position_category',
    'category', 'bib_number', 'cost', 'website', 'notes', 'is_public',
])]
class Race extends Model
{
    /** @use HasFactory<RaceFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'distance' => 'decimal:3',
            'cost' => 'decimal:2',
            'finish_time' => 'integer',
            'position_overall' => 'integer',
            'position_category' => 'integer',
            'is_public' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Returns distance without trailing zeros: 5.000 → "5", 42.195 → "42.195"
     */
    public function getFormattedDistanceAttribute(): string
    {
        return rtrim(rtrim(number_format((float) $this->distance, 3, '.', ''), '0'), '.');
    }

    /**
     * Returns finish_time formatted as H:i:s or i:s
     */
    public function getFormattedTimeAttribute(): ?string
    {
        if (! $this->finish_time) {
            return null;
        }

        $hours = intdiv($this->finish_time, 3600);
        $minutes = intdiv($this->finish_time % 3600, 60);
        $seconds = $this->finish_time % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    /**
     * Returns pace per km as M:SS
     */
    public function getPaceAttribute(): ?string
    {
        if (! $this->finish_time || ! $this->distance || (float) $this->distance === 0.0) {
            return null;
        }

        $distanceKm = $this->distance_unit === 'mi'
            ? (float) $this->distance * 1.60934
            : (float) $this->distance;

        $paceSeconds = $this->finish_time / $distanceKm;
        $minutes = intdiv((int) $paceSeconds, 60);
        $seconds = (int) $paceSeconds % 60;

        return sprintf('%d:%02d /km', $minutes, $seconds);
    }
}
