<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdClick extends Model
{
    public $timestamps = false;

    protected $fillable = ['ad_id', 'user_id', 'ip_hash', 'user_agent', 'created_at'];

    public function ad(): BelongsTo
    {
        return $this->belongsTo(Ad::class);
    }
}
