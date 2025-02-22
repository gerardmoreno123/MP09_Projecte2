@extends('layouts.videos-app-layout')

@section('content')
    <div class="container mx-auto px-4 text-center">
        <!-- Tabs -->
        <div class="flex justify-center space-x-6 pb-2 mb-6">
            <a href="{{ route('videos.manage.index') }}" class="text-lg font-semibold text-gray-400 pb-2 inline-block text-center w-full hover:text-green-400 hover:border-green-400 hover:border-b-2 transition duration-200">Llista de Vídeos</a>
            <a href="{{ route('videos.manage.create') }}" class="text-lg font-semibold text-gray-400 pb-2 inline-block text-center w-full hover:text-green-400 hover:border-green-400 hover:border-b-2 transition duration-200">Afegir Vídeo</a>
            <a href="{{ route('videos.manage.edit', $video->id) }}" class="text-lg font-semibold text-blue-400 border-b-2 pb-2 inline-block text-center w-full">Editar Vídeo</a>
        </div>

        <!-- Contenedor del formulari -->
        <div class="max-w-lg mx-auto bg-gray-800 p-8 rounded-lg shadow-lg">
            <h2 class="text-3xl font-semibold text-teal-400 mb-6">Editar Vídeo</h2>

            <form action="{{ route('videos.manage.update', $video->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Títol -->
                <div>
                    <label for="title" class="block text-lg font-semibold text-gray-300 mb-1">Títol</label>
                    <input type="text" name="title" id="title" class="w-full p-3 border border-gray-600 rounded-md bg-gray-700 text-white focus:ring-2 focus:ring-teal-400 focus:border-teal-400 transition @error('title') border-red-500 @enderror" placeholder="Introdueix el títol del vídeo" required value="{{ old('title', $video->title) }}">
                    @error('title')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Descripció -->
                <div>
                    <label for="description" class="block text-lg font-semibold text-gray-300 mb-1">Descripció</label>
                    <textarea name="description" id="description" rows="4" class="w-full p-3 border border-gray-600 rounded-md bg-gray-700 text-white focus:ring-2 focus:ring-teal-400 focus:border-teal-400 transition @error('description') border-red-500 @enderror" placeholder="Escriu una breu descripció..." required>{{ old('description', $video->description) }}</textarea>
                    @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- URL -->
                <div>
                    <label for="url" class="block text-lg font-semibold text-gray-300 mb-1">URL del Vídeo</label>
                    <input type="url" name="url" id="url" class="w-full p-3 border border-gray-600 rounded-md bg-gray-700 text-white focus:ring-2 focus:ring-teal-400 focus:border-teal-400 transition @error('url') border-red-500 @enderror" placeholder="Introdueix l'URL del vídeo" required value="{{ old('url', $video->url) }}">
                    @error('url')
                    <span class="text-red-500 text-sm">Aquesta URL ja existeix</span>
                    @enderror
                </div>

                <!-- Botó de enviar -->
                <button type="submit" class="w-full bg-green-500 text-white font-semibold text-lg py-3 rounded-md hover:bg-green-600 transition">
                    Actualitzar Vídeo
                </button>
            </form>
        </div>
    </div>
@endsection
