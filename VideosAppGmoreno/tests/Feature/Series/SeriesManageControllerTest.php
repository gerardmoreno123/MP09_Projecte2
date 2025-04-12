<?php

namespace Tests\Feature\Series;

use App\Models\Serie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SeriesManageControllerTest extends TestCase
{
    use RefreshDatabase;

    public const TESTED_BY = self::class;

    protected function setUp(): void
    {
        parent::setUp();

        // Configurar roles y permisos
        $this->artisan('db:seed', ['--class' => 'DatabaseSeeder']);
    }

    protected function loginAsSerieManager()
    {
        $user = User::factory()->create();
        $user->assignRole('serie-manager');
        $this->actingAs($user);
        return $user;
    }

    protected function loginAsSuperAdmin()
    {
        $user = User::factory()->create();
        $user->assignRole('super-admin');
        $this->actingAs($user);
        return $user;
    }

    protected function loginAsRegularUser()
    {
        $user = User::factory()->create();
        $user->assignRole('viewer');
        $this->actingAs($user);
        return $user;
    }

    public function test_user_with_permissions_can_see_add_series()
    {
        $user = $this->loginAsSerieManager();

        $response = $this->get(route('series.manage.create'));

        $response->assertStatus(200);
        $response->assertViewIs('series.manage.create');
    }

    public function test_user_without_series_manage_create_cannot_see_add_series()
    {
        $user = $this->loginAsRegularUser();

        $response = $this->get(route('series.manage.create'));

        $response->assertStatus(403);
    }

    public function test_user_with_permissions_can_store_series()
    {
        $user = $this->loginAsSerieManager();

        $data = [
            'title' => 'Test Serie',
            'description' => 'This is a test serie.',
            'user_name' => 'Test User',
        ];

        $response = $this->post(route('series.manage.store'), $data);

        $response->assertSessionHas('success', 'Sèrie creada amb èxit.');
        $this->assertDatabaseHas('series', ['title' => 'Test Serie']);
    }

    public function test_user_without_permissions_cannot_store_series()
    {
        $user = $this->loginAsRegularUser();

        $data = [
            'title' => 'Test Serie',
            'description' => 'This is a test serie.',
            'user_name' => 'Test User',
        ];

        $response = $this->post(route('series.manage.store'), $data);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('series', ['title' => 'Test Serie']);
    }

    public function test_user_with_permissions_can_destroy_series()
    {
        $user = $this->loginAsSerieManager();
        $serie = Serie::factory()->create();

        $response = $this->delete(route('series.manage.destroy', $serie));

        $response->assertRedirect(route('series.manage.index'));
        $response->assertSessionHas('success', 'Sèrie eliminada amb èxit.');
        $this->assertDatabaseMissing('series', ['id' => $serie->id]);
    }

    public function test_user_without_permissions_cannot_destroy_series()
    {
        $user = $this->loginAsRegularUser();
        $serie = Serie::factory()->create();

        $response = $this->delete(route('series.manage.destroy', $serie));

        $response->assertStatus(403);
        $this->assertDatabaseHas('series', ['id' => $serie->id]);
    }

    public function test_user_with_permissions_can_see_edit_series()
    {
        $user = $this->loginAsSerieManager();
        $serie = Serie::factory()->create();

        $response = $this->get(route('series.manage.edit', $serie));

        $response->assertStatus(200);
        $response->assertViewIs('series.manage.edit');
        $response->assertViewHas('serie', $serie);
    }

    public function test_user_without_permissions_cannot_see_edit_series()
    {
        $user = $this->loginAsRegularUser();
        $serie = Serie::factory()->create();

        $response = $this->get(route('series.manage.edit', $serie));

        $response->assertStatus(403);
    }

    public function test_user_with_permissions_can_update_series()
    {
        $user = $this->loginAsSerieManager();
        $serie = Serie::factory()->create();

        $data = [
            'title' => 'Updated Serie',
            'description' => 'Updated description.',
            'user_name' => 'Updated User',
        ];

        $response = $this->put(route('series.manage.update', $serie), $data);

        $response->assertRedirect(route('series.manage.show', $serie));
        $response->assertSessionHas('success', 'Sèrie actualitzada amb èxit.');
        $this->assertDatabaseHas('series', ['id' => $serie->id, 'title' => 'Updated Serie']);
    }

    public function test_user_without_permissions_cannot_update_series()
    {
        $user = $this->loginAsRegularUser();
        $serie = Serie::factory()->create();

        $data = [
            'title' => 'Updated Serie',
            'description' => 'Updated description.',
            'user_name' => 'Updated User',
        ];

        $response = $this->put(route('series.manage.update', $serie), $data);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('series', ['id' => $serie->id, 'title' => 'Updated Serie']);
    }

    public function test_user_with_permissions_can_manage_series()
    {
        $user = $this->loginAsSerieManager();
        $serie = Serie::factory()->create();

        $response = $this->get(route('series.manage.index'));

        $response->assertStatus(200);
        $response->assertViewIs('series.manage.index');
    }

    public function test_regular_users_cannot_manage_series()
    {
        $user = $this->loginAsRegularUser();

        $response = $this->get(route('series.manage.index'));

        $response->assertStatus(403);
    }

    public function test_guest_users_cannot_manage_series()
    {
        $response = $this->get(route('series.manage.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_seriemanagers_can_manage_series()
    {
        $user = $this->loginAsSerieManager();
        $serie = Serie::factory()->create();

        $response = $this->get(route('series.manage.index'));

        $response->assertStatus(200);
        $response->assertViewIs('series.manage.index');
    }

    public function test_superadmins_can_manage_series()
    {
        $user = $this->loginAsSuperAdmin();
        $serie = Serie::factory()->create();

        $response = $this->get(route('series.manage.index'));

        $response->assertStatus(200);
        $response->assertViewIs('series.manage.index');
    }
}
