<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Album extends Model
{
    protected $fillable = [
        'artist_id',
        'youtube_id',
        'title',
        'thumbnail',
        'release_date',
    ];

    protected function casts(): array
    {
        return [
            'release_date' => 'date',
        ];
    }

    /**
     * Artist yang memiliki album ini.
     */
    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }

    /**
     * Lagu-lagu yang terdapat dalam album.
     */
    public function songs(): HasMany
    {
        return $this->hasMany(Song::class);
    }
}
