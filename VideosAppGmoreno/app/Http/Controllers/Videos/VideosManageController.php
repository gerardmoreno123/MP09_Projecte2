<?php

namespace App\Http\Controllers\Videos;

use App\Http\Controllers\Controller;
use App\Models\Serie;
use App\Models\Video;
use Illuminate\Http\Request;
use Tests\Feature\Videos\VideosManageControllerTest;

class VideosManageController extends Controller
{
    /**
     * Display a listing of the videos.
     */
    public function index(Request $request)
    {
        $query = Video::query();

        // Handle search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', '%' . $search . '%');
        }

        // With user and serie
        $videos = $query->with(['user', 'serie'])->paginate(10);

        if ($request->expectsJson()) {
            return response()->json([
                'data' => $videos,
            ], 200);
        }

        return view('videos.manage.index', compact('videos'));
    }

    /**
     * Show the form for creating a new video.
     */
    public function create()
    {
        $series = Serie::All();
        return view('videos.manage.create', compact('series'));
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
            'serie_id' => 'nullable|exists:series,id',
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

        if ($request->expectsJson()) {
            return response()->json([
                'Video created successfully.',
            ], 201);
        }

        // Redirigir a la lista de vídeos con un mensaje de éxito
        return redirect()->route('videos.manage.index')->with('success', 'Video created successfully.');
    }

    /**
     * Display the specified video.
     */
    public function show($id, Request $request)
    {
        $video = Video::findOrFail($id);

        $video->user_id = $video->user->name;

        if ($request->expectsJson()) {
            return response()->json([
                'data' => $video,
            ], 200);
        }

        return view('videos.manage.show', compact('video'));
    }

    /**
     * Show the form for editing the specified video.
     */
    public function edit($id, Request $request)
    {
        $video = Video::findOrFail($id);
        $series = Serie::All();

        if ($request->expectsJson()) {
            return response()->json([
                'data' => $video,
            ], 200);
        }

        return view('videos.manage.edit', compact('video', 'series'));
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
    public function destroy($id, Request $request)
    {
        $video = Video::findOrFail($id);
        $video->forceDelete();

        if ($request->expectsJson()) {
            return response()->json([
                'Video permanetly deleted.',
            ], 201);
        }

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
