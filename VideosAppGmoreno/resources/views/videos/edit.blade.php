@extends('layouts.videos-app-layout')

@section('content')
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold text-white mb-8 flex items-center">
                <i class="fas fa-edit text-blue-500 mr-3"></i>
                Editar Video
            </h1>

            <div class="bg-slate-800 rounded-xl shadow-lg p-6">
                <form action="{{ route('videos.update', $video->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Title -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-slate-400 mb-2">Título *</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $video->title) }}"
                               class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white"
                               required>
                        @error('title')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-slate-400 mb-2">Descripción *</label>
                        <textarea name="description" id="description" rows="5"
                                  class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white"
                                  required>{{ old('description', $video->description) }}</textarea>
                        @error('description')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- URL -->
                    <div class="mb-6">
                        <label for="url" class="block text-sm font-medium text-slate-400 mb-2">URL del Video *</label>
                        <input type="url" name="url" id="url" value="{{ old('url', $video->url) }}"
                               class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white"
                               required>
                        @error('url')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Serie -->
                    <div class="mb-6">
                        <label for="serie_id" class="block text-sm font-medium text-slate-400 mb-2">Serie (opcional)</label>
                        <select name="serie_id" id="serie_id"
                                class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white">
                            @if ($video->serie_id)
                                <option value="{{ $video->serie_id }}">{{ $video->serie->title }}</option>
                            @else
                                <option value="">Selecciona una serie</option>
                            @endif
                            @foreach($series as $serie)
                                @auth
                                    <!-- Show only series created by the authenticated user -->
                                    @if (auth()->user()->name == $serie->user_name)
                                        <option value="{{ $serie->id }}" {{ old('serie_id') == $serie->id ? 'selected' : '' }}>
                                            {{ $serie->title }}
                                        </option>

                                    @endif
                                @endauth
                            @endforeach
                        </select>
                        @error('serie_id')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-4">
                        <a href="{{ route('videos.index') }}"
                           class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg">Cancelar</a>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Actualizar Video</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
