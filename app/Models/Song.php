<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Song extends Model
{
    protected $fillable = [
        'artist_id',
        'album_id',
        'youtube_id',
        'title',
        'thumbnail',
        'duration',
    ];

    /**
     * Artist yang memiliki lagu ini.
     */
    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }

    /**
     * Album tempat lagu ini berada.
     */
    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }

    /**
     * Playlist yang berisi lagu ini.
     */
    public function playlists(): BelongsToMany
    {
        return $this->belongsToMany(
            Playlist::class,
            'playlist_songs'
        )
            ->withPivot('position')
            ->withTimestamps();
    }

    /**
     * User yang menyukai lagu ini.
     */
    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'favorites'
        )->withTimestamps();
    }

    /**
     * Riwayat pemutaran lagu.
     */
    public function histories(): HasMany
    {
        return $this->hasMany(History::class);
    }

    /**
     * Queue Room yang menggunakan lagu ini.
     */
    public function roomQueue(): HasMany
    {
        return $this->hasMany(RoomQueue::class);
    }

    public function playerStates(): HasMany
    {
        return $this->hasMany(PlayerState::class);
    }
}
