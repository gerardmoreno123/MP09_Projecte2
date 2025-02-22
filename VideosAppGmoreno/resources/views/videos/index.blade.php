@extends('layouts.videos-app-layout')

@section('content')
    <div class="container mx-auto px-6 py-12">
        <!-- Título principal -->
        <h2 class="text-4xl font-bold text-green-400 mb-8 text-center">Todos los Videos</h2>

        <!-- Grilla de videos con separación entre tarjetas -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach($videos as $video)
                <div class="bg-gray-800 p-6 hover:bg-gray-700 transition-colors rounded-none">
                    <a href="{{ route('videos.show', $video->id) }}" class="block">
                        <!-- Vista previa del video -->
                        <div class="relative mb-4">
                            @php
                                preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $video->url, $matches);
                                $videoId = $matches[1] ?? null;
                            @endphp

                            @if($videoId)
                                <img src="https://img.youtube.com/vi/{{ $videoId }}/0.jpg" alt="Video Thumbnail" class="w-full h-48 object-cover">
                                <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                    <span class="text-white text-3xl">▶</span>
                                </div>
                            @else
                                <img src="default-thumbnail.jpg" alt="Default Thumbnail" class="w-full h-48 object-cover">
                            @endif
                        </div>

                        <!-- Título y descripción -->
                        <div class="px-4 pb-6">
                            <h3 class="text-xl font-semibold text-green-400 hover:text-orange-400 transition-colors mb-2">{{ $video->title }}</h3>
                            <p class="text-gray-300 text-sm mb-2">{{ $video->description }}</p>

                            <!-- Fecha de publicación -->
                            <p class="text-gray-500 text-xs">
                                <strong>Publicat el:</strong> {{ $video->formatted_published_at }}
                            </p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
