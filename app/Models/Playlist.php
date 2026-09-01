<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Playlist extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'thumbnail',
    ];

    /**
     * User yang memiliki playlist ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Lagu-lagu yang terdapat dalam playlist.
     */
    public function songs(): BelongsToMany
    {
        return $this->belongsToMany(
            Song::class,
            'playlist_songs'
        )
            ->withPivot('position')
            ->orderBy('position')
            ->withTimestamps();
    }
}
