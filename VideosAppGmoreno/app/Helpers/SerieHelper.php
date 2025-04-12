<?php

namespace App\Helpers;

use App\Models\Serie;
use App\Models\Video;
use Illuminate\Support\Collection;

class SerieHelper
{
    public static function create_series(): Collection
    {
        $existingSeries = Serie::all();
        if ($existingSeries->isNotEmpty()) {
            return $existingSeries;
        }

        $serie1 = Serie::create([
            'title' => 'Serie 1',
            'description' => 'Descripció de la sèrie 1',
            'user_name' => (new UserHelpers())->create_serie_manager_user()->name,
        ]);

        $serie2 = Serie::create([
            'title' => 'Serie 2',
            'description' => 'Descripció de la sèrie 2',
            'user_name' => (new UserHelpers())->create_serie_manager_user()->name,
        ]);

        $serie3 = Serie::create([
            'title' => 'Serie 3',
            'description' => 'Descripció de la sèrie 3',
            'user_name' => (new UserHelpers())->create_serie_manager_user()->name,
        ]);

        return collect([$serie1, $serie2, $serie3]);
    }
}
