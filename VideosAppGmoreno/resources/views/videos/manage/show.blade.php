@extends('layouts.videos-app-layout')

@section('content')
    <div class="container mx-auto px-4 text-center">
        <!-- Tabs -->
        <div class="flex justify-center space-x-6 pb-2 mb-6">
            <a href="{{ route('videos.manage.index') }}" class="text-lg font-semibold text-gray-400 pb-2 inline-block text-center w-full hover:text-green-400 hover:border-green-400 hover:border-b-2 transition duration-200">Llista de Vídeos</a>
            <a href="{{ route('videos.manage.create') }}" class="text-lg font-semibold text-gray-400 pb-2 inline-block text-center w-full hover:text-green-400 hover:border-green-400 hover:border-b-2 transition duration-200">Afegir Vídeo</a>
            <a href="{{ route('videos.manage.show', $video->id) }}" class="text-lg font-semibold text-green-400 border-b-2 pb-2 inline-block text-center w-full">Veure dades del vídeo</a>
        </div>

        <!-- Detalls del Video -->
        <div class="flex justify-center space-x-6 pb-2 mb-6">
            <div class="flex flex-col sm:flex-row w-full gap-2">
                <!-- Informació del Video en una Card -->
                <div class="text-left w-full sm:w-1/2 bg-gray-800 p-6 rounded-lg shadow-lg flex flex-col space-y-4 h-full mx-auto">
                    <h2 class="w-full text-3xl text-teal-400 font-semibold">Títol: {{ $video->title }}</h2>
                    <p class="text-lg text-gray-400">Publicat el: {{ $video->published_at ? $video->published_at->format('d/m/Y') : 'No publicat' }}</p>

                    <div class="w-full">
                        <h3 class="text-2xl text-teal-400 font-semibold">Descripció</h3>
                        <p class="text-lg text-gray-300">{{ $video->description ?? 'No disponible' }}</p>
                    </div>

                    <!-- URL del vídeo guardada a la base de dades -->
                    <div class="w-full">
                        <h3 class="text-2xl text-teal-400 font-semibold">URL</h3>
                        <p class="text-lg text-gray-300 break-words">{{ $video->url }}</p>
                    </div>

                    <!-- URL del vídeo a la APP -->
                    <div class="w-full">
                        <h3 class="text-2xl text-teal-400 font-semibold">URL APP</h3>
                        <a href="{{ route('videos.show', ['video' => $video->id]) }}" class="text-blue-500 hover:underline" target="_blank">Veure vídeo</a>
                    </div>

                    <!-- Contenidor flex per als botons -->
                    <div class="flex mt-auto justify-end space-x-4 w-full">
                        <a href="{{ route('videos.manage.edit', $video) }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-500 transition duration-200">Editar</a>
                        <a href="{{ route('videos.manage.delete', $video) }}" class="bg-red-600 text-white px-4 py-2 rounded text-sm hover:bg-red-500 transition duration-200">Eliminar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
