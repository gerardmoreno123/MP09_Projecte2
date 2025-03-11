<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use Tests\Feature\Videos\VideosManageControllerTest;

class VideosManageController extends Controller
{
    /**
     * Display a listing of the videos.
     */
    public function index()
    {
        $videos = Video::all();
        return view('videos.manage.index', compact('videos'));
    }

    /**
     * Show the form for creating a new video.
     */
    public function create()
    {
        return view('videos.manage.create');
    }

    /**
     * Store a newly created video in storage.
     */
    public function store(Request $request)
    {
        // Validación de los datos del formulario
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'url' => 'required|string|unique:videos,url',
        ]);

        // Obtener todos los datos del request
        $videoData = $request->all();
        $videoData['published_at'] = now();
        $videoData['previous_id'] = null;
        $videoData['next_id'] = null;
        $videoData['user_id'] = auth()->id();

        // Crear el nuevo vídeo
        $newVideo = Video::create($videoData);

        // Obtener el último vídeo creado (el que estaría justo antes del nuevo)
        $previousVideo = Video::orderBy('id', 'desc')->skip(1)->first();

        // Si hay un vídeo anterior, actualizar el previous_id del nuevo vídeo y el next_id del anterior
        if ($previousVideo) {
            $newVideo->update(['previous_id' => $previousVideo->id]);
            $previousVideo->update(['next_id' => $newVideo->id]);
        }

        // Redirigir a la lista de vídeos con un mensaje de éxito
        return redirect()->route('videos.manage.index')->with('success', 'Video created successfully.');
    }

    /**
     * Display the specified video.
     */
    public function show($id)
    {
        $video = Video::findOrFail($id);
        return view('videos.manage.show', compact('video'));
    }

    /**
     * Show the form for editing the specified video.
     */
    public function edit($id)
    {
        $video = Video::findOrFail($id);
        return view('videos.manage.edit', compact('video'));
    }

    /**
     * Update the specified video in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $video = Video::findOrFail($id);
        $video->update($request->all());

        return redirect()->route('videos.manage.index')->with('success', 'Video updated successfully.');
    }

    /**
     * Soft delete the specified video from storage.
     */
    // Funció per mostrar la pàgina de confirmació d'eliminació
    public function delete($id)
    {
        $video = Video::findOrFail($id);

        // Mostrar la pàgina de confirmació d'eliminació (per exemple, una vista amb el vídeo a eliminar)
        return view('videos.manage.delete', compact('video'));
    }

    /**
     * Eliminar el vídeo de manera permanent.
     */
    public function destroy($id)
    {
        $video = Video::findOrFail($id);
        $video->forceDelete();

        return redirect()->route('videos.manage.index')->with('success', 'Video permanently deleted.');
    }

    /**
     * Return the name of the test class.
     */
    public function testedBy(): string
    {
        $tests = [];

        if (class_exists(VideosManageControllerTest::class)) {
            $tests[] = VideosManageControllerTest::class;
        }

        return !empty($tests) ? implode('<br>', $tests) : 'Unknown';
    }
}
