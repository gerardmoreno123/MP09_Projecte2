<?php

namespace App\Http\Controllers\Multimedia;

use App\Http\Controllers\Controller;
use App\Models\Multimedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MultimediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return View|JsonResponse
     */
    public function index(Request $request): View|JsonResponse
    {
        $multimedia = Multimedia::all();

        if ($request->expectsJson()) {
            return response()->json([
                'data' => $multimedia,
            ], 200);
        }

        /** @phpstan-ignore argument.type */
        return view('multimedia.index', compact('multimedia'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View|JsonResponse
     */
    public function show(int $id): View|JsonResponse
    {
        $multimedia_file = Multimedia::find($id);

        if (!$multimedia_file) {
            return response()->json(['message' => 'Multimedia file not found'], 404);
        }

        if (request()->expectsJson()) {
            return response()->json([
                'data' => $multimedia_file,
            ], 200);
        }

        /** @phpstan-ignore argument.type */
        return view('multimedia.show', compact('multimedia_file'));
    }
}
