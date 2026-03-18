<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Ad extends Model
{
    protected $fillable = [
        'user_id', 'title', 'subtitle', 'image_path', 'cta_label',
        'target_url', 'type', 'location', 'status', 'rejection_reason',
        'starts_at', 'ends_at', 'max_impressions',
        'impressions_count', 'clicks_count',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'max_impressions' => 'integer',
            'impressions_count' => 'integer',
            'clicks_count' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function clicks(): HasMany
    {
        return $this->hasMany(AdClick::class);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopeForLocation(Builder $query, string $location): Builder
    {
        $now = now();

        return $query->approved()
            ->where('location', $location)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now))
            ->where(fn ($q) => $q->where('max_impressions', 0)->orWhereColumn('impressions_count', '<', 'max_impressions'));
    }

    /**
     * Pick one random active ad for the given location without recording the impression.
     * Use this in controllers — the impression is recorded when the ad is actually rendered.
     */
    public static function pick(string $location): ?self
    {
        return static::forLocation($location)->inRandomOrder()->first();
    }

    /**
     * Pick and immediately record the impression.
     *
     * @deprecated Use pick() + let the view record via /ad/{ad}/impression instead.
     */
    public static function serve(string $location): ?self
    {
        $ad = static::pick($location);

        if ($ad) {
            static::where('id', $ad->id)->increment('impressions_count');
        }

        return $ad;
    }

    public function recordImpression(): void
    {
        static::where('id', $this->id)->increment('impressions_count');
    }

    public function imageUrl(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        return Storage::url($this->image_path);
    }

    public function ctr(): float
    {
        if ($this->impressions_count === 0) {
            return 0.0;
        }

        return round($this->clicks_count / $this->impressions_count * 100, 2);
    }

    public function isActive(): bool
    {
        if ($this->status !== 'approved') {
            return false;
        }

        $now = now();

        if ($this->starts_at && $this->starts_at->gt($now)) {
            return false;
        }

        if ($this->ends_at && $this->ends_at->lt($now)) {
            return false;
        }

        if ($this->max_impressions > 0 && $this->impressions_count >= $this->max_impressions) {
            return false;
        }

        return true;
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'race' => 'Carrera',
            'product' => 'Producto',
            'service' => 'Servicio',
            'event' => 'Evento',
            default => $this->type,
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'Pendiente',
            'approved' => 'Activo',
            'paused' => 'Pausado',
            'rejected' => 'Rechazado',
            default => $this->status,
        };
    }
}
