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
        try {
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

            // Fire the video created event
            event(new VideoCreated($newVideo));

            // Update previous_id and next_id
            $previousVideo = Video::orderBy('id', 'desc')->skip(1)->first();
            if ($previousVideo) {
                $newVideo->update(['previous_id' => $previousVideo->id]);
                $previousVideo->update(['next_id' => $newVideo->id]);
            }

            $message = "S’ha creat el vídeo “{$newVideo->title}”!";

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                    'data' => $newVideo,
                    'status' => 'success',
                ], 201);
            }

            // Flash the message to the session for display on index
            return redirect()->route('videos.index')->with('success', $message);
        } catch (\Exception $e) {
            $errorMessage = "Error al crear el vídeo: {$e->getMessage()}";

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $errorMessage,
                    'status' => 'error',
                ], 500);
            }

            // Flash the error message to the session for display on index
            return redirect()->route('videos.index')->with('error', $errorMessage);
        }
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

        $series = Serie::all();
        return view('videos.edit', compact('video', 'series'));
    }

    /**
     * Update the specified video in storage.
     */
    public function update(Request $request, int $id)
    {
        try {
            $video = Video::findOrFail($id);

            // Verify user permissions
            if ($video->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
                throw new \Exception('No tens permís per actualitzar aquest vídeo.');
            }

            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'url' => 'required|string|unique:videos,url,'.$id,
                'serie_id' => 'nullable|exists:series,id',
            ]);

            $video->update($request->all());
            $message = "S’ha actualitzat el vídeo “{$video->title}”!";

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                    'data' => $video,
                    'status' => 'success',
                ], 200);
            }

            // Flash the message to the session for display on index
            return redirect()->route('videos.index')->with('success', $message);
        } catch (\Exception $e) {
            $errorMessage = "Error al actualitzar el vídeo: {$e->getMessage()}";

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $errorMessage,
                    'status' => 'error',
                ], 500);
            }

            // Flash the error message to the session for display on index
            return redirect()->route('videos.index')->with('error', $errorMessage);
        }
    }

    /**
     * Show the confirmation page for deleting a video.
     */
    public function delete(int $id): View
    {
        $video = Video::findOrFail($id);

        return view('videos.delete', compact('video'));
    }

    /**
     * Permanently delete the specified video.
     */
    public function destroy(int $id, Request $request)
    {
        try {
            $video = Video::findOrFail($id);

            // Verify user permissions
            if ($video->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
                throw new \Exception('No tens permís per eliminar aquest vídeo.');
            }

            $title = $video->title;
            $video->forceDelete();
            $message = "S’ha eliminat el vídeo “{$title}”!";

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                    'status' => 'success',
                ], 200);
            }

            // Flash the message to the session for display on index
            return redirect()->route('videos.index')->with('success', $message);
        } catch (\Exception $e) {
            $errorMessage = "Error al eliminar el vídeo: {$e->getMessage()}";

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $errorMessage,
                    'status' => 'error',
                ], 500);
            }

            // Flash the error message to the session for display on index
            return redirect()->route('videos.index')->with('error', $errorMessage);
        }
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
