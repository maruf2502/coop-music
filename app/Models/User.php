<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'google_id',
        'avatar',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Playlist milik user.
     */
    public function playlists(): HasMany
    {
        return $this->hasMany(Playlist::class);
    }

    /**
     * Lagu favorit user.
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Riwayat lagu user.
     */
    public function histories(): HasMany
    {
        return $this->hasMany(History::class);
    }

    /**
     * Room yang dibuat user sebagai host.
     */
    public function hostedRooms(): HasMany
    {
        return $this->hasMany(Room::class, 'host_id');
    }

    /**
     * Data keanggotaan user di room.
     */
    public function roomUsers(): HasMany
    {
        return $this->hasMany(RoomUser::class);
    }

    /**
     * Semua room yang diikuti user.
     */
    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(
            Room::class,
            'room_users',
            'user_id',
            'room_id'
        )
            ->withPivot(
                'role',
                'is_online',
                'joined_at',
                'left_at'
            )
            ->withTimestamps();
    }
}
