<?php

namespace App\Http\Controllers\Series;

use App\Http\Controllers\Controller;
use App\Models\Serie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Series\SeriesManageControllerTest;

class SeriesManageController extends Controller
{
    /**
     * Display a listing of the series.
     */
    public function index(Request $request)
    {
        $query = Serie::query();

        // Handle search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', '%' . $search . '%');
        }

        $series = $query->withCount('videos')->paginate(10);

        if ($request->expectsJson()) {
            return response()->json([
                'data' => $series,
            ], 200);
        }

        return view('series.manage.index', compact('series'));
    }

    /**
     * Show the form for creating a new series.
     */
    public function create()
    {
        return view('series.manage.create');
    }

    /**
     * Store a newly created series in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,gif',
                'user_name' => 'required|string|max:255',
                'user_photo_url' => 'nullable|url',
                'published_at' => 'nullable|date',
            ]);

            // Set the authenticated user's ID
            $data['user_id'] = Auth::id();

            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images/series', 'public');
                $data['image'] = $imagePath;
            }

            $serie = Serie::create($data);
            $message = "S’ha creat la sèrie “{$serie->title}”!";

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                    'status' => 'success',
                ], 201);
            }

            return redirect()->route('series.manage.index')->with('success', $message);
        } catch (\Exception $e) {
            $errorMessage = "Error al crear la sèrie: {$e->getMessage()}";

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $errorMessage,
                    'status' => 'error',
                ], 500);
            }

            return redirect()->route('series.manage.index')->with('error', $errorMessage);
        }
    }

    /**
     * Display the specified series.
     */
    public function show($id, Request $request)
    {
        $serie = Serie::with('videos')->findOrFail($id);

        if ($request->expectsJson()) {
            return response()->json([
                'data' => $serie,
            ], 200);
        }

        return view('series.manage.show', compact('serie'));
    }

    /**
     * Show the form for editing the specified series.
     */
    public function edit($id, Request $request)
    {
        $serie = Serie::findOrFail($id);

        // Verify user permissions
        if ($serie->user_id !== Auth::id() && !Auth::user()->hasAnyRole(['super-admin', 'serie-manager'])) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'No tens permís per editar aquesta sèrie.',
                    'status' => 'error',
                ], 403);
            }
            return redirect()->route('series.manage.index')->with('error', 'No tens permís per editar aquesta sèrie.');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'data' => $serie,
            ], 200);
        }

        return view('series.manage.edit', compact('serie'));
    }

    /**
     * Update the specified series in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $serie = Serie::findOrFail($id);

            // Verify user permissions
            if ($serie->user_id !== Auth::id() && !Auth::user()->hasAnyRole(['super-admin', 'serie-manager'])) {
                throw new \Exception('No tens permís per actualitzar aquesta sèrie.');
            }

            $data = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,gif',
                'user_name' => 'required|string|max:255',
                'user_photo_url' => 'nullable|url',
                'published_at' => 'nullable|date',
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($serie->image) {
                    Storage::disk('public')->delete($serie->image);
                }
                $imagePath = $request->file('image')->store('images/series', 'public');
                $data['image'] = $imagePath;
            }

            $serie->update($data);
            $message = "S’ha actualitzat la sèrie “{$serie->title}”!";

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                    'status' => 'success',
                ], 200);
            }

            return redirect()->route('series.manage.index')->with('success', $message);
        } catch (\Exception $e) {
            $errorMessage = "Error al actualitzar la sèrie: {$e->getMessage()}";

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $errorMessage,
                    'status' => 'error',
                ], 500);
            }

            return redirect()->route('series.manage.index')->with('error', $errorMessage);
        }
    }

    /**
     * Show the confirmation page for deleting the specified series.
     */
    public function delete($id, Request $request)
    {
        $serie = Serie::findOrFail($id);

        // Verify user permissions
        if ($serie->user_id !== Auth::id() && !Auth::user()->hasAnyRole(['super-admin', 'serie-manager'])) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'No tens permís per eliminar aquesta sèrie.',
                    'status' => 'error',
                ], 403);
            }
            return redirect()->route('series.manage.index')->with('error', 'No tens permís per eliminar aquesta sèrie.');
        }

        return view('series.manage.delete', compact('serie'));
    }

    /**
     * Remove the specified series from storage.
     */
    public function destroy($id, Request $request)
    {
        try {
            $serie = Serie::findOrFail($id);

            // Verify user permissions
            if ($serie->user_id !== Auth::id() && !Auth::user()->hasAnyRole(['super-admin', 'serie-manager'])) {
                throw new \Exception('No tens permís per eliminar aquesta sèrie.');
            }

            // Detach videos from the series
            if ($serie->videos()->count() > 0) {
                $serie->videos()->update(['serie_id' => null]);
            }

            $title = $serie->title;
            // Delete image if exists
            if ($serie->image) {
                Storage::disk('public')->delete($serie->image);
            }
            $serie->delete();
            $message = "S’ha eliminat la sèrie “{$title}”!";

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                    'status' => 'success',
                ], 200);
            }

            return redirect()->route('series.manage.index')->with('success', $message);
        } catch (\Exception $e) {
            $errorMessage = "Error al eliminar la sèrie: {$e->getMessage()}";

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $errorMessage,
                    'status' => 'error',
                ], 500);
            }

            return redirect()->route('series.manage.index')->with('error', $errorMessage);
        }
    }

    /**
     * Return the name of the test class.
     */
    public function testedBy(): string
    {
        $tests = [];

        if (class_exists(SeriesManageControllerTest::class)) {
            $tests[] = SeriesManageControllerTest::class;
        }

        return !empty($tests) ? implode('<br>', $tests) : 'Unknown';
    }
}
