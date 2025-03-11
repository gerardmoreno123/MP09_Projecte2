<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\VideoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Series; #No existeix encara
use App\Helpers\VideoHelper;

class Video extends Model
{
    /** @use HasFactory<VideoFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'url',
        'published_at',
        'previous_id',
        'next_id',
        'series_id',
        'user_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con el video anterior
    public function previous()
    {
        return $this->belongsTo(Video::class, 'previous_id');
    }

    // Relación con el video siguiente
    public function next()
    {
        return $this->belongsTo(Video::class, 'next_id');
    }

    // Cuando eliminas un video, actualizas las relaciones de los videos anteriores y siguientes
    protected static function booted()
    {
        static::deleting(function ($video) {
            // Desvincula las relaciones si es necesario
            if ($video->previous) {
                $video->previous->update(['next_id' => null]);
            }

            if ($video->next) {
                $video->next->update(['previous_id' => null]);
            }
        });
    }

    /**
     * Format de la data de publicació
     *
     * @return string|null
     */
    public function getFormattedPublishedAtAttribute(): string|null
    {
        return VideoHelper::getFormattedPublishedAtAttribute($this->published_at);
    }

    /**
     * Data de publicació en format humà
     *
     * @return string|null
     */
    public function getFormattedForHumansPublishedAtAttribute(): string|null
    {
        return VideoHelper::getFormattedForHumansPublishedAtAttribute($this->published_at);
    }

    /**
     * Data de publicació en format UNIX timestamp
     *
     * @return int
     *
     */
    public function getPublishedAtTimestampAttribute(): int
    {
        return VideoHelper::getPublishedAtTimestampAttribute($this->published_at);
    }


}
