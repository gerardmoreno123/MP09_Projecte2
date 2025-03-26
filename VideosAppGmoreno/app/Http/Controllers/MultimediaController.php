<?php

namespace App\Http\Controllers;

use App\Models\Multimedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Video;
use Tests\Unit\VideosTest as UnitVideosTest;
use Tests\Feature\Videos\VideosTest as FeatureVideosTest;

class MultimediaController extends Controller
{
    public function index(Request $request)
    {
        $multimedia = Multimedia::all();

        if ($request->expectsJson()) {
            return response()->json([
                'data' => $multimedia,
            ], 200);
        }

        return view('multimedia.index', compact('multimedia'));
    }

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

        return view('multimedia.show', compact('multimedia_file'));
    }
}
