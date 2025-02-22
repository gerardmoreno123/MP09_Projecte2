<?php

namespace Tests\Feature\Videos;

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

    protected function setUp(): void
    {
        parent::setUp();

        // Crear permisos en la base de datos de pruebas
        Permission::create(['name' => 'view-videos']);
        Permission::create(['name' => 'manage-videos']);
        Permission::create(['name' => 'edit-users']);
        Permission::create(['name' => 'super-admin']);

        Role::create(['name' => 'video-manager']);
        Role::create(['name' => 'super-admin']);
    }

    public function test_user_with_permissions_can_manage_videos()
    {
        $user = $this->loginAsVideoManager();
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
    }

    public function test_superadmins_can_manage_videos()
    {
        $user = $this->loginAsSuperAdmin();
        $response = $this->actingAs($user)->get('/videos/manage');
        $response->assertStatus(200);
    }

    protected function loginAsVideoManager()
    {
        $user = User::factory()->create();
        $role = Role::findByName('video-manager');
        $user->assignRole($role);
        $user->givePermissionTo('manage-videos');
        $this->actingAs($user);

        return $user;
    }

    protected function loginAsSuperAdmin()
    {
        $user = User::factory()->create();
        $role = Role::findByName('super-admin');
        $user->assignRole($role);
        $user->givePermissionTo(['manage-videos', 'view-videos', 'super-admin']);
        $this->actingAs($user);

        return $user;
    }

    protected function loginAsRegularUser()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('view-videos');
        $this->actingAs($user);

        return $user;
    }
}
