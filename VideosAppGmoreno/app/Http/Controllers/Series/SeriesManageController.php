<?php

namespace App\Http\Controllers\Series;

use App\Http\Controllers\Controller;
use App\Models\Serie;
use Tests\Feature\Series\SeriesManageControllerTest;

class SeriesManageController extends Controller
{
    public function index()
    {
        $query = Serie::query();

        // Handle search
        if (request()->filled('search')) {
            $search = request()->input('search');
            $query->where('title', 'like', '%' . $search . '%');
        }

        $series = $query->withCount('videos')->paginate(10);

        return view('series.manage.index', compact('series'));
    }

    public function create()
    {
        return view('series.manage.create');
    }

    public function store()
    {
        // Lògica per guardar una nova sèrie
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

        return redirect()->route('series.manage.show', $serie)->with('success', 'Sèrie creada amb èxit.');
    }

    public function show($serie)
    {
        $serie = Serie::with('videos')->findOrFail($serie);

        return view('series.manage.show', compact('serie'));
    }

    public function edit($serie)
    {
        $serie = Serie::findOrFail($serie);

        return view('series.manage.edit', compact('serie'));
    }

    public function update($serie)
    {
        // Lògica per actualitzar una sèrie
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

        return redirect()->route('series.manage.show', $serie)->with('success', 'Sèrie actualitzada amb èxit.');
    }

    public function delete($serie)
    {
        // Lògica per mostrar la confirmació d'eliminació d'una sèrie
        $serie = Serie::findOrFail($serie);

        return view('series.manage.delete', compact('serie'));
    }

    public function destroy($serie)
    {
        // Lògica per eliminar una sèrie
        $serie = Serie::findOrFail($serie);
        // Comprovar si la sèrie té vídeos associats
        if ($serie->videos()->count() > 0) {
            $videos = $serie->videos()->get();
            foreach ($videos as $video) {
                $video->serie_id = null;
                $video->save();
            }
        }

        $serie->delete();


        return redirect()->route('series.manage.index')->with('success', 'Sèrie eliminada amb èxit.');
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
