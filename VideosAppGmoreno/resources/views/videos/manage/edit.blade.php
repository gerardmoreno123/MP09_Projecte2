@extends('layouts.admin')

@section('admin-content')
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Breadcrumbs -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('videos.manage.index') }}" class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-blue-400 transition-colors">
                        <i class="fas fa-video mr-2"></i>
                        Videos
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-xs text-slate-500 mx-2"></i>
                        <span class="text-sm font-medium text-blue-400">Editar video</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Form Header -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-white">
                <i class="fas fa-edit text-blue-400 mr-2"></i>
                Editar video: {{ $video->title }}
            </h1>
        </div>

        <!-- Form -->
        <form action="{{ route('videos.manage.update', $video->id) }}" method="POST" class="bg-slate-800 rounded-xl shadow-lg p-6">
            @csrf
            @method('PUT')

            <!-- Título -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-slate-400 mb-2">Título *</label>
                <input type="text" id="title" name="title" required
                       class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white"
                       value="{{ old('title', $video->title) }}"
                       placeholder="Título del video">
                @error('title')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Descripción -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-slate-400 mb-2">Descripción *</label>
                <textarea id="description" name="description" required
                          class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white"
                          rows="4" placeholder="Descripción del video">{{ old('description', $video->description) }}</textarea>
                @error('description')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- URL -->
            <div class="mb-6">
                <label for="url" class="block text-sm font-medium text-slate-400 mb-2">URL del video *</label>
                <input type="url" id="url" name="url" required
                       class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white"
                       value="{{ old('url', $video->url) }}"
                       placeholder="URL del video">
                @error('url')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Serie -->
            <div class="mb-6">
                <label for="serie_id" class="block text-sm font-medium text-slate-400 mb-2">Serie</label>
                <select id="serie_id" name="serie_id"
                        class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white">
                    <option value="">Sin serie</option>
                    @foreach($series as $serie)
                        <option value="{{ $serie->id }}" {{ old('serie_id', $video->serie_id) == $serie->id ? 'selected' : '' }}>
                            {{ $serie->title }}
                        </option>
                    @endforeach
                </select>
                @error('serie_id')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 pt-4 border-t border-slate-700">
                <a href="{{ route('videos.manage.index') }}"
                   class="px-6 py-2 bg-slate-700 hover:bg-slate-600 text-white font-medium rounded-lg transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Actualizar video
                </button>
            </div>
        </form>
    </div>
@endsection
