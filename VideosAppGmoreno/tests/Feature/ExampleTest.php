<?php

namespace Tests\Feature;

use App\Helpers\DefaultVideosHelper;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear permisos necesarios
        Permission::create(['name' => 'view-videos']);
        Permission::create(['name' => 'create-videos']);
        Permission::create(['name' => 'edit-videos']);
        Permission::create(['name' => 'delete-videos']);
        Permission::create(['name' => 'super-admin']);

        $viewerRole = Role::create(['name' => 'viewer']);
        $videoManagerRole = Role::create(['name' => 'video-manager']);
        $superAdminRole = Role::create(['name' => 'super-admin']);

        $viewerRole->givePermissionTo('view-videos');
        $videoManagerRole->givePermissionTo(['view-videos', 'create-videos', 'edit-videos', 'delete-videos']);
        $superAdminRole->givePermissionTo(Permission::all());
    }

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
