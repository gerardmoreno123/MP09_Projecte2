@extends('layouts.admin')

@section('admin-content')
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Breadcrumbs -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('series.manage.index') }}" class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-blue-400 transition-colors" data-qa="breadcrumb-series-index">
                        <i class="fas fa-list mr-2"></i>
                        Series
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-xs text-slate-500 mx-2"></i>
                        <span class="text-sm font-medium text-blue-400">Nueva serie</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Form Header -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-white">
                <i class="fas fa-plus-circle text-blue-400 mr-2"></i>
                Crear nueva serie
            </h1>
        </div>

        <!-- Form -->
        <form action="{{ route('series.manage.store') }}" method="POST" class="bg-slate-800 rounded-xl shadow-lg p-6" enctype="multipart/form-data" data-qa="series-create-form">
            @csrf

            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-slate-400 mb-2">Título de la serie *</label>
                <input type="text" id="title" name="title" required
                       class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white"
                       placeholder="Ej: Tutoriales de Laravel"
                       value="{{ old('title') }}"
                       data-qa="series-title-input">
                @error('title')
                <p class="mt-1 text-sm text-red-400" data-qa="series-title-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-slate-400 mb-2">Descripción</label>
                <textarea id="description" name="description" rows="4"
                          class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white"
                          placeholder="Describe el contenido de esta serie"
                          data-qa="series-description-textarea">{{ old('description') }}</textarea>
                @error('description')
                <p class="mt-1 text-sm text-red-400" data-qa="series-description-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image Upload -->
            <div class="mb-6">
                <label for="image" class="block text-sm font-medium text-slate-400 mb-2">Imagen destacada</label>
                <input type="file" id="image" name="image" accept="image/*"
                       class="w-full text-sm text-slate-400
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-lg file:border-0
                              file:text-sm file:font-medium
                              file:bg-slate-700 file:text-white
                              hover:file:bg-slate-600"
                       data-qa="series-image-input">
                @error('image')
                <p class="mt-1 text-sm text-red-400" data-qa="series-image-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- User Name -->
            <div class="mb-6">
                <label for="user_name" class="block text-sm font-medium text-slate-400 mb-2">Nombre del creador</label>
                <input type="text" id="user_name" name="user_name" readonly
                       class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-400 cursor-not-allowed"
                       value="{{ auth()->user()->name }}"
                       data-qa="series-user-name-input">
                @error('user_name')
                <p class="mt-1 text-sm text-red-400" data-qa="series-user-name-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- User Photo URL -->
            <div class="mb-6">
                <label for="user_photo_url" class="block text-sm font-medium text-slate-400 mb-2">Foto del creador</label>
                <input type="url" id="user_photo_url" name="user_photo_url" readonly
                       class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-400 cursor-not-allowed"
                       value="{{ auth()->user()->profile_photo_url ?? '' }}"
                       placeholder="Sin foto de perfil"
                       data-qa="series-user-photo-url-input">
                @error('user_photo_url')
                <p class="mt-1 text-sm text-red-400" data-qa="series-user-photo-url-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Published At -->
            <div class="mb-6">
                <label for="published_at" class="block text-sm font-medium text-slate-400 mb-2">Fecha de publicación</label>
                <input type="datetime-local" id="published_at" name="published_at"
                       class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white"
                       value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}"
                       step="60"
                       data-qa="series-published-at-input">
                @error('published_at')
                <p class="mt-1 text-sm text-red-400" data-qa="series-published-at-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 pt-4 border-t border-slate-700">
                <a href="{{ route('series.manage.index') }}"
                   class="px-6 py-2 bg-slate-700 hover:bg-slate-600 text-white font-medium rounded-lg transition-colors"
                   data-qa="series-cancel-button">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors flex items-center"
                        data-qa="series-submit-button">
                    <i class="fas fa-save mr-2"></i>
                    Guardar serie
                </button>
            </div>
        </form>
    </div>
@endsection
