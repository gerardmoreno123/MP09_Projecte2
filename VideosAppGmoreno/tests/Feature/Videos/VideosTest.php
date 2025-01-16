<?php

namespace Tests\Feature\Videos;

use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VideosTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_view_videos()
    {
        $user = User::factory()->create();
        $video = Video::factory()->create();

        $response = $this->actingAs($user)->get("/videos/{$video->id}");

        $response->assertStatus(200);
        $response->assertSee($video->title);
    }

    public function test_users_cannot_view_not_existing_videos()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/videos/9999');

        $response->assertStatus(404);
    }
}
