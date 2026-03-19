<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RaceEventModality extends Model
{
    protected $fillable = [
        'race_event_id', 'name', 'distance_km', 'category',
        'price', 'registration_url', 'max_participants', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'distance_km' => 'decimal:3',
            'price' => 'decimal:2',
        ];
    }

    public function raceEvent(): BelongsTo
    {
        return $this->belongsTo(RaceEvent::class);
    }
}
