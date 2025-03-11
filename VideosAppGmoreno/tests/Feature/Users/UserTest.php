<?php

namespace Tests\Feature\Users;

use App\Helpers\UserHelpers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Crear permisos necesarios
        Permission::create(['name' => 'view-users']);
        Permission::create(['name' => 'create-users']);
        Permission::create(['name' => 'edit-users']);
        Permission::create(['name' => 'delete-users']);;
        Permission::create(['name' => 'super-admin']);

        $viewerRole = Role::create(['name' => 'viewer']);
        $videoManagerRole = Role::create(['name' => 'user-manager']);
        $superAdminRole = Role::create(['name' => 'super-admin']);

        $viewerRole->givePermissionTo('view-users');
        $videoManagerRole->givePermissionTo(['view-users', 'create-users', 'edit-users', 'delete-users']);
        $superAdminRole->givePermissionTo(Permission::all());
    }

    public const TESTED_BY = self::class;

    public function test_not_logged_users_cannot_see_default_users_page()
    {
        $response = $this->get('/users');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_not_logged_users_cannot_see_user_show_page()
    {
        $response = $this->get('/users/1');

        $response->assertStatus(302);
        $response->assertRedirect('/login');

    }

    public function test_user_without_permissions_can_see_default_users_page(): void
    {
        $user = (new UserHelpers())->create_default_user();

        $response = $this->actingAs($user)->get('/users');

        $response->assertStatus(200);
    }

    public function test_user_with_permissions_can_see_default_users_page(): void
    {
        $user = (new UserHelpers())->create_default_user();
        $user->assignRole('user-manager');

        $response = $this->actingAs($user)->get('/users');

        $response->assertStatus(200);
    }

    public function test_user_without_permissions_can_see_user_show_page()
    {
        $user = (new UserHelpers())->create_default_user();

        $response = $this->actingAs($user)->get('/users/1');

        $response->assertStatus(200);
    }

    public function user_with_permissions_can_see_user_show_page()
    {
        $user = (new UserHelpers())->create_default_user();
        $user->assignRole('user-manager');

        $response = $this->actingAs($user)->get('/users/1');

        $response->assertStatus(200);
    }
}
