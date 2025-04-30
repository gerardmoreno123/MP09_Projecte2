@extends('layouts.videos-app-layout')

@section('content')
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold text-white mb-8 flex items-center">
                <i class="fas fa-plus text-emerald-500 mr-3"></i>
                Crear Nueva Serie
            </h1>

            <div class="bg-slate-800 rounded-xl shadow-lg p-6">
                <form action="{{ route('series.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Title -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-slate-400 mb-2">Título *</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}"
                               class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white"
                               placeholder="Ej: Tutoriales de Laravel"
                               required>
                        @error('title')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-slate-400 mb-2">Descripción</label>
                        <textarea name="description" id="description" rows="5"
                                  class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white"
                                  placeholder="Describe el contenido de esta serie">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Image Upload -->
                    <div class="mb-6">
                        <label for="image" class="block text-sm font-medium text-slate-400 mb-2">Imagen destacada</label>
                        <input type="file" name="image" id="image" accept="image/*"
                               class="w-full text-sm text-slate-400
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-lg file:border-0
                                      file:text-sm file:font-medium
                                      file:bg-slate-700 file:text-white
                                      hover:file:bg-slate-600">
                        @error('image')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- User Name -->
                    <div class="mb-6">
                        <label for="user_name" class="block text-sm font-medium text-slate-400 mb-2">Nombre del creador</label>
                        <input type="text" name="user_name" id="user_name" readonly
                               class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-400 cursor-not-allowed"
                               value="{{ auth()->user()->name }}">
                        @error('user_name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- User Photo URL -->
                    <div class="mb-6">
                        <label for="user_photo_url" class="block text-sm font-medium text-slate-400 mb-2">Foto del creador</label>
                        <input type="url" name="user_photo_url" id="user_photo_url" readonly
                               class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-400 cursor-not-allowed"
                               value="{{ auth()->user()->profile_photo_url ?? '' }}"
                               placeholder="Sin foto de perfil">
                        @error('user_photo_url')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-4">
                        <a href="{{ route('series.index') }}"
                           class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg">Cancelar</a>
                        <button type="submit"
                                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg">Crear Serie</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
