<?php

namespace Database\Factories;

use App\Models\Multimedia;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MultimediaFactory extends Factory
{
    protected $model = Multimedia::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'file_path' => 'multimedia/testfile.mp4',
            'file_type' => 'video',
            'user_id' => User::factory(),
            'published_at' => now(),
        ];
    }
}
