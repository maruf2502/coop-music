<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomQueue extends Model
{
    protected $table = 'room_queue';

    protected $fillable = [
        'room_id',
        'song_id',
        'added_by',
        'position',
        'is_played',
    ];

    protected function casts(): array
    {
        return [
            'position' => 'integer',
            'is_played' => 'boolean',
        ];
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function song(): BelongsTo
    {
        return $this->belongsTo(Song::class);
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
