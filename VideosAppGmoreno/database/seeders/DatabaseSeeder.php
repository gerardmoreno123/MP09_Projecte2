<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Helpers\UserHelpers;
use App\Helpers\DefaultVideosHelper;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->create_permissions_and_roles();

        $userHelpers = new UserHelpers();
        $defaultUser = $userHelpers->create_default_user();
        $this->command->info("Default user created: {$defaultUser->email}");

        $defaultTeacher = $userHelpers->create_default_teacher();
        $this->command->info("Default teacher created: {$defaultTeacher->email}");

        $defaultRegularUser = $userHelpers->create_regular_user();
        $this->command->info("Default regular user created: {$defaultRegularUser->email}");

        $defaultManageVideosUser = $userHelpers->create_video_manager_user();
        $this->command->info("Default video manager created: {$defaultManageVideosUser->email}");

        $defaultSuperAdmin = $userHelpers->create_superadmin_user();
        $this->command->info("Default super admin created: {$defaultSuperAdmin->email}");

        $defaultVideos = DefaultVideosHelper::create_default_videos();
        $this->command->info("Videos created: " . $defaultVideos->pluck('title')->join(', '));
    }

    private function create_permissions_and_roles(): void
    {
        UserHelpers::create_video_permissions();

        $viewerRole = Role::firstOrCreate(['name' => 'viewer']);
        $viewerRole->givePermissionTo('view-videos');

        $videoManagerRole = Role::firstOrCreate(['name' => 'video-manager']);
        $videoManagerRole->givePermissionTo(['view-videos', 'create-videos', 'edit-videos', 'delete-videos']);

        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdminRole->givePermissionTo(Permission::all());

        $this->command->info('Roles and permissions created');
    }
}
