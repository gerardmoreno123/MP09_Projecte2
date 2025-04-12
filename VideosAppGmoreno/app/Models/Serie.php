<?php

namespace App\Models;

use App\Helpers\CarbonHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\Unit\SerieTest;

class Serie extends Model
{
    //hasfactory
    use \Illuminate\Database\Eloquent\Factories\HasFactory;


    protected $table = 'series';

    protected $fillable = [
        'title',
        'description',
        'image',
        'user_name',
        'user_photo_url',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    public function getFormattedCreatedAtAttribute(): string|null
    {
        return CarbonHelper::getFormattedPublishedAtAttribute($this->published_at);
    }

    public function getFormattedForHumansCreatedAtAttribute(): string|null
    {
        return CarbonHelper::getFormattedForHumansPublishedAtAttribute($this->published_at);
    }

    public function getCreatedAtTimestampAttribute(): Int|null
    {
        return CarbonHelper::getCreatedAtTimestampAttribute($this->published_at);
    }

    public function testedBy()
    {
        $tests = [];

        if (class_exists(SerieTest::class)) {
            $tests[] = SerieTest::class;
        }

        return !empty($tests) ? implode('<br>', $tests) : 'Desconegut';
    }

}
