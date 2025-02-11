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

    protected function setUp(): void
    {
        parent::setUp();

        // Crear permisos en la base de datos de pruebas
        Permission::create(['name' => 'view-videos']);
        Permission::create(['name' => 'manage-videos']);
        Permission::create(['name' => 'edit-users']);
        Permission::create(['name' => 'super-admin']);
    }

    public function test_user_with_permissions_can_manage_videos()
    {
        $user = $this->loginAsVideoManager();
        $this->assertTrue($user->can('manage-videos'));
    }

    public function test_regular_users_cannot_manage_videos()
    {
        $user = $this->loginAsRegularUser();
        $this->assertFalse($user->can('manage-videos'));
    }

    public function test_guest_users_cannot_manage_videos()
    {
        $user = new User();
        $this->assertFalse($user->can('manage-videos'));
    }

    public function test_superadmins_can_manage_videos()
    {
        $user = $this->loginAsSuperAdmin();
        $this->assertTrue($user->can('manage-videos'));
    }

    protected function loginAsVideoManager()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('manage-videos');
        $this->actingAs($user);

        return $user;
    }

    protected function loginAsSuperAdmin()
    {
        $user = User::factory()->create();
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
