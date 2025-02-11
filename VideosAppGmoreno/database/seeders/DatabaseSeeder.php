<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Helpers\UserHelpers;
use App\Helpers\DefaultVideosHelper;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        //Crear permisos
        $this->create_permissions();

        //Crear usuaris i videos per defecte
        $userHelpers = new UserHelpers();
        $defaultUser = $userHelpers->create_default_user();
        $this->command->info("Usuario por defecto creado: {$defaultUser->email}");

        $defaultTeacher = $userHelpers->create_default_teacher();
        $this->command->info("Profesor por defecto creado: {$defaultTeacher->email}");

        $defaultRegularUser = $userHelpers->create_regular_user();
        $this->command->info("Usuario regular por defecto creado: {$defaultRegularUser->email}");

        $defaultManageVideosUser = $userHelpers->create_video_manager_user();
        $this->command->info("Usuario administrador de videos por defecto creado: {$defaultManageVideosUser->email}");

        $defaultSuperAdmin = $userHelpers->create_superadmin_user();
        $this->command->info("Super administrador por defecto creado: {$defaultSuperAdmin->email}");

        $defaultVideos = DefaultVideosHelper::create_default_videos();
        $this->command->info("Videos creados: " . $defaultVideos->pluck('title')->join(', '));
    }

    /**
     * Crear permisos
     */
    private function create_permissions(): void
    {
        $permissions = [
            'view-videos',
            'manage-videos',
            'edit-users',
            'super-admin',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $this->command->info('Permisos creados: ' . implode(', ', $permissions));
    }
}
