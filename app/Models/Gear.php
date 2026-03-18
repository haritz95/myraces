<?php

namespace App\Models;

use Database\Factories\GearFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'brand', 'model', 'type', 'purchase_date', 'current_km', 'max_km', 'purchase_price', 'is_active', 'notes'])]
class Gear extends Model
{
    /** @use HasFactory<GearFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'current_km' => 'decimal:2',
            'max_km' => 'decimal:2',
            'purchase_price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getUsagePercentageAttribute(): ?float
    {
        if (! $this->max_km || (float) $this->max_km === 0.0) {
            return null;
        }

        return min(100, round((float) $this->current_km / (float) $this->max_km * 100, 1));
    }

    public function getRemainingKmAttribute(): ?float
    {
        if (! $this->max_km) {
            return null;
        }

        return max(0, (float) $this->max_km - (float) $this->current_km);
    }
}
