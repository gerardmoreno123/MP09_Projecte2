<?php

namespace Tests\Unit;

use App\Helpers\UserHelpers;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class VideosTest extends TestCase
{
    use RefreshDatabase;

    public const TESTED_BY = self::class;

    protected $regular;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setLocale('ca');

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

        // Create default users
        $this->regular = (new UserHelpers())->create_regular_user();
    }

    public function test_can_get_formatted_published_at_date()
    {
        $video = Video::create([
            'title' => 'Video de prova',
            'description' => 'Descripció de prova',
            'url' => 'http://test.com',
            'published_at' => Carbon::now()->toDateString(),
            'user_id' => $this->regular->id,
        ]);

        $formattedDate = $video->getFormattedPublishedAtAttribute();

        $expectedDate = Carbon::now()->locale('ca')->isoFormat('D [de] MMMM [de] YYYY');

        $this->assertEquals($expectedDate, $formattedDate);
    }

    public function test_can_get_formatted_for_humans_published_at_date()
    {
        $video = Video::create([
            'title' => 'Video de prova',
            'description' => 'Descripció de prova',
            'url' => 'http://test.com',
            'published_at' => Carbon::now()->subDays(3)->toDateString(),
            'user_id' => $this->regular->id,
        ]);

        $formattedDate = $video->getFormattedForHumansPublishedAtAttribute();

        $this->assertEquals('fa 3 dies', $formattedDate);
    }

    public function test_can_get_published_at_timestamp()
    {
        $video = Video::create([
            'title' => 'Video de prova',
            'description' => 'Descripció de prova',
            'url' => 'http://test.com',
            'published_at' => Carbon::now()->toDateString(),
            'user_id' => $this->regular->id,
        ]);

        $timestamp = $video->getPublishedAtTimestampAttribute();

        $this->assertEquals($video->published_at->timestamp, $timestamp);
    }

    public function test_can_get_formatted_published_at_date_when_not_published()
    {
        $video = Video::create([
            'title' => 'Video sense data',
            'description' => 'Aquest video no ha estat publicat encara',
            'url' => 'http://test.com',
            'published_at' => null,
            'user_id' => $this->regular->id,
        ]);

        $formattedDate = $video->getFormattedPublishedAtAttribute();

        $this->assertEquals(null, $formattedDate);
    }
}
