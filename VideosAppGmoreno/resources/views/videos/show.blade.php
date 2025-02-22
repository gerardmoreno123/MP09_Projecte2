@extends('layouts.videos-app-layout')

@section('content')
    <div class="bg-gradient-to-r from-gray-800 via-gray-900 to-black max-w-3xl mx-auto p-6 shadow-xl rounded-lg">
        <!-- Título del video -->
        <h2 class="text-3xl font-semibold text-green-400 mb-4 text-center">{{ $video->title }}</h2>

        <!-- Descripción del video -->
        <p class="text-gray-300 mb-4 text-lg">{{ $video->description }}</p>

        <!-- Fecha de publicación -->
        <p class="text-gray-500 mb-6 text-sm">
            <strong>Publicat el:</strong> {{ $video->formatted_published_at }}
        </p>

        <!-- Reproductor de video -->
        <div class="video-player mb-6 rounded-xl overflow-hidden shadow-lg">
            @php
                $videoUrl = $video->url;
                if (strpos($videoUrl, '?') === false) {
                    $videoUrl .= '?autoplay=1';
                } else {
                    $videoUrl .= '&autoplay=1';
                }
            @endphp
            <iframe
                width="100%"
                height="400"
                src="{{ $videoUrl }}"
                title="YouTube video player"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                referrerpolicy="strict-origin-when-cross-origin"
                allowfullscreen>
            </iframe>
        </div>

        <!-- Enlace al video -->
        <div class="text-center">
            <a href="{{ $video->url }}" target="_blank" class="text-orange-400 hover:text-orange-600 font-medium text-lg transition-colors duration-300">
                Obrir vídeo en una nova pestanya
            </a>
        </div>
    </div>
@endsection
