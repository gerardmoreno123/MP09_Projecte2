<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Helpers\UserHelpers;
use App\Helpers\DefaultVideosHelper;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $userHelpers = new UserHelpers();
        $defaultUser = $userHelpers->create_default_user();
        $this->command->info("Usuario por defecto creado: {$defaultUser->email}");

        $defaultTeacher = $userHelpers->create_default_teacher();
        $this->command->info("Profesor por defecto creado: {$defaultTeacher->email}");

        $defaultVideos = DefaultVideosHelper::create_default_videos();
        $this->command->info("Videos creados: " . $defaultVideos->pluck('title')->join(', '));
    }
}
