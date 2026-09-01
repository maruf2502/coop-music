<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomMessage extends Model
{
    /**
     * Room tempat pesan dikirim.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * User yang mengirim pesan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
