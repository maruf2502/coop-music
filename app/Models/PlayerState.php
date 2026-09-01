<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerState extends Model
{
    protected $fillable = [
        'room_id',
        'song_id',
        'status',
        'position',
        'updated_at_server',
    ];

    protected $casts = [
        'position' => 'float',
        'updated_at_server' => 'datetime',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function song(): BelongsTo
    {
        return $this->belongsTo(Song::class);
    }
}
