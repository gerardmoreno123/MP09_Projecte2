<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;

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

        $team = Team::forceCreate([
            'user_id' => $user->id,
            'team_name' => explode(' ', $user->name, 2)[0] . "'s Team",
            'personal_team' => true,
        ]);

        $user->current_team_id = $team->id;
        $user->save();

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
        ]);

        $team = Team::forceCreate([
            'user_id' => $teacher->id,
            'team_name' => explode(' ', $teacher->name, 2)[0] . "'s Team",
            'personal_team' => true,
        ]);

        $teacher->current_team_id = $team->id;
        $teacher->save();

        return $teacher;
    }
}
