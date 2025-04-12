<?php

namespace App\Http\Controllers\Multimedia;

use App\Http\Controllers\Controller;
use App\Models\Multimedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApiMultimediaController extends Controller
{
    public function index()
    {
        $multimedia = Multimedia::where('user_id', auth()->id())->get();
        return response()->json(['data' => $multimedia]);
    }

    public function show($id)
    {
        $multimedia = Multimedia::with('user')->findOrFail($id);

        return response()->json([
            'data' => [
                'multimedia' => $multimedia,
                'user_name' => $multimedia->user ? $multimedia->user->name : null,
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:mp4,mpeg,avi,mov,webm,mkv,jpeg,png,jpg|max:1024400', // 1GB
        ]);

        $file = $request->file('file');
        $fileType = in_array($file->getClientOriginalExtension(), ['mp4', 'mpeg', 'avi', 'mov', 'webm', 'mkv']) ? 'video' : 'image';
        $filePath = $file->store('multimedia', 'public');

        $multimedia = Multimedia::create([
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'user_id' => auth()->id(),
            'published_at' => now(),
        ]);

        return response()->json(['message' => 'Arxiu multimèdia creat amb èxit.', 'data' => $multimedia], 201);
    }

    public function edit($id)
    {
        $multimedia = Multimedia::findOrFail($id);
        return response()->json(['data' => $multimedia]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:mp4,mpeg,avi,mov,webm,mkv,jpeg,png,jpg|max:1024400',
        ]);

        $multimedia = Multimedia::findOrFail($id);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
        ];

        if ($request->hasFile('file')) {
            if ($multimedia->file_path) {
                Storage::disk('public')->delete($multimedia->file_path);
            }
            $file = $request->file('file');
            $fileType = in_array($file->getClientOriginalExtension(), ['mp4', 'mpeg', 'avi', 'mov', 'webm', 'mkv']) ? 'video' : 'image';
            $filePath = $file->store('multimedia', 'public');
            $data['file_path'] = $filePath;
            $data['file_type'] = $fileType;
        }

        $multimedia->update($data);

        return response()->json(['message' => 'Multimèdia actualitzat amb èxit', 'data' => $multimedia]);
    }

    public function destroy($id, Request $request)
    {
        $video = Multimedia::findOrFail($id);
        $video->forceDelete();

        if ($request->expectsJson()) {
            return response()->json([
                'Video permanetly deleted.',
            ], 201);
        }

        return response()->json(['message' => 'Arxiu multimèdia eliminat permanentment.']);
    }
}
