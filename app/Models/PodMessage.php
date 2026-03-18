<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PodMessage extends Model
{
    protected $fillable = ['pod_id', 'user_id', 'message', 'type'];

    public function pod(): BelongsTo
    {
        return $this->belongsTo(Pod::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isCelebration(): bool
    {
        return $this->type === 'celebration';
    }

    public function isSystem(): bool
    {
        return $this->type === 'system';
    }
}
