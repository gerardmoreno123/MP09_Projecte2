<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserHelpers
{
    public static function create_default_user(): User
    {
        $password = config('userdefaults.default_user.password');
        $existingUser = User::where('email', config('userdefaults.default_user.email'))->first();

        if ($existingUser) {
            return $existingUser;
        }

        $user = User::create([
            'name' => config('userdefaults.default_user.name'),
            'email' => config('userdefaults.default_user.email'),
            'password' => Hash::make($password),
        ]);

        self::add_personal_team($user);
        $user->assignRole('viewer');

        return $user;
    }

    public static function create_default_teacher(): User
    {
        $password = config('userdefaults.default_teacher.password');
        $existingTeacher = User::where('email', config('userdefaults.default_teacher.email'))->first();

        if ($existingTeacher) {
            return $existingTeacher;
        }

        $teacher = User::create([
            'name' => config('userdefaults.default_teacher.name'),
            'email' => config('userdefaults.default_teacher.email'),
            'password' => Hash::make($password),
            'super_admin' => true,
        ]);

        self::add_personal_team($teacher);
        $teacher->assignRole('super-admin');

        return $teacher;
    }

    public static function create_regular_user(): User
    {
        $existingRegularUser = User::where('email', 'regular@videosapp.com')->first();

        if ($existingRegularUser) {
            return $existingRegularUser;
        }

        $user = User::create([
            'name' => 'Regular',
            'email' => 'regular@videosapp.com',
            'password' => Hash::make('123456789'),
        ]);

        self::add_personal_team($user);
        $user->assignRole('viewer');

        return $user;
    }

    public static function create_video_manager_user(): User
    {
        $existingVideoManager = User::where('email', 'videosmanager@videosapp.com')->first();

        if ($existingVideoManager) {
            return $existingVideoManager;
        }

        $user = User::create([
            'name' => 'Video Manager',
            'email' => 'videosmanager@videosapp.com',
            'password' => Hash::make('123456789'),
        ]);

        self::add_personal_team($user);
        $user->assignRole('video-manager');

        return $user;
    }

    public static function create_user_manager_user(): User
    {
        $existingUserManager = User::where('email', 'usersmanager@videosapp.com')->first();

        if ($existingUserManager) {
            return $existingUserManager;
        }

        $user = User::create([
            'name' => 'User Manager',
            'email' => 'usersmanager@videosapp.com',
            'password' => Hash::make('123456789'),
        ]);

        self::add_personal_team($user);
        $user->assignRole('user-manager');

        return $user;
    }

    public static function create_serie_manager_user(): User
    {
        $existingSerieManager = User::where('email', 'seriesmanager@videosapp.com')->first();

        if ($existingSerieManager) {
            return $existingSerieManager;
        }

        $user = User::create([
            'name' => 'Serie Manager',
            'email' => 'seriesmanager@videosapp.com',
            'password' => Hash::make('123456789'),
        ]);

        self::add_personal_team($user);
        $user->assignRole('serie-manager');

        return $user;
    }

    public static function create_superadmin_user(): User
    {
        $existingSuperAdmin = User::where('email', 'superadmin@videosapp.com')->first();

        if ($existingSuperAdmin) {
            return $existingSuperAdmin;
        }

        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@videosapp.com',
            'password' => Hash::make('123456789'),
            'super_admin' => true,
        ]);

        self::add_personal_team($user);
        $user->assignRole('super-admin');

        return $user;
    }

    public static function add_personal_team(User $user)
    {
        $team = Team::forceCreate([
            'user_id' => $user->id,
            'team_name' => explode(' ', $user->name, 2)[0] . "'s Team",
            'personal_team' => true,
        ]);

        $user->current_team_id = $team->id;
        $user->save();
    }

    public static function create_video_permissions()
    {
        $permissions = [
            //CRUD permissions for videos
            'view-videos',
            'create-videos',
            'edit-videos',
            'delete-videos',

            //CRUD permissions for users
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',

            //CRUD permissions for series
            'view-series',
            'create-series',
            'edit-series',
            'delete-series',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
