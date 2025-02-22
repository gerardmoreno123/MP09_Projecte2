<?php

namespace Tests\Feature;

use App\Helpers\DefaultVideosHelper;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // Run the database migrations
        $this->artisan('migrate');

        // Create default videos using the helper
        DefaultVideosHelper::create_default_videos();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewHas('videos', Video::all());
    }
}
