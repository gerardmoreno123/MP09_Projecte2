<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Models\Video;

class VideosController extends Controller
{
    /**
     * Muestra un video por su ID.
     *
     * @param int $id
     * @return View|JsonResponse
     */
    public function show(int $id): View|JsonResponse
    {
        $video = Video::find($id);

        if (!$video) {
            return response()->json(['message' => 'Video not found'], 404);
        }

        return view('videos.show', compact('video'));
    }

    /**
     * Devuelve los testers asociados a un video.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function testedBy(int $id): JsonResponse
    {
        $video = Video::find($id);

        if (!$video) {
            return response()->json(['message' => 'Video not found'], 404);
        }

        if (!property_exists($video, 'testers')) {
            return response()->json(['message' => 'Testers not found for this video'], 404);
        }

        return response()->json($video->testers);
    }
}
