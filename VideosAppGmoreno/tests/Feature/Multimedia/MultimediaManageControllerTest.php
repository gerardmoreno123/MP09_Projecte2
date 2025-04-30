<?php

namespace Tests\Feature\Multimedia;

use App\Models\Multimedia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MultimediaManageControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['email_verified_at' => now()]);
    }

    #[Test]
    public function test_lists_all_multimedia_for_authenticated_user()
    {
        Multimedia::factory()->count(3)->create(['user_id' => $this->user->id]);
        Multimedia::factory()->create(['user_id' => User::factory()->create()->id]);

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/multimedia/manage');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure(['data' => [['id', 'title', 'description', 'file_path', 'file_type', 'user_id']]]);
    }

    #[Test]
    public function test_shows_a_multimedia_item()
    {
        $multimedia = Multimedia::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'sanctum')->getJson("/api/multimedia/manage/show/{$multimedia->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $multimedia->id,
                    'multimedia' => [
                        'title' => $multimedia->title,
                    ],
                    'user_name' => $this->user->name,
                ]
            ]);
    }

    #[Test]
    public function test_stores_a_new_multimedia_item()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->create('video.mp4', 1024, 'video/mp4');

        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/multimedia/manage/create', [
            'title' => 'Test Video',
            'description' => 'A test video',
            'file' => $file,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Arxiu multimèdia creat amb èxit.',
                'data' => [
                    'title' => 'Test Video',
                    'description' => 'A test video',
                    'file_type' => 'video',
                    'user_id' => $this->user->id,
                ]
            ]);

        $this->assertDatabaseHas('multimedia', [
            'title' => 'Test Video',
            'user_id' => $this->user->id,
        ]);
        Storage::disk('public')->assertExists('multimedia/' . $file->hashName());
    }

    #[Test]
    public function test_fails_to_store_with_invalid_file_type()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf');

        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/multimedia/manage/create', [
            'title' => 'Test Video',
            'file' => $file,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['file']);
    }

    #[Test]
    public function test_retrieves_multimedia_for_editing()
    {
        $multimedia = Multimedia::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'sanctum')->getJson("/api/multimedia/manage/edit/{$multimedia->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $multimedia->id,
                    'title' => $multimedia->title,
                    'description' => $multimedia->description,
                    'file_path' => $multimedia->file_path,
                ]
            ]);
    }

    #[Test]
    public function test_updates_multimedia_without_new_file()
    {
        $multimedia = Multimedia::factory()->create([
            'user_id' => $this->user->id,
            'file_path' => 'multimedia/oldfile.mp4',
            'file_type' => 'video',
        ]);

        $response = $this->actingAs($this->user, 'sanctum')->putJson("/api/multimedia/manage/update/{$multimedia->id}", [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Multimèdia actualitzat amb èxit',
                'data' => [
                    'title' => 'Updated Title',
                    'description' => 'Updated Description',
                    'file_path' => 'multimedia/oldfile.mp4',
                ]
            ]);

        $this->assertDatabaseHas('multimedia', [
            'id' => $multimedia->id,
            'title' => 'Updated Title',
        ]);
    }

    #[Test]
    public function test_updates_multimedia_with_new_file()
    {
        Storage::fake('public');
        $multimedia = Multimedia::factory()->create([
            'user_id' => $this->user->id,
            'file_path' => 'multimedia/oldfile.mp4',
            'file_type' => 'video',
        ]);
        $newFile = UploadedFile::fake()->create('newvideo.mp4', 1024, 'video/mp4');

        $response = $this->actingAs($this->user, 'sanctum')->putJson("/api/multimedia/manage/update/{$multimedia->id}", [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'file' => $newFile,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Multimèdia actualitzat amb èxit',
                'data' => [
                    'title' => 'Updated Title',
                    'file_type' => 'video',
                ]
            ]);

        $this->assertDatabaseHas('multimedia', [
            'id' => $multimedia->id,
            'title' => 'Updated Title',
        ]);
        Storage::disk('public')->assertMissing('multimedia/oldfile.mp4');
        Storage::disk('public')->assertExists('multimedia/' . $newFile->hashName());
    }

    #[Test]
    public function test_deletes_multimedia_permanently()
    {
        $multimedia = Multimedia::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'sanctum')->deleteJson("/api/multimedia/manage/destroy/{$multimedia->id}");

        $response->assertStatus(201)
            ->assertJson(['Video permanetly deleted.']);

        $this->assertDatabaseMissing('multimedia', ['id' => $multimedia->id]);
    }

    #[Test]
    public function test_returns_404_if_multimedia_not_found()
    {
        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/multimedia/manage/show/999');

        $response->assertStatus(404);
    }
}
