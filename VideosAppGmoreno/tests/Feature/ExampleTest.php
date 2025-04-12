<?php

namespace Tests\Feature;

use App\Helpers\DefaultVideosHelper;
use App\Helpers\SerieHelper;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
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
        $serieManagerRole = Role::create(['name' => 'serie-manager']);
        $superAdminRole = Role::create(['name' => 'super-admin']);

        $viewerRole->givePermissionTo('view-videos');
        $videoManagerRole->givePermissionTo(['view-videos', 'create-videos', 'edit-videos', 'delete-videos']);
        $serieManagerRole->givePermissionTo(['view-videos', 'create-videos', 'edit-videos']);
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
        SerieHelper::create_series();
        DefaultVideosHelper::create_default_videos();

        $response = $this->get('/');

        $response->assertStatus(200);

        // Verifica que la vista contiene la variable 'videos'
        $response->assertViewHas('videos');

        // Verifica que la variable es una instancia de paginator
        $videos = $response->viewData('videos');
        $this->assertInstanceOf(LengthAwarePaginator::class, $videos);

        // Verifica que contiene al menos uno de los videos esperados
        $this->assertGreaterThan(0, $videos->count());
    }

}
