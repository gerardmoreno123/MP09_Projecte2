<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Tests\Feature\Users\UsersManageControllerTest;

class UsersManageController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(): View
    {
        $users = User::all();
        return view('users.manage.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        $roles = Role::all();
        return view('users.manage.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8',
            'profile_photo' => 'nullable|image',
            'roles' => 'required|array',
        ]);

        $userData = $request->all();
        $userData['password'] = bcrypt($userData['password']);

        if ($request->hasFile('profile_photo')) {
            $userData['profile_photo'] = $request->file('profile_photo')->store('profile-photos');
        }

        if (isset($userData['roles'])) {
            if (is_string($userData['roles'])) {
                $userData['roles'] = explode(',', $userData['roles']);
            } elseif (is_array($userData['roles']) && count($userData['roles']) === 1 && str_contains($userData['roles'][0], ',')) {
                $userData['roles'] = explode(',', $userData['roles'][0]);
            }
        }

        $newUser = User::create($userData);
        $newUser->syncRoles($userData['roles']);

        return redirect()->route('users.manage.index')->with('success', 'User created successfully.');
    }

    /**
     * Edit the specified user.
     */
    public function edit(int $id): View
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('users.manage.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'profile_photo' => 'nullable|image',
            'roles' => 'required|array',
        ]);

        $userData = $request->all();

        if ($request->has('password')) {
            $userData['password'] = bcrypt($userData['password']);
        } else {
            unset($userData['password']);
        }

        $user = User::findOrFail($id);
        $user->update($userData);

        if ($request->hasFile('profile_photo')) {
            $user->profile_photo_path = $request->file('profile_photo')->store('profile-photos');
            $user->save();
        }

        if (isset($userData['roles'])) {
            if (is_string($userData['roles'])) {
                $userData['roles'] = explode(',', $userData['roles']);
            } elseif (is_array($userData['roles']) && count($userData['roles']) === 1 && str_contains($userData['roles'][0], ',')) {
                $userData['roles'] = explode(',', $userData['roles'][0]);
            }
        }

        $user->syncRoles($userData['roles']);

        return redirect()->route('users.manage.index')->with('success', 'User updated successfully.');
    }

    /**
     * Soft delete the specified user from storage.
     */
    public function delete(int $id): View
    {
        $user = User::findOrFail($id);
        return view('users.manage.delete', compact('user'));
    }

    /**
     * Permanently delete the specified user from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->forceDelete();

        return redirect()->route('users.manage.index')->with('success', 'User permanently deleted.');
    }

    public function testedBy(): string
    {
        $tests = [];

        if (class_exists(UsersManageControllerTest::class)) {
            $tests[] = UsersManageControllerTest::class;
        }

        return !empty($tests) ? implode('<br>', $tests) : 'Unknown';
    }
}
