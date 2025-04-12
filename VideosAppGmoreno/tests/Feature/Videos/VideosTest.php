<?php

namespace Tests\Feature\Videos;

use App\Helpers\DefaultVideosHelper;
use App\Helpers\SerieHelper;
use App\Helpers\UserHelpers;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class VideosTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Crear permisos necesarios
        Permission::create(['name' => 'view-videos']);
        Permission::create(['name' => 'create-videos']);
        Permission::create(['name' => 'edit-videos']);
        Permission::create(['name' => 'delete-videos']);;
        Permission::create(['name' => 'super-admin']);

        $viewerRole = Role::create(['name' => 'viewer']);
        $videoManagerRole = Role::create(['name' => 'video-manager']);
        $serieManagerRole = Role::create(['name' => 'serie-manager']);
        $superAdminRole = Role::create(['name' => 'super-admin']);

        $viewerRole->givePermissionTo('view-videos');
        $videoManagerRole->givePermissionTo(['view-videos', 'create-videos', 'edit-videos', 'delete-videos']);
        $serieManagerRole->givePermissionTo(['view-videos', 'create-videos', 'edit-videos', 'delete-videos']);
        $superAdminRole->givePermissionTo(Permission::all());
    }

    public const TESTED_BY = self::class;

    public function test_users_can_view_videos()
    {
        $user = (new UserHelpers())->create_default_user();
        $serie = SerieHelper::create_series()->first();
        $videos = (new DefaultVideosHelper())->create_default_videos();

        $video = $videos->first();

        $response = $this->actingAs($user)->get("/{$video->id}");

        $response->assertStatus(200);
        $response->assertSee($video->title);
    }

    public function test_users_cannot_view_not_existing_videos()
    {
        $user = (new UserHelpers())->create_default_user();

        $response = $this->actingAs($user)->get('/9999');

        $response->assertStatus(404);
    }

    public function test_user_without_permissions_can_see_default_videos_page()
    {
        $user = (new UserHelpers())->create_regular_user();
        // Eliminar el rol i els permisos
        $user->removeRole('viewer');
        $user->revokePermissionTo('view-videos');

        $serie = SerieHelper::create_series()->first();
        $videos = (new DefaultVideosHelper())->create_default_videos();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        foreach ($videos as $video) {
            $response->assertSee($video->title);
        }
    }

    public function test_user_with_permissions_can_see_default_videos_page()
    {
        $user = (new UserHelpers())->create_video_manager_user();
        $serie = SerieHelper::create_series()->first();
        $videos = (new DefaultVideosHelper())->create_default_videos();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        foreach ($videos as $video) {
            $response->assertSee($video->title);
        }
    }

    public function test_not_logged_users_can_see_default_videos_page()
    {
        $serie = SerieHelper::create_series()->first();
        $videos = (new DefaultVideosHelper())->create_default_videos();

        $response = $this->get('/');

        $response->assertStatus(200);
        foreach ($videos as $video) {
            $response->assertSee($video->title);
        }
    }
}
