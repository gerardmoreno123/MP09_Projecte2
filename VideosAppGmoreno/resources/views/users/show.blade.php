@extends('layouts.videos-app-layout')

@section('content')
    <div class="max-w-4xl mx-auto p-8 bg-gray-900 shadow-2xl rounded-lg text-center transform shadow-green-500">
        <!-- Foto de perfil del usuario -->
        <div class="mb-6 flex justify-center">
            <img src="{{ $user->profile_photo_url }}" alt="Foto de {{ $user->name }}" class="w-40 h-40 object-cover rounded-full border-4 border-green-500 shadow-lg">
        </div>

        <!-- Nombre del usuario -->
        <h2 class="text-4xl font-bold text-green-400 mb-2">{{ $user->name }}</h2>

        <!-- Email del usuario -->
        <p class="text-gray-300 text-lg mb-6">{{ $user->email }}</p>

        <!-- Información adicional -->
        <div class="bg-gray-800 p-4 rounded-lg shadow-md mb-6">
            <p class="text-gray-400"><span class="font-semibold text-white">Miembro desde:</span> {{ $user->created_at->format('d/m/Y') }}</p>
        </div>

        <!-- Videos publicados por el usuario -->
        @if($user->videos->isNotEmpty())
            <h3 class="text-2xl font-bold text-green-400 mb-6">Videos Publicados</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($user->videos as $video)
                    <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-transform transform hover:scale-105">
                        <a href="{{ route('videos.show', $video->id) }}" class="block">
                            <!-- Vista previa del video -->
                            <div class="relative">
                                @php
                                    preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $video->url, $matches);
                                    $videoId = $matches[1] ?? null;
                                @endphp

                                @if($videoId)
                                    <span class="text-white text-4xl">▶</span>
                            </div>
                            @else
                                <img src="default-thumbnail.jpg" alt="Default Thumbnail" class="w-full h-40 object-cover">
                        @endif
                    </div>

                    <!-- Título y descripción -->
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-green-400 hover:text-orange-400 transition-colors mb-1">{{ $video->title }}</h3>
                        <p class="text-gray-500 text-xs"><strong>Publicat el:</strong> {{ $video->formatted_published_at }}</p>
                    </div>
                    </a>
            </div>
            @endforeach
    </div>
    @else
        <p class="text-gray-400 text-lg">Este usuario no tiene videos publicados.</p>
    @endif

    <!-- Enlace al perfil del usuario -->
    <a href="{{ route('users.index') }}" class="inline-block px-6 py-2 text-lg font-medium text-white bg-orange-500 rounded-lg hover:bg-orange-600 transition duration-300 mt-6">
        Volver a la lista de usuarios
    </a>
    </div>
@endsection
