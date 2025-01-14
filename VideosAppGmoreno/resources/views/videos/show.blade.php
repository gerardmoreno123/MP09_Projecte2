<!-- resources/views/videos/show.blade.php -->

@extends('layouts.videos-app-layout')  <!-- Usando el layout principal -->

@section('content')
    <div class="video-details">
        <h2>{{ $video->title }}</h2>
        <p>{{ $video->description }}</p>
        <p><strong>Publicat el:</strong> {{ $video->formatted_published_at }}</p> <!-- Usando el accesor del modelo -->
        <p><a href="{{ $video->url }}" target="_blank">Veure vídeo</a></p>
    </div>
@endsection
