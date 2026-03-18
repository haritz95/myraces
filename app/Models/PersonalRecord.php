<?php

namespace App\Models;

use Database\Factories\PersonalRecordFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'race_id', 'distance_label', 'distance_km', 'time_seconds', 'date', 'location'])]
class PersonalRecord extends Model
{
    /** @use HasFactory<PersonalRecordFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'distance_km' => 'decimal:3',
            'time_seconds' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function race(): BelongsTo
    {
        return $this->belongsTo(Race::class);
    }

    public function getFormattedTimeAttribute(): string
    {
        $hours = intdiv($this->time_seconds, 3600);
        $minutes = intdiv($this->time_seconds % 3600, 60);
        $seconds = $this->time_seconds % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    public function getPaceAttribute(): ?string
    {
        if (! $this->distance_km || (float) $this->distance_km === 0.0) {
            return null;
        }

        $paceSeconds = $this->time_seconds / (float) $this->distance_km;
        $minutes = intdiv((int) $paceSeconds, 60);
        $seconds = (int) $paceSeconds % 60;

        return sprintf('%d:%02d /km', $minutes, $seconds);
    }
}
