<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Room extends Model
{
    protected $fillable = [
        'host_id',
        'code',
        'name',
        'control_mode',
        'allow_add_song',
        'allow_chat',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'allow_add_song' => 'boolean',
            'allow_chat' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * User yang menjadi host room.
     */
    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    /**
     * Data keanggotaan room.
     *
     * Menggunakan tabel room_users.
     */
    public function roomUsers(): HasMany
    {
        return $this->hasMany(RoomUser::class, 'room_id');
    }

    /**
     * Semua user yang berada di dalam room.
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'room_users',
            'room_id',
            'user_id'
        )
            ->withPivot(
                'role',
                'is_online',
                'joined_at',
                'left_at'
            )
            ->withTimestamps();
    }

    /**
     * Semua lagu yang ada di queue room.
     */
    public function queue(): HasMany
    {
        return $this->hasMany(RoomQueue::class, 'room_id')
            ->orderBy('position');
    }

    public function playerState(): HasOne
    {
        return $this->hasOne(PlayerState::class);
    }
}
