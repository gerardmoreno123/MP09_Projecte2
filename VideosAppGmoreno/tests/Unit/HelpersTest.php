<?php

namespace Tests\Unit;

use App\Models\Team;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

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

    public function test_create_default_video()
    {
        $video = create_default_video();

        $this->assertInstanceOf(Video::class, $video);
        $this->assertEquals('Titol perdefecte', $video->title);
        $this->assertEquals('Descripció perdefecte', $video->description);
        $this->assertEquals('https://www.youtube.com/watch?v=123456', $video->url);
        $this->assertEquals(Carbon::now()->toDateString(), $video->published_at->toDateString());
    }
}
