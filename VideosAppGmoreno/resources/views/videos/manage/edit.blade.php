@extends('layouts.admin')

@section('admin-content')
    <div class="container mx-auto px-4 py-12">
        <!-- Breadcrumbs -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('videos.manage.index') }}" class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-blue-400 transition-colors">
                        <i class="fas fa-video mr-2" aria-hidden="true"></i>
                        Videos
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-xs text-slate-500 mx-2" aria-hidden="true"></i>
                        <span class="text-sm font-medium text-blue-400">Editar video</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Form Header -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-white">
                <i class="fas fa-edit text-blue-400 mr-2" aria-hidden="true"></i>
                Editar video: {{ $video->title }}
            </h1>
        </div>

        <!-- Form -->
        <form action="{{ route('videos.manage.update', $video->id) }}" method="POST" class="bg-slate-800 rounded-xl shadow-lg p-4 sm:p-6 max-w-4xl mx-auto">
            @csrf
            @method('PUT')

            <!-- Título -->
            <div class="mb-4 sm:mb-6">
                <label for="title" class="block text-sm font-medium text-slate-400 mb-2">Título *</label>
                <input type="text" id="title" name="title" required
                       class="input input-primary"
                       value="{{ old('title', $video->title) }}"
                       placeholder="Título del video"
                       aria-label="Título del video"
                       aria-describedby="title-error">
                @error('title')
                <p id="title-error" class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Descripción -->
            <div class="mb-4 sm:mb-6">
                <label for="description" class="block text-sm font-medium text-slate-400 mb-2">Descripción *</label>
                <textarea id="description" name="description" required
                          class="input input-primary h-32 resize-y"
                          placeholder="Descripción del video"
                          aria-label="Descripción del video"
                          aria-describedby="description-error">{{ old('description', $video->description) }}</textarea>
                @error('description')
                <p id="description-error" class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- URL -->
            <div class="mb-4 sm:mb-6">
                <label for="url" class="block text-sm font-medium text-slate-400 mb-2">URL del video *</label>
                <input type="url" id="url" name="url" required
                       class="input input-primary"
                       value="{{ old('url', $video->url) }}"
                       placeholder="URL del video"
                       aria-label="URL del video"
                       aria-describedby="url-error">
                @error('url')
                <p id="url-error" class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Serie -->
            <div class="mb-4 sm:mb-6">
                <label for="serie_id" class="block text-sm font-medium text-slate-400 mb-2">Serie</label>
                <select id="serie_id" name="serie_id"
                        class="input input-primary"
                        aria-label="Seleccionar serie"
                        aria-describedby="serie_id-error">
                    <option value="">Sin serie</option>
                    @foreach($series as $serie)
                        <option value="{{ $serie->id }}" {{ old('serie_id', $video->serie_id) == $serie->id ? 'selected' : '' }}>
                            {{ $serie->title }}
                        </option>
                    @endforeach
                </select>
                @error('serie_id')
                <p id="serie_id-error" class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-4 border-t border-slate-700">
                <a href="{{ route('videos.manage.index') }}"
                   class="btn btn-secondary">
                    Cancelar
                </a>
                <button type="submit"
                        class="btn btn-primary flex items-center">
                    <i class="fas fa-save mr-2" aria-hidden="true"></i>
                    Actualizar video
                </button>
            </div>
        </form>
    </div>

    <style>
        /* Button Styles */
        .btn {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .btn-primary {
            background-color: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2563eb;
        }

        .btn-secondary {
            background-color: #4b5563;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #374151;
        }

        /* Input Styles */
        .input {
            width: 100%;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            background-color: #334155;
            border: 1px solid #475569;
            color: white;
            transition: border-color 0.3s ease;
        }

        .input-primary {
            @media (max-width: 640px) {
                padding: 0.375rem 0.75rem;
                font-size: 0.875rem;
            }
        }

        .input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
        }

        .input-primary.h-32 {
            height: 8rem;
            resize: vertical;
        }
    </style>
@endsection
