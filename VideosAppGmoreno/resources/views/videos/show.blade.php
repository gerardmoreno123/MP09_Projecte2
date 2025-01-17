@extends('layouts.videos-app-layout')

@section('content')
    <div class="bg-gray-50 video-details max-w-3xl mx-auto p-6 bg-gradient-to-r from-blue-50 via-blue-100 to-blue-200 shadow-xl rounded-lg">
        <!-- Título -->
        <h2 class="text-3xl font-semibold text-gray-800 mb-4 text-center">{{ $video->title }}</h2>

        <!-- Descripción -->
        <p class="text-gray-600 mb-4 text-lg">{{ $video->description }}</p>

        <!-- Fecha de publicación -->
        <p class="text-gray-500 mb-6 text-sm">
            <strong>Publicat el:</strong> {{ $video->formatted_published_at }}
        </p>

        <!-- Reproductor de vídeo -->
        <div class="video-player mb-6 rounded-xl overflow-hidden shadow-lg">
            <iframe
                width="100%"
                height="400"
                src="{{ $video->url }}"
                title="YouTube video player"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                referrerpolicy="strict-origin-when-cross-origin"
                allowfullscreen>
            </iframe>
        </div>

        <!-- Enlace para abrir en una nueva pestaña -->
        <div class="text-center">
            <a href="{{ $video->url }}" target="_blank" class="text-blue-600 hover:text-blue-800 font-medium text-lg transition-colors duration-300">
                Obrir vídeo en una nova pestanya
            </a>
        </div>
    </div>
@endsection
