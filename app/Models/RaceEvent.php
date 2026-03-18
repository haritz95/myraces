<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class RaceEvent extends Model
{
    protected $fillable = [
        'created_by', 'name', 'slug', 'description', 'image',
        'event_date', 'registration_deadline', 'location', 'province', 'country',
        'distance_km', 'category', 'race_type', 'price', 'max_participants',
        'website_url', 'registration_url', 'organizer',
        'status', 'source', 'external_id', 'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'datetime',
            'registration_deadline' => 'date',
            'distance_km' => 'decimal:3',
            'price' => 'decimal:2',
            'is_featured' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $event): void {
            if (empty($event->slug)) {
                $event->slug = self::uniqueSlug($event->name, $event->event_date?->format('Y'));
            }
        });
    }

    private static function uniqueSlug(string $name, ?string $year): string
    {
        $base = Str::slug($name.($year ? '-'.$year : ''));
        $slug = $base;
        $i = 2;
        while (self::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'race_event_user')->withTimestamps();
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('event_date', '>=', now()->startOfDay())
            ->where('status', '!=', 'cancelled')
            ->orderBy('event_date');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function isAttending(User $user): bool
    {
        return $this->attendees()->where('user_id', $user->id)->exists();
    }

    public function isPast(): bool
    {
        return $this->event_date->isPast();
    }

    public function registrationOpen(): bool
    {
        if ($this->status !== 'open') {
            return false;
        }

        return ! $this->registration_deadline || $this->registration_deadline->isFuture();
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'upcoming' => 'Próxima',
            'open' => 'Inscripción abierta',
            'cancelled' => 'Cancelada',
            'past' => 'Finalizada',
            default => $this->status,
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'open' => '#C8FA5F',
            'upcoming' => '#60a5fa',
            'cancelled' => '#f87171',
            default => 'rgba(255,255,255,0.35)',
        };
    }

    public function raceTypeLabel(): string
    {
        return match ($this->race_type) {
            'road' => 'Asfalto',
            'trail' => 'Trail',
            'mountain' => 'Montaña',
            'ultra' => 'Ultra',
            'obstacle' => 'Obstáculos',
            'triathlon' => 'Triatlón',
            default => 'Otro',
        };
    }

    /** @return array<string,string> */
    public static function raceTypes(): array
    {
        return [
            'road' => 'Asfalto',
            'trail' => 'Trail',
            'mountain' => 'Montaña',
            'ultra' => 'Ultra',
            'obstacle' => 'Obstáculos',
            'triathlon' => 'Triatlón',
            'other' => 'Otro',
        ];
    }

    /** @return array<string> */
    public static function categories(): array
    {
        return ['5K', '10K', '15K', 'Media maratón', 'Maratón', 'Trail corto', 'Trail largo', 'Ultra', 'Otro'];
    }
}
