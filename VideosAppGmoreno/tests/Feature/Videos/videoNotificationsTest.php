<?php

namespace Tests\Feature\Videos;

use App\Events\VideoCreated;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class videoNotificationsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Executem el seeder per configurar rols i permisos
        $this->artisan('db:seed', ['--class' => 'DatabaseSeeder']);
    }

    /**
     * Test that the VideoCreated event is dispatched when a video is created.
     */
    public function test_video_created_event_is_dispatched(): void
    {
        Event::fake();

        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $user->assignRole('viewer'); // Assignem el rol 'viewer' al usuari creat
        $this->actingAs($user);

        $videoData = [
            'title' => 'Test Video',
            'description' => 'Test description',
            'url' => 'https://example.com/test-video',
            'serie_id' => null,
            'user_id' => $user->id,
        ];

        $response = $this->post(route('videos.store'), $videoData);

        $response->assertStatus(302); // Redirect després de crear
        Event::assertDispatched(VideoCreated::class, function ($event) use ($videoData) {
            return $event->video->title === $videoData['title'] &&
                $event->video->user_id === $videoData['user_id'] &&
                $event->video->url === $videoData['url'];
        });
    }

    public function test_push_notification_is_sent_when_video_is_created(): void
    {
        Event::fake();

        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $user->assignRole('viewer');
        $this->actingAs($user);

        $videoData = [
            'title' => 'Test Video',
            'description' => 'Test description',
            'url' => 'https://example.com/test-video',
            'serie_id' => null,
            'user_id' => $user->id,
        ];

        $response = $this->post(route('videos.store'), $videoData);

        $response->assertStatus(302);
        Event::assertDispatched(VideoCreated::class, function ($event) use ($videoData) {
            return $event->broadcastOn()[0]->name === 'video-created-channel.' . $videoData['user_id'] &&
                $event->broadcastAs() === 'video.created' &&
                $event->broadcastWith()['video_title'] === $videoData['title'];
        });
    }
}
