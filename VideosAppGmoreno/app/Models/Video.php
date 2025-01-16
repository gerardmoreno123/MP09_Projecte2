<?php

namespace App\Models;

use Database\Factories\VideoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
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
     * Formato de la fecha de publicación
     *
     * @return string
     */
    public function getFormattedPublishedAtAttribute(): string
    {
        return VideoHelper::getFormattedPublishedAtAttribute($this->published_at);
    }

    /**
     * Fecha de publicación en formato legible para el humano
     *
     * @return string
     */
    public function getFormattedForHumansPublishedAtAttribute(): string
    {
        return VideoHelper::getFormattedForHumansPublishedAtAttribute($this->published_at);
    }

    /**
     * Fecha de publicación como un timestamp
     *
     * @return int
     */
    public function getPublishedAtTimestampAttribute(): int
    {
        return VideoHelper::getPublishedAtTimestampAttribute($this->published_at);
    }
}
