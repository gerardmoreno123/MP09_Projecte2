<?php

namespace Tests\Feature\Videos;

use App\Helpers\DefaultVideosHelper;
use App\Helpers\UserHelpers;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class VideosManageControllerTest extends TestCase
{
    use RefreshDatabase;

    public const TESTED_BY = self::class;

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
        $superAdminRole = Role::create(['name' => 'super-admin']);

        $viewerRole->givePermissionTo('view-videos');
        $videoManagerRole->givePermissionTo(['view-videos', 'create-videos', 'edit-videos', 'delete-videos']);
        $superAdminRole->givePermissionTo(Permission::all());
    }

    protected function loginAsRegularUser(): User
    {
        $user = (new UserHelpers())->create_regular_user();
        $this->actingAs($user);

        return $user;
    }

    protected function loginAsVideoManager(): User
    {
        $user = (new UserHelpers())->create_video_manager_user();
        $this->actingAs($user);

        return $user;
    }

    protected function loginAsSuperAdmin(): User
    {
        $user = (new UserHelpers())->create_superadmin_user();
        $this->actingAs($user);

        return $user;
    }

    public function test_user_with_permissions_can_see_add_videos()
    {
        $user = $this->loginAsVideoManager();
        $response = $this->actingAs($user)->get('/videos/manage/create');
        $response->assertStatus(200);
    }

    public function test_user_without_videos_manage_create_cannot_see_add_videos()
    {
        $user = $this->loginAsRegularUser();
        $response = $this->actingAs($user)->get('/videos/manage/create');
        $response->assertStatus(403);
    }

    public function test_user_with_permissions_can_store_videos()
    {
        $user = $this->loginAsVideoManager();
        $response = $this->actingAs($user)->post('/videos/manage', [
            'title' => 'New Video',
            'description' => 'New Video Description',
            'url' => 'https://www.youtube.com/embed/newvideo',
            'published_at' => now(),
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('videos', ['title' => 'New Video']);
    }

    public function test_user_without_permissions_cannot_store_videos()
    {
        $user = $this->loginAsRegularUser();
        $response = $this->actingAs($user)->post('/videos/manage', [
            'title' => 'New Video',
            'description' => 'New Video Description',
            'url' => 'https://www.youtube.com/embed/newvideo',
            'published_at' => now(),
        ]);
        $response->assertStatus(403);
    }

    public function test_user_with_permissions_can_destroy_videos()
    {
        $user = $this->loginAsVideoManager();
        $video = DefaultVideosHelper::create_default_videos()->first();
        $response = $this->actingAs($user)->delete("/videos/manage/{$video->id}");
        $response->assertStatus(302);
        $this->assertDatabaseMissing('videos', ['id' => $video->id]);
    }

    public function test_user_without_permissions_cannot_destroy_videos()
    {
        $user = $this->loginAsRegularUser();
        $video = DefaultVideosHelper::create_default_videos()->first();
        $response = $this->actingAs($user)->delete("/videos/manage/{$video->id}");
        $response->assertStatus(403);
    }

    public function test_user_with_permissions_can_see_edit_videos()
    {
        $user = $this->loginAsVideoManager();
        $video = DefaultVideosHelper::create_default_videos()->first();
        $response = $this->actingAs($user)->get("/videos/manage/{$video->id}/edit");
        $response->assertStatus(200);
    }

    public function test_user_without_permissions_cannot_see_edit_videos()
    {
        $user = $this->loginAsRegularUser();
        $video = DefaultVideosHelper::create_default_videos()->first();
        $response = $this->actingAs($user)->get("/videos/manage/{$video->id}/edit");
        $response->assertStatus(403);
    }

    public function test_user_with_permissions_can_update_videos()
    {
        $user = $this->loginAsVideoManager();
        $video = DefaultVideosHelper::create_default_videos()->first();
        $response = $this->actingAs($user)->put("/videos/manage/{$video->id}", [
            'title' => 'Updated Video Title',
            'description' => 'Updated Video Description',
            'url' => 'https://www.youtube.com/embed/updatedvideo',
            'published_at' => now(),
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('videos', ['title' => 'Updated Video Title']);
    }

    public function test_user_without_permissions_cannot_update_videos()
    {
        $user = $this->loginAsRegularUser();
        $video = DefaultVideosHelper::create_default_videos()->first();
        $response = $this->actingAs($user)->put("/videos/manage/{$video->id}", [
            'title' => 'Updated Video Title',
            'description' => 'Updated Video Description',
            'url' => 'https://www.youtube.com/embed/updatedvideo',
            'published_at' => now(),
        ]);
        $response->assertStatus(403);
    }

    public function test_user_with_permissions_can_manage_videos()
    {
        $user = $this->loginAsVideoManager();
        DefaultVideosHelper::create_default_videos();
        $response = $this->actingAs($user)->get('/videos/manage');
        $response->assertStatus(200);
    }

    public function test_regular_users_cannot_manage_videos()
    {
        $user = $this->loginAsRegularUser();
        $response = $this->actingAs($user)->get('/videos/manage');
        $response->assertStatus(403);
    }
    public function test_guest_users_cannot_manage_videos()
    {
        $response = $this->get('/videos/manage');
        $response->assertStatus(302);

        $response->assertRedirect('/login');
    }

    public function test_superadmins_can_manage_videos()
    {
        $user = $this->loginAsSuperAdmin();
        $response = $this->actingAs($user)->get('/videos/manage');
        $response->assertStatus(200);
    }
}
