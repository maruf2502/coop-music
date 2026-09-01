<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class History extends Model
{
    /**
     * User yang memutar lagu.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Lagu yang diputar.
     */
    public function song(): BelongsTo
    {
        return $this->belongsTo(Song::class);
    }
}
