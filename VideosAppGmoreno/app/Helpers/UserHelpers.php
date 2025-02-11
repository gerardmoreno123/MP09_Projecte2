<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserHelpers
{
    public static function create_default_user(): User
    {
        $password = config('userdefaults.default_user.password');
        if (!is_string($password)) {
            throw new InvalidArgumentException('El valor de la contraseña debe ser una cadena.');
        }

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

        return $user;
    }

    public static function create_default_teacher(): User
    {
        $password = config('userdefaults.default_teacher.password');
        if (!is_string($password)) {
            throw new InvalidArgumentException('El valor de la contraseña debe ser una cadena.');
        }

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

        return $teacher;
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

    public static function create_regular_user(): User
    {
        $user = User::create([
            'name' => 'Regular',
            'email' => 'regular@videosapp.com',
            'password' => Hash::make('123456789'),
        ]);

        self::add_personal_team($user);

        return $user;
    }

    public static function create_video_manager_user(): User
    {
        $user = User::create([
            'name' => 'Video Manager',
            'email' => 'videosmanager@videosapp.com',
            'password' => Hash::make('123456789'),
        ]);

        self::add_personal_team($user);

        return $user;
    }

    public static function create_superadmin_user(): User
    {
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@videosapp.com',
            'password' => Hash::make('123456789'),
        ]);

        self::add_personal_team($user);

        return $user;
    }
}
