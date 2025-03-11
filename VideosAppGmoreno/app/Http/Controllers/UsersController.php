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
    public function show(int $id): View|JsonResponse
    {
        $user = User::with('videos')->find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return view('users.show', compact('user' ));
    }
}
