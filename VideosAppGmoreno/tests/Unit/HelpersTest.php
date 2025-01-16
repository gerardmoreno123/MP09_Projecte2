<?php

namespace Tests\Unit;

use App\Models\Team;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;
use App\Helpers\VideoHelper;

class HelpersTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_default_user()
    {
        $user = create_default_user();

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(config('userdefaults.default_user.name'), $user->name);
        $this->assertEquals(config('userdefaults.default_user.email'), $user->email);
        $this->assertTrue(\Hash::check(config('userdefaults.default_user.password'), $user->password));
        $this->assertInstanceOf(Team::class, $user->currentTeam);
        $this->assertEquals($user->current_team_id, $user->currentTeam->id);
    }

    public function test_create_default_teacher()
    {
        $teacher = create_default_teacher();

        $this->assertInstanceOf(User::class, $teacher);
        $this->assertEquals(config('userdefaults.default_teacher.name'), $teacher->name);
        $this->assertEquals(config('userdefaults.default_teacher.email'), $teacher->email);
        $this->assertTrue(\Hash::check(config('userdefaults.default_teacher.password'), $teacher->password));
        $this->assertInstanceOf(Team::class, $teacher->currentTeam);
        $this->assertEquals($teacher->current_team_id, $teacher->currentTeam->id);
    }

    public function test_getFormattedPublishedAtAttribute()
    {
        // Crea un video utilizando el factory
        $video = Video::factory()->create([
            'published_at' => Carbon::now()->subDays(3),
        ]);

        $formattedDate = VideoHelper::getFormattedPublishedAtAttribute($video->published_at);

        $this->assertEquals(Carbon::now()->subDays(3)->isoFormat('D [de] MMMM [de] YYYY'), $formattedDate);
    }

    public function test_getFormattedForHumansPublishedAtAttribute()
    {
        // Crea un video utilizando el factory
        $video = Video::factory()->create([
            'published_at' => Carbon::now()->subDays(3),
        ]);

        $formattedForHumans = VideoHelper::getFormattedForHumansPublishedAtAttribute($video->published_at);

        $this->assertEquals(Carbon::now()->subDays(3)->diffForHumans(), $formattedForHumans);
    }

    public function test_getPublishedAtTimestampAttribute()
    {
        // Crea un video utilizando el factory
        $video = Video::factory()->create([
            'published_at' => Carbon::now()->subDays(3),
        ]);

        $timestamp = VideoHelper::getPublishedAtTimestampAttribute($video->published_at);

        $this->assertEquals(Carbon::now()->subDays(3)->timestamp, $timestamp);
    }
}
