<?php

use App\Models\Video;
use Carbon\Carbon;

class VideoHelper
{
    /**
     * Torna la data de publicació en format "13 de gener de 2025"
     *
     * @param string $date El video del que volem obtenir la data de publicació
     * @return string
     */
    public static function getFormattedPublishedAtAttribute($date)
    {
        return Carbon::parse($date)->isoFormat('D [de] MMMM [de] YYYY');
    }


    /**
     * Torna la data de publicació en format "fa 3 dies"
     *
     * @param string $date El video del que volem obtenir la data de publicació
     * @return string
     */
    public static function getFormattedForHumansPublishedAtAttribute($date)
    {
        return Carbon::parse($date)->diffForHumans();
    }

    /**
     * Torna la data de publicació en format unix timestamp
     *
     * @param string $date El video del que volem obtenir la data de publicació
     * @return int
     */
    public static function getPublishedAtTimestampAttribute($date)
    {
        $timestamp = Carbon::parse($date)->timestamp;

        return (int) $timestamp;
    }
}
