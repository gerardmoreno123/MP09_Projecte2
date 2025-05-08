<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Tests\Feature\Users\UsersManageControllerTest;

class UsersManageController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request): View
    {
        // Verify user permissions
        if (!Auth::user()->hasAnyRole(['user-manager', 'super-admin'])) {
            abort(403, 'No tens permís per gestionar usuaris.');
        }

        $query = User::query();

        // Handle search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $users = $query->withCount('videos')->paginate(10);

        if ($request->expectsJson()) {
            return response()->json([
                'data' => $users,
            ], 200);
        }

        return view('users.manage.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        // Verify user permissions
        if (!Auth::user()->hasAnyRole(['user-manager', 'super-admin'])) {
            abort(403, 'No tens permís per crear usuaris.');
        }

        $roles = Role::all();
        return view('users.manage.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            // Verify user permissions
            if (!Auth::user()->hasAnyRole(['user-manager', 'super-admin'])) {
                throw new \Exception('No tens permís per crear usuaris.');
            }

            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|min:8',
                'profile_photo' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,gif',
                'roles' => 'required|array',
                'roles.*' => 'exists:roles,name',
            ]);

            // Hash password
            $data['password'] = bcrypt($data['password']);

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                $data['profile_photo_path'] = $request->file('profile_photo')->store('profile-photos', 'public');
            }

            // Process roles
            $roles = $data['roles'];
            unset($data['roles']);

            // Create user
            $newUser = User::create($data);

            // Create personal team
            Team::forceCreate([
                'user_id' => $newUser->id,
                'team_name' => $newUser->name . "'s Team",
                'personal_team' => true,
            ]);

            // Assign roles
            $newUser->syncRoles($roles);

            $message = "S’ha creat l’usuari “{$newUser->name}”!";

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                    'status' => 'success',
                ], 201);
            }

            return redirect()->route('users.manage.index')->with('success', $message);
        } catch (\Exception $e) {
            $errorMessage = "Error al crear l’usuari: {$e->getMessage()}";

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $errorMessage,
                    'status' => 'error',
                ], 500);
            }

            return redirect()->route('users.manage.index')->with('error', $errorMessage);
        }
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(int $id, Request $request): View
    {
        // Verify user permissions
        if (!Auth::user()->hasAnyRole(['user-manager', 'super-admin'])) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'No tens permís per editar usuaris.',
                    'status' => 'error',
                ], 403);
            }
            abort(403, 'No tens permís per editar usuaris.');
        }

        $user = User::findOrFail($id);
        $roles = Role::all();

        if ($request->expectsJson()) {
            return response()->json([
                'data' => $user,
                'roles' => $roles,
            ], 200);
        }

        return view('users.manage.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        try {
            // Verify user permissions
            if (!Auth::user()->hasAnyRole(['user-manager', 'super-admin'])) {
                throw new \Exception('No tens permís per actualitzar usuaris.');
            }

            $user = User::findOrFail($id);

            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users,email,' . $id,
                'password' => 'nullable|string|min:8',
                'profile_photo' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,gif',
                'roles' => 'required|array',
                'roles.*' => 'exists:roles,name',
            ]);

            // Handle password
            if ($request->has('password') && !empty($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            } else {
                unset($data['password']);
            }

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($user->profile_photo_path) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }
                $data['profile_photo_path'] = $request->file('profile_photo')->store('profile-photos', 'public');
            }

            // Process roles
            $roles = $data['roles'];
            unset($data['roles']);

            // Update user
            $user->update($data);

            // Sync roles
            $user->syncRoles($roles);

            $message = "S’ha actualitzat l’usuari “{$user->name}”!";

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                    'status' => 'success',
                ], 200);
            }

            return redirect()->route('users.manage.index')->with('success', $message);
        } catch (\Exception $e) {
            $errorMessage = "Error al actualitzar l’usuari: {$e->getMessage()}";

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $errorMessage,
                    'status' => 'error',
                ], 500);
            }

            return redirect()->route('users.manage.index')->with('error', $errorMessage);
        }
    }

    /**
     * Show the confirmation page for deleting the specified user.
     */
    public function delete(int $id, Request $request): View
    {
        // Verify user permissions
        if (!Auth::user()->hasAnyRole(['user-manager', 'super-admin'])) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'No tens permís per eliminar usuaris.',
                    'status' => 'error',
                ], 403);
            }
            abort(403, 'No tens permís per eliminar usuaris.');
        }

        $user = User::findOrFail($id);

        // Prevent self-deletion
        if ($user->id === Auth::id()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'No pots eliminar el teu propi compte.',
                    'status' => 'error',
                ], 403);
            }
            return redirect()->route('users.manage.index')->with('error', 'No pots eliminar el teu propi compte.');
        }

        return view('users.manage.delete', compact('user'));
    }

    /**
     * Permanently delete the specified user from storage.
     */
    public function destroy(int $id, Request $request): RedirectResponse
    {
        try {
            // Verify user permissions
            if (!Auth::user()->hasAnyRole(['user-manager', 'super-admin'])) {
                throw new \Exception('No tens permís per eliminar usuaris.');
            }

            $user = User::findOrFail($id);

            // Prevent self-deletion
            if ($user->id === Auth::id()) {
                throw new \Exception('No pots eliminar el teu propi compte.');
            }

            // Delete associated videos
            if ($user->videos()->count() > 0) {
                $user->videos()->delete();
            }

            // Delete profile photo if exists
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $name = $user->name;
            $user->delete();
            $message = "S’ha eliminat l’usuari “{$name}”!";

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                    'status' => 'success',
                ], 200);
            }

            return redirect()->route('users.manage.index')->with('success', $message);
        } catch (\Exception $e) {
            $errorMessage = "Error al eliminar l’usuari: {$e->getMessage()}";

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $errorMessage,
                    'status' => 'error',
                ], 500);
            }

            return redirect()->route('users.manage.index')->with('error', $errorMessage);
        }
    }

    /**
     * Return the name of the test class.
     */
    public function testedBy(): string
    {
        $tests = [];

        if (class_exists(UsersManageControllerTest::class)) {
            $tests[] = UsersManageControllerTest::class;
        }

        return !empty($tests) ? implode('<br>', $tests) : 'Unknown';
    }
}
