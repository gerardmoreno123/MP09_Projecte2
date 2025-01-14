<?php

// tests/Unit/VideosTest.php

namespace Tests\Unit;

use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VideosTest extends TestCase
{
    use RefreshDatabase;

    public function can_get_formatted_published_at_date()
    {
        $video = Video::create([
            'title' => 'Video de prueba',
            'description' => 'Descripción de prueba',
            'url' => 'http://test.com',
            'published_at' => Carbon::now()->toDateString(),
            'series_id' => 1,
        ]);

        $formattedDate = $video->getFormattedPublishedAtAttribute();

        $this->assertEquals('13 de gener de 2025', $formattedDate);
    }

    public function can_get_formatted_published_at_date_when_not_published()
    {
        $video = Video::create([
            'title' => 'Video sin fecha',
            'description' => 'Este video no ha sido publicado aún',
            'url' => 'http://test.com',
            'published_at' => null,
            'series_id' => 1,
        ]);

        $formattedDate = $video->getFormattedPublishedAtAttribute();

        $this->assertNull($formattedDate);
    }
}