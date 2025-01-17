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
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

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
