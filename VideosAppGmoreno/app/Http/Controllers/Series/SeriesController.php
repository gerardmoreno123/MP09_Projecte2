<?php

namespace App\Http\Controllers\Series;

use App\Http\Controllers\Controller;
use App\Models\Serie;
use Illuminate\Http\Request;

class SeriesController extends Controller
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
            $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        }

        $series = $query->withCount('videos')->paginate(12);

        return view('series.index', compact('series'));
    }

    /**
     * Show the series.
     */
    public function show(int $id)
    {
        $serie = Serie::with('videos')->find($id);

        return view('series.show', compact('serie'));
    }

    /**
     * Show the form for creating a new series.
     */
    public function create()
    {
        return view('series.create');
    }

    /**
     * Store a newly created series in storage.
     */
    public function store(Request $request)
    {
        $data = request()->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,gif',
            'user_name' => 'required|string|max:255',
            'user_photo_url' => 'nullable|url',
            'published_at' => 'nullable|date',
        ]);

        // Validar la imatge i guardar-la
        if (request()->hasFile('image')) {
            $image = request()->file('image');
            $imagePath = $image->store('images/series', 'public');
            $data['image'] = $imagePath;
        }

        $serie = Serie::create($data);

        return redirect()->route('series.show', $serie)->with('success', 'Sèrie creada amb èxit.');
    }

    /**
     * Show the form for editing the specified series.
     */
    public function edit(int $id)
    {
        $serie = Serie::find($id);

        return view('series.edit', compact('serie'));
    }

    /**
     * Update the specified series in storage.
     */
    public function update($serie)
    {
        $serie = Serie::findOrFail($serie);

        $data = request()->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,gif',
            'user_name' => 'required|string|max:255',
            'user_photo_url' => 'nullable|url',
            'published_at' => 'nullable|date',
        ]);

        // Validar la imatge i guardar-la
        if (request()->hasFile('image')) {
            $image = request()->file('image');
            // Eliminar la imatge anterior si existeix
            if ($serie->image) {
                $oldImagePath = public_path('storage/' . $serie->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Guardar la nova imatge
            $imagePath = $image->store('images/series', 'public');
            $data['image'] = $imagePath;
        }

        $serie->update($data);

        return redirect()->route('series.show', $serie)->with('success', 'Sèrie actualitzada amb èxit.');


    }

    /**
     * Show the confirmation page for deleting the specified series.
     */
    public function delete($serie)
    {
        $serie = Serie::findOrFail($serie);

        return view('series.delete', compact('serie'));
    }

    /**
     * Remove the specified series from storage.
     */
    public function destroy($serie)
    {
        $serie = Serie::findOrFail($serie);

        // Eliminar la imatge anterior si existeix
        if ($serie->image) {
            $oldImagePath = public_path('storage/' . $serie->image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        $serie->delete();

        return redirect()->route('series.index')->with('success', 'Sèrie eliminada amb èxit.');
    }
}
