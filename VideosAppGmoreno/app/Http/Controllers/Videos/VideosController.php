<?php

namespace App\Http\Controllers\Videos;

use App\Events\VideoCreated;
use App\Http\Controllers\Controller;
use App\Models\Serie;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Tests\Feature\Videos\VideosTest as FeatureVideosTest;
use Tests\Unit\VideosTest as UnitVideosTest;

class VideosController extends Controller
{
    /**
     * Display a listing of the videos.
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = Video::query();

        // Handle search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', '%'.$search.'%')
                ->orWhere('description', 'like', '%'.$search.'%');
        }

        $videos = $query->with(['user', 'serie'])->paginate(12);

        if ($request->expectsJson()) {
            return response()->json([
                'data' => $videos,
                'pagination' => [
                    'total' => $videos->total(),
                    'current_page' => $videos->currentPage(),
                    'last_page' => $videos->lastPage(),
                    'per_page' => $videos->perPage(),
                ],
            ], 200);
        }

        return view('videos.index', compact('videos'));
    }

    /**
     * Show the form for creating a new video.
     */
    public function create(): View
    {
        $series = Serie::all();
        return view('videos.create', compact('series'));
    }

    /**
     * Store a newly created video in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'url' => 'required|string|unique:videos,url',
            'serie_id' => 'nullable|exists:series,id',
        ]);

        $videoData = $request->all();
        $videoData['published_at'] = now();
        $videoData['previous_id'] = null;
        $videoData['next_id'] = null;
        $videoData['user_id'] = Auth::id();

        $newVideo = Video::create($videoData);

        // Disparar el evento de creación de vídeo
        event(new VideoCreated($newVideo));

        // Actualizar previous_id y next_id
        $previousVideo = Video::orderBy('id', 'desc')->skip(1)->first();
        if ($previousVideo) {
            $newVideo->update(['previous_id' => $previousVideo->id]);
            $previousVideo->update(['next_id' => $newVideo->id]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Video created successfully.',
                'data' => $newVideo,
            ], 201);
        }

        return redirect()->route('videos.index')->with('success', '¡Video creado con éxito!');
    }

    /**
     * Display the specified video.
     */
    public function show(int $id): View|JsonResponse
    {
        $video = Video::with(['user', 'serie'])->findOrFail($id);

        if (request()->expectsJson()) {
            return response()->json([
                'data' => $video,
            ], 200);
        }

        return view('videos.show', compact('video'));
    }

    /**
     * Show the form for editing the specified video.
     */
    public function edit(int $id): View
    {
        $video = Video::findOrFail($id);

        // Verificar que el usuario sea el propietario o administrador
        if ($video->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para editar este video.');
        }

        $series = Serie::all();
        return view('videos.edit', compact('video', 'series'));
    }

    /**
     * Update the specified video in storage.
     */
    public function update(Request $request, int $id)
    {
        $video = Video::findOrFail($id);

        // Verificar que el usuario sea el propietario o administrador
        if ($video->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para actualizar este video.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'url' => 'required|string|unique:videos,url,'.$id,
            'serie_id' => 'nullable|exists:series,id',
        ]);

        $video->update($request->all());

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Video updated successfully.',
                'data' => $video,
            ], 200);
        }

        return redirect()->route('videos.index')->with('success', '¡Video actualizado con éxito!');
    }

    /**
     * Show the confirmation page for deleting a video.
     */
    public function delete(int $id): View
    {
        $video = Video::findOrFail($id);

        // Verificar que el usuario sea el propietario o administrador
        if ($video->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para eliminar este video.');
        }

        return view('videos.delete', compact('video'));
    }

    /**
     * Permanently delete the specified video.
     */
    public function destroy(int $id, Request $request)
    {
        $video = Video::findOrFail($id);

        // Verificar que el usuario sea el propietario o administrador
        if ($video->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para eliminar este video.');
        }

        $video->forceDelete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Video permanently deleted.',
            ], 204);
        }

        return redirect()->route('videos.index')->with('success', '¡Video eliminado con éxito!');
    }

    /**
     * Return the name of the test classes.
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
