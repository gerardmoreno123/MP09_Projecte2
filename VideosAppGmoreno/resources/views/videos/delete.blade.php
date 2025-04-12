@extends('layouts.videos-app-layout')

@section('content')
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold text-white mb-8 flex items-center">
                <i class="fas fa-trash-alt text-red-500 mr-3"></i>
                Eliminar Video
            </h1>

            <div class="bg-slate-800 rounded-xl shadow-lg p-6">
                <p class="text-lg text-slate-300 mb-6">
                    ¿Estás seguro de que quieres eliminar el video <span class="font-semibold text-white">"{{ $video->title }}"</span>?
                    Esta acción no se puede deshacer.
                </p>

                <form action="{{ route('videos.destroy', $video->id) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="flex justify-end gap-4">
                        <a href="{{ route('videos.index') }}"
                           class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg">Cancelar</a>
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">Eliminar Video</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
