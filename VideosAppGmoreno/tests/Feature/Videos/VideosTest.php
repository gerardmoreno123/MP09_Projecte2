<?php

namespace Tests\Feature\Videos;

use App\Helpers\DefaultVideosHelper;
use App\Helpers\UserHelpers;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VideosTest extends TestCase
{
    use RefreshDatabase;

    public const TESTED_BY = self::class;

    public function test_users_can_view_videos()
    {
        $user = (new UserHelpers())->create_default_user();
        $videos = (new DefaultVideosHelper())->create_default_videos();

        $video = $videos[1];

        $response = $this->actingAs($user)->get("/video/{$video->id}");

        $response->assertStatus(200);
        $response->assertSee($video->title);
    }


    public function test_users_cannot_view_not_existing_videos()
    {
        $user = (new UserHelpers())->create_default_user();

        $response = $this->actingAs($user)->get('/videos/9999');

        $response->assertStatus(404);
    }
}
