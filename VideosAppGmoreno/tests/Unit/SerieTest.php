<?php

namespace Tests\Unit;

use App\Models\Serie;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class SerieTest extends TestCase
{
    use RefreshDatabase;

    public const TESTED_BY = self::class;

    protected function setUp(): void
    {
        parent::setUp();

        Permission::create(['name' => 'super-admin']);

        //Seeder
        $this->seed();
    }

    public function test_serie_have_videos()
    {
        // Crear un usuario (necesario para user_id)
        $user = User::factory()->create();

        // Crear una serie
        $serie = Serie::factory()->create();

        // Crear videos asociados a la serie
        $videos = Video::factory()->count(3)->create([
            'serie_id' => $serie->id,
            'user_id' => $user->id,
            'previous_id' => null,
            'next_id' => null,
        ]);

        // Refrescar la serie para cargar relaciones
        $serie->refresh();

        // Verificar que la serie tiene videos
        $this->assertEquals(3, $serie->videos->count());
        $this->assertTrue($serie->videos->contains($videos[0]));
        $this->assertTrue($serie->videos->contains($videos[1]));
        $this->assertTrue($serie->videos->contains($videos[2]));
    }
}
