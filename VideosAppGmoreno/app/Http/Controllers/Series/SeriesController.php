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
}
