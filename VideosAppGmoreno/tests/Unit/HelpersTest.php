<?php

namespace Tests\Unit;

use App\Models\Team;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Helpers\DefaultVideosHelper;
use App\Helpers\UserHelpers;

class HelpersTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_default_user()
    {
        $user = (new UserHelpers())->create_default_user();

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(config('userdefaults.default_user.name'), $user->name);
        $this->assertEquals(config('userdefaults.default_user.email'), $user->email);
        $this->assertTrue(\Hash::check(config('userdefaults.default_user.password'), $user->password));
        $this->assertInstanceOf(Team::class, $user->currentTeam);
        $this->assertEquals($user->current_team_id, $user->currentTeam->id);
    }

    public function test_create_default_teacher()
    {
        $teacher = (new UserHelpers())->create_default_teacher();

        $this->assertInstanceOf(User::class, $teacher);
        $this->assertEquals(config('userdefaults.default_teacher.name'), $teacher->name);
        $this->assertEquals(config('userdefaults.default_teacher.email'), $teacher->email);
        $this->assertTrue(\Hash::check(config('userdefaults.default_teacher.password'), $teacher->password));
        $this->assertInstanceOf(Team::class, $teacher->currentTeam);
        $this->assertEquals($teacher->current_team_id, $teacher->currentTeam->id);
    }

    public function test_create_default_videos()
    {
        // Llama a la función helper
        $videos = (new DefaultVideosHelper())->create_default_videos();

        // Verifica que se han creado tres instancias de Video
        $this->assertCount(3, $videos);

        // Verifica que cada instancia es del tipo Video
        foreach ($videos as $video) {
            $this->assertInstanceOf(Video::class, $video);
        }

        // Verifica que las propiedades del primer video sean correctas
        $video1 = $videos[0];
        $this->assertEquals('Video 1', $video1->title);
        $this->assertEquals('Descripció del video 1', $video1->description);
        $this->assertEquals('https://www.youtube.com/watch?v=video1', $video1->url);
        $this->assertEquals(null, $video1->previous_id);
        $this->assertEquals(2, $video1->next_id);

        // Verifica las propiedades del segundo video
        $video2 = $videos[1];
        $this->assertEquals('Video 2', $video2->title);
        $this->assertEquals(1, $video2->previous_id);
        $this->assertEquals(3, $video2->next_id);

        // Verifica las propiedades del tercer video
        $video3 = $videos[2];
        $this->assertEquals('Video 3', $video3->title);
        $this->assertEquals(2, $video3->previous_id);
        $this->assertEquals(null, $video3->next_id);

        // Verifica que los videos existen en la base de datos
        $this->assertDatabaseHas('videos', ['title' => 'Video 1']);
        $this->assertDatabaseHas('videos', ['title' => 'Video 2']);
        $this->assertDatabaseHas('videos', ['title' => 'Video 3']);
    }
}
