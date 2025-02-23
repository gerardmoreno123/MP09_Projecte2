<?php

namespace App\Helpers;

use App\Models\Video;
use Illuminate\Support\Collection;

class DefaultVideosHelper
{
    /**
     * Crea videos por defecto.
     *
     * @return Collection<int, Video>
     */
    public static function create_default_videos(): Collection
    {
        $existingVideos = Video::all();
        if ($existingVideos->isNotEmpty()) {
            return $existingVideos;
        }

        // Crear los tres videos
        $video1 = Video::create([
            'title' => 'Video 1',
            'description' => 'Descripció del video 1',
            'url' => 'https://www.youtube.com/embed/VRvmn2WA0Q8?si=ih-tb-l0SKeoFl1p',
            'published_at' => '2025-01-13 12:00:00',
            'previous_id' => null,
            'next_id' => null,
            'series_id' => null,
        ]);

        $video2 = Video::create([
            'title' => 'Video 2',
            'description' => 'Descripció del video 2',
            'url' => 'https://www.youtube.com/embed/CYXJYQZ3FX0?si=i8LCxbQFYv-NG6jh',
            'published_at' => '2025-01-10 12:00:00',
            'previous_id' => null,
            'next_id' => null,
            'series_id' => null,
        ]);

        $video3 = Video::create([
            'title' => 'Video 3',
            'description' => 'Descripció del video 3',
            'url' => 'https://www.youtube.com/embed/VjlqaPc98hg?si=kgjsVBjIut-4H5T- ',
            'published_at' => '2025-01-07 12:00:00',
            'previous_id' => null,
            'next_id' => null,
            'series_id' => null,
        ]);

        $video1->update(['next_id' => $video2->id]);
        $video2->update(['previous_id' => $video1->id, 'next_id' => $video3->id]);
        $video3->update(['previous_id' => $video2->id]);

        // Retorna un array con los tres videos
        return collect([$video1, $video2, $video3]);
    }
}
