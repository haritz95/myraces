<?php

namespace App\Models;

use Database\Factories\NavItemFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavItem extends Model
{
    /** @use HasFactory<NavItemFactory> */
    use HasFactory;

    protected $fillable = [
        'key', 'label', 'route_name', 'icon_path',
        'match_pattern', 'location', 'sort_order',
        'is_enabled', 'is_premium',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'is_premium' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('location')->orderBy('sort_order');
    }

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    public function isActive(): bool
    {
        return collect(explode('|', $this->match_pattern))
            ->contains(fn ($pattern) => request()->routeIs($pattern));
    }

    /**
     * @return Collection<int, self>
     */
    public static function forMobile(): Collection
    {
        return static::ordered()->get();
    }
}
