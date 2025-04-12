<?php

namespace App\Helpers;

use Carbon\Carbon;

class CarbonHelper
{
    /**
     * Torna la data de publicació en format "13 de gener de 2025"
     *
     * @param Carbon|string|null $date El video del que volem obtenir la data de publicació
     * @return string|null
     */
    public static function getFormattedPublishedAtAttribute(Carbon|string|null $date): string|null
    {
        if ($date === null) {
            return null;
        }

        $carbonDate = $date instanceof Carbon ? $date : Carbon::parse($date);

        // Retorna la fecha formateada
        return $carbonDate->isoFormat('D [de] MMMM [de] YYYY');
    }

    /**
     * Torna la data de publicació en format "fa 3 dies"
     *
     * @param Carbon|string|null $date El video del que volem obtenir la data de publicació
     * @return string|null
     */
    public static function getFormattedForHumansPublishedAtAttribute(Carbon|string|null $date): string|null
    {
        if ($date === null) {
            return null;
        }

        $carbonDate = $date instanceof Carbon ? $date : Carbon::parse($date);

        return $carbonDate->diffForHumans();
    }

    /**
     * Torna la data de publicació en format timestamp
     *
     * @param Carbon|string|null $date
     * @return int
     */
    public static function getPublishedAtTimestampAttribute(Carbon|string|null $date): int
    {
        if ($date === null) {
            return 0;
        }

        $carbonDate = $date instanceof Carbon ? $date : Carbon::parse($date);

        return (int) $carbonDate->timestamp;
    }

    /**
     * Torna la data de creació en format timestamp
     *
     * @param Carbon|string|null $date
     * @return int
     */
    public static function getCreatedAtTimestampAttribute(Carbon|string|null $date): int
    {
        if ($date === null) {
            return 0;
        }

        $carbonDate = $date instanceof Carbon ? $date : Carbon::parse($date);

        return (int) $carbonDate->timestamp;
    }
}
