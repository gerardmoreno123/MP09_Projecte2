@extends('layouts.admin')

@section('admin-content')
    <div class="container mx-auto px-4 py-12">
        <!-- Breadcrumbs -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('series.manage.index') }}" class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-blue-400 transition-colors"
                       aria-label="Volver a la lista de series">
                        <i class="fas fa-list mr-2" aria-hidden="true"></i>
                        Series
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-xs text-slate-500 mx-2" aria-hidden="true"></i>
                        <span class="text-sm font-medium text-blue-400">Editar serie</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Form Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white">
                <i class="fas fa-edit text-blue-400 mr-2" aria-hidden="true"></i>
                Editar serie: {{ $serie->title }}
            </h1>
        </div>

        <!-- Form -->
        <form action="{{ route('series.manage.update', $serie->id) }}" method="POST" class="bg-slate-800 rounded-xl shadow-lg p-4 sm:p-6 max-w-md mx-auto" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="mb-4 sm:mb-6">
                <label for="title" class="block text-sm font-medium text-slate-400 mb-2">Título de la serie *</label>
                <input type="text" id="title" name="title" required
                       class="input"
                       value="{{ old('title', $serie->title) }}"
                       placeholder="Ej: Tutoriales de Laravel"
                       aria-label="Título de la serie">
                @error('title')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-4 sm:mb-6">
                <label for="description" class="block text-sm font-medium text-slate-400 mb-2">Descripción</label>
                <textarea id="description" name="description" rows="4"
                          class="input"
                          placeholder="Describe el contenido de esta serie"
                          aria-label="Descripción de la serie">{{ old('description', $serie->description) }}</textarea>
                @error('description')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Image -->
            <div class="mb-4 sm:mb-6">
                <label class="block text-sm font-medium text-slate-400 mb-2">Imagen actual</label>
                @if($serie->image)
                    <div class="flex items-center space-x-4">
                        <img src="{{ asset('storage/' . $serie->image) }}" alt="Imagen actual"
                             class="w-32 h-32 object-cover rounded-lg"
                             aria-label="Imagen actual de la serie {{ $serie->title }}">
                    </div>
                @else
                    <div class="w-32 h-32 bg-slate-700 rounded-lg flex items-center justify-center">
                        <i class="fas fa-image text-2xl text-slate-500" aria-hidden="true"></i>
                        <span class="sr-only">Sin imagen</span>
                    </div>
                @endif
            </div>

            <!-- New Image Upload -->
            <div class="mb-4 sm:mb-6">
                <label for="image" class="block text-sm font-medium text-slate-400 mb-2">Nueva imagen destacada</label>
                <input type="file" id="image" name="image"
                       class="input-file"
                       aria-label="Nueva imagen destacada de la serie">
                @error('image')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- User Name -->
            <div class="mb-4 sm:mb-6">
                <label for="user_name" class="block text-sm font-medium text-slate-400 mb-2">Nombre del creador</label>
                <input type="text" id="user_name" name="user_name" readonly
                       class="input bg-slate-900 cursor-not-allowed"
                       value="{{ auth()->user()->name }}"
                       data-qa="series-user-name-input"
                       aria-label="Nombre del creador">
                @error('user_name')
                <p class="mt-1 text-sm text-red-400" data-qa="series-user-name-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- User Photo URL -->
            <div class="mb-4 sm:mb-6">
                <label for="user_photo_url" class="block text-sm font-medium text-slate-400 mb-2">Foto del creador</label>
                <input type="url" id="user_photo_url" name="user_photo_url" readonly
                       class="input bg-slate-900 cursor-not-allowed"
                       value="{{ auth()->user()->profile_photo_url ?? '' }}"
                       placeholder="Sin foto de perfil"
                       data-qa="series-user-photo-url-input"
                       aria-label="Foto del creador">
                @error('user_photo_url')
                <p class="mt-1 text-sm text-red-400" data-qa="series-user-photo-url-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Published At -->
            <div class="mb-4 sm:mb-6">
                <label for="published_at" class="block text-sm font-medium text-slate-400 mb-2">Fecha de publicación</label>
                <input type="datetime-local" id="published_at" name="published_at"
                       class="input"
                       value="{{ old('published_at', $serie->published_at ? $serie->published_at->format('Y-m-d\TH:i') : '') }}"
                       step="60"
                       aria-label="Fecha de publicación de la serie">
                @error('published_at')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-4 pt-4 border-t border-slate-700">
                <a href="{{ route('series.manage.index') }}"
                   class="btn btn-secondary"
                   aria-label="Cancelar edición de serie">
                    Cancelar
                </a>
                <button type="submit"
                        class="btn btn-primary flex items-center"
                        aria-label="Guardar cambios de la serie">
                    <i class="fas fa-save mr-2" aria-hidden="true"></i>
                    Guardar cambios
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

        .input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
        }

        .input-file {
            width: 100%;
            text-sm text-slate-400;
        }

        .input-file::file-selector-button {
            margin-right: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            border: none;
            font-weight: 500;
            background-color: #4b5563;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .input-file::file-selector-button:hover {
            background-color: #374151;
        }
    </style>
@endsection
