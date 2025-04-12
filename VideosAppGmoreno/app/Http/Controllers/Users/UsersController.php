<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        // Start building the query
        $query = User::query();

        // Handle search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        }

        // Load users with their videos count
        $users = $query->withCount('videos')->paginate(12);

        return view('users.index', compact('users'));
    }

    /**
     * Show the users.
     */
    public function show(Request $request, int $id): Factory|\Illuminate\Contracts\View\View|Application|JsonResponse
    {
        // Retornar videos i multimedia de l'usuari autenticat
        $user = User::with('videos', 'multimedia')->find($id);


        if (!$user) {
            return response()->json(['message' => 'User not authenticated or not found'], 401);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'data' => $user,
            ], 200);
        }

        return view('users.show', compact('user'));
    }
}
