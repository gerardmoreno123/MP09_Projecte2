<?php

namespace App\Http\Controllers\Videos;

use App\Events\VideoCreated;
use App\Http\Controllers\Controller;
use App\Models\Serie;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $series = Serie::all();
        return view('videos.manage.create', compact('series'));
    }

    /**
     * Store a newly created video in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate form data
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'url' => 'required|string|unique:videos,url',
                'serie_id' => 'nullable|exists:series,id',
            ]);

            // Prepare video data
            $videoData = $request->all();
            $videoData['published_at'] = now();
            $videoData['previous_id'] = null;
            $videoData['next_id'] = null;
            $videoData['user_id'] = Auth::id();

            // Create the new video
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
                    'status' => 'success',
                ], 201);
            }

            // Flash success message and redirect to index
            return redirect()->route('videos.manage.index')->with('success', $message);
        } catch (\Exception $e) {
            $errorMessage = "Error al crear el vídeo: {$e->getMessage()}";

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $errorMessage,
                    'status' => 'error',
                ], 500);
            }

            // Flash error message and redirect to index
            return redirect()->route('videos.manage.index')->with('error', $errorMessage);
        }
    }

    /**
     * Display the specified video.
     */
    public function show($id, Request $request)
    {
        $video = Video::with(['user', 'serie'])->findOrFail($id);

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

        // Verify user permissions
        if ($video->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'No tens permís per editar aquest vídeo.',
                    'status' => 'error',
                ], 403);
            }
            return redirect()->route('videos.manage.index')->with('error', 'No tens permís per editar aquest vídeo.');
        }

        $series = Serie::all();

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
        try {
            $video = Video::findOrFail($id);

            // Verify user permissions
            if ($video->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
                throw new \Exception('No tens permís per actualitzar aquest vídeo.');
            }

            // Validate form data
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'url' => 'required|string|unique:videos,url,' . $id,
                'serie_id' => 'nullable|exists:series,id',
            ]);

            $video->update($request->all());
            $message = "S’ha actualitzat el vídeo “{$video->title}”!";

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                    'status' => 'success',
                ], 200);
            }

            // Flash success message and redirect to index
            return redirect()->route('videos.manage.index')->with('success', $message);
        } catch (\Exception $e) {
            $errorMessage = "Error al actualitzar el vídeo: {$e->getMessage()}";

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $errorMessage,
                    'status' => 'error',
                ], 500);
            }

            // Flash error message and redirect to index
            return redirect()->route('videos.manage.index')->with('error', $errorMessage);
        }
    }

    /**
     * Show the confirmation page for deleting the specified video.
     */
    public function delete($id)
    {
        $video = Video::findOrFail($id);

        // Verify user permissions
        if ($video->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            return redirect()->route('videos.manage.index')->with('error', 'No tens permís per eliminar aquest vídeo.');
        }

        return view('videos.manage.delete', compact('video'));
    }

    /**
     * Permanently delete the specified video from storage.
     */
    public function destroy($id, Request $request)
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

            // Flash success message and redirect to index
            return redirect()->route('videos.manage.index')->with('success', $message);
        } catch (\Exception $e) {
            $errorMessage = "Error al eliminar el vídeo: {$e->getMessage()}";

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $errorMessage,
                    'status' => 'error',
                ], 500);
            }

            // Flash error message and redirect to index
            return redirect()->route('videos.manage.index')->with('error', $errorMessage);
        }
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
