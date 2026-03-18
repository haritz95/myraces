<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pod extends Model
{
    protected $fillable = [
        'created_by', 'name', 'description', 'goal',
        'target_distance', 'target_unit', 'max_members',
        'status', 'starts_at', 'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'target_distance' => 'decimal:3',
            'starts_at' => 'date',
            'ends_at' => 'date',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'pod_user')
            ->withPivot(['role', 'points', 'joined_at'])
            ->withTimestamps()
            ->orderByPivot('points', 'desc');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(PodMessage::class)->with('user')->latest();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function isFull(): bool
    {
        return $this->members()->count() >= $this->max_members;
    }

    public function hasMember(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    public function totalPoints(): int
    {
        return (int) $this->members()->sum('pod_user.points');
    }

    public function progressPercent(): float
    {
        if (! $this->target_distance || (float) $this->target_distance === 0.0) {
            return 0.0;
        }

        $totalKm = $this->members()
            ->join('races', function ($join) {
                $join->on('races.user_id', '=', 'users.id')
                    ->where('races.status', 'completed');
            })
            ->where('races.created_at', '>=', $this->created_at)
            ->sum('races.distance');

        return min(100, round($totalKm / (float) $this->target_distance * 100, 1));
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'active' => 'Activo',
            'completed' => 'Completado',
            'archived' => 'Archivado',
            default => $this->status,
        };
    }
}
