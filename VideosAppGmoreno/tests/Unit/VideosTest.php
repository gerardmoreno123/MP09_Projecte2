<?php

namespace Tests\Unit;

use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VideosTest extends TestCase
{
    use RefreshDatabase;

    public const TESTED_BY = self::class;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setLocale('ca');
    }

    public function test_can_get_formatted_published_at_date()
    {
        $video = Video::create([
            'title' => 'Video de prova',
            'description' => 'Descripció de prova',
            'url' => 'http://test.com',
            'published_at' => Carbon::now()->toDateString(),
        ]);

        $formattedDate = $video->getFormattedPublishedAtAttribute();

        $expectedDate = Carbon::now()->locale('ca')->isoFormat('D [de] MMMM [de] YYYY');

        $this->assertEquals($expectedDate, $formattedDate);
    }

    public function test_can_get_formatted_for_humans_published_at_date()
    {
        $video = Video::create([
            'title' => 'Video de prova',
            'description' => 'Descripció de prova',
            'url' => 'http://test.com',
            'published_at' => Carbon::now()->subDays(3)->toDateString(),
        ]);

        $formattedDate = $video->getFormattedForHumansPublishedAtAttribute();

        $this->assertEquals('fa 3 dies', $formattedDate);
    }

    public function test_can_get_published_at_timestamp()
    {
        $video = Video::create([
            'title' => 'Video de prova',
            'description' => 'Descripció de prova',
            'url' => 'http://test.com',
            'published_at' => Carbon::now()->toDateString(),
        ]);

        $timestamp = $video->getPublishedAtTimestampAttribute();

        $this->assertEquals($video->published_at->timestamp, $timestamp);
    }

    public function test_can_get_formatted_published_at_date_when_not_published()
    {
        $video = Video::create([
            'title' => 'Video sense data',
            'description' => 'Aquest video no ha estat publicat encara',
            'url' => 'http://test.com',
            'published_at' => null,
        ]);

        $formattedDate = $video->getFormattedPublishedAtAttribute();

        $this->assertEquals(null, $formattedDate);
    }
}
