<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Models\Video;
use Tests\Unit\VideosTest as UnitVideosTest;
use Tests\Feature\Videos\VideosTest as FeatureVideosTest;

class VideosController extends Controller
{
    /**
     * Display a listing of the videos.
     */
    public function index()
    {
        $videos = Video::all();
        return view('videos.index', compact('videos'));
    }

    /**
     * Mostra un video per la seva ID.
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

        // Pasar la información del test junto con el video a la vista
        return view('videos.show', compact('video' ));
    }

    /**
     * Retorna el nombre de la clase del test.
     * @return string
     */
    public function testedBy(): string
    {
        $tests = [];

        if (class_exists(UnitVideosTest::class)) {
            $tests[] = UnitVideosTest::class;
        }

        if (class_exists(FeatureVideosTest::class)) {
            $tests[] = FeatureVideosTest::class;
        }

        return !empty($tests) ? implode('<br>', $tests) : 'Desconegut';
    }
}
