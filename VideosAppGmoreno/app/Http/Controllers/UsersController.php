<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class UsersController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(): View
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the users.
     */
    public function show(): JsonResponse
    {
        // Retornar videos i multimedia de l'usuari autenticat
        $user = User::with('videos', 'multimedia')->find(auth()->id());


        if (!$user) {
            return response()->json(['message' => 'User not authenticated or not found'], 401);
        }

        return response()->json([
            'data' => $user,
        ], 200);
    }
}
