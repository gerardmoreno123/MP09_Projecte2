<?php

namespace Tests\Unit;

use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VideosTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_formatted_published_at_date()
    {
        $video = Video::create([
            'title' => 'Video de prueba',
            'description' => 'Descripción de prueba',
            'url' => 'http://test.com',
            'published_at' => Carbon::now()->toDateString(),
//            'series_id' => null,
        ]);

        $formattedDate = $video->getFormattedPublishedAtAttribute();

        $expectedDate = Carbon::now()->locale('ca')->isoFormat('D [de] MMMM [de] YYYY');

        $this->assertEquals($expectedDate, $formattedDate);
    }

    public function test_can_get_formatted_published_at_date_when_not_published()
    {
        $video = Video::create([
            'title' => 'Video sin fecha',
            'description' => 'Este video no ha sido publicado aún',
            'url' => 'http://test.com',
            'published_at' => null,
//            'series_id' => null,
        ]);

        $formattedDate = $video->getFormattedPublishedAtAttribute();

        $this->assertEquals(null, $formattedDate);
    }
}
