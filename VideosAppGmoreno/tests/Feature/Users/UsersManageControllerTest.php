<?php

namespace Tests\Feature\Users;

use App\Helpers\DefaultVideosHelper;
use App\Helpers\UserHelpers;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UsersManageControllerTest extends TestCase
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

        Permission::create(['name' => 'view-users']);
        Permission::create(['name' => 'create-users']);
        Permission::create(['name' => 'edit-users']);
        Permission::create(['name' => 'delete-users']);;
        Permission::create(['name' => 'super-admin']);

        $viewerRole = Role::create(['name' => 'viewer']);
        $videoManagerRole = Role::create(['name' => 'video-manager']);
        $userManagerRole = Role::create(['name' => 'user-manager']);
        $superAdminRole = Role::create(['name' => 'super-admin']);

        $viewerRole->givePermissionTo('view-users');
        $videoManagerRole->givePermissionTo(['view-videos', 'create-videos', 'edit-videos', 'delete-videos']);
        $userManagerRole->givePermissionTo(['view-users', 'create-users', 'edit-users', 'delete-users']);
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
        $user->assignRole('user-manager');
        $this->actingAs($user);

        return $user;
    }

    protected function loginAsSuperAdmin(): User
    {
        $user = (new UserHelpers())->create_superadmin_user();
        $this->actingAs($user);

        return $user;
    }

    public function test_user_with_permissions_can_see_users()
    {
        $this->loginAsVideoManager();

        $response = $this->get(route('users.manage.index'));

        $response->assertStatus(200);
    }

    public function test_user_without_users_manage_create_cannot_see_add_users()
    {
        $this->loginAsRegularUser();

        $response = $this->get(route('users.manage.create'));

        $response->assertStatus(403);
    }

    public function test_user_with_permissions_can_store_users()
    {
        $this->loginAsVideoManager();

        $response = $this->post(route('users.manage.store'), [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password',
            'roles' => ['viewer']
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');
    }

    public function test_user_without_permissions_cannot_store_users()
    {
        $this->loginAsRegularUser();

        $response = $this->post(route('users.manage.store'), [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password',
            'roles' => ['viewer']
        ]);

        $response->assertStatus(403);
    }

    public function test_user_with_permissions_can_destroy_users()
    {
        $this->loginAsVideoManager();

        $user = (new UserHelpers())->create_regular_user();

        $response = $this->delete(route('users.manage.destroy', $user->id));

        $response->assertStatus(302);
        $response->assertSessionHas('success');
    }

    public function test_user_without_permissions_cannot_destroy_users()
    {
        $this->loginAsRegularUser();

        $user = (new UserHelpers())->create_regular_user();

        $response = $this->delete(route('users.manage.destroy', $user->id));

        $response->assertStatus(403);
    }

    public function test_user_with_permissions_can_edit_users()
    {
        $this->loginAsVideoManager();

        $user = (new UserHelpers())->create_regular_user();

        $response = $this->get(route('users.manage.edit', $user->id));

        $response->assertStatus(200);
    }

    public function test_user_without_permissions_cannot_edit_users()
    {
        $this->loginAsRegularUser();

        $user = (new UserHelpers())->create_regular_user();

        $response = $this->get(route('users.manage.edit', $user->id));

        $response->assertStatus(403);
    }

    public function test_user_with_permissions_can_update_users()
    {
        $this->loginAsVideoManager();

        $user = (new UserHelpers())->create_regular_user();

        $response = $this->put(route('users.manage.update', $user->id), [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password',
            'roles' => ['viewer']
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');
    }

    public function test_user_without_permissions_cannot_update_users()
    {
        $this->loginAsRegularUser();

        $user = (new UserHelpers())->create_regular_user();

        $response = $this->put(route('users.manage.update', $user->id), [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password',
            'roles' => ['viewer']
        ]);

        $response->assertStatus(403);
    }

    public function test_user_with_permissions_can_manage_users()
    {
        $this->loginAsVideoManager();

        $response = $this->get(route('users.manage.index'));

        $response->assertStatus(200);
    }

    public function test_regular_users_cannot_manage_users()
    {
        $this->loginAsRegularUser();

        $response = $this->get(route('users.manage.index'));

        $response->assertStatus(403);
    }

    public function test_guest_users_cannot_manage_users()
    {
        $response = $this->get(route('users.manage.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_superadmins_can_manage_users()
    {
        $this->loginAsSuperAdmin();

        $response = $this->get(route('users.manage.index'));

        $response->assertStatus(200);
    }
}
