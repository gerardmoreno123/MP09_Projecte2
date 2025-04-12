@php
    $roleColors = [
        'super-admin' => 'bg-red-500 text-white',
        'video-manager' => 'bg-blue-500 text-white',
        'user-manager' => 'bg-yellow-500 text-white',
        'serie-manager' => 'bg-purple-500 text-white',
        'viewer' => 'bg-green-500 text-white',
    ];
@endphp

@extends('layouts.admin')

@section('admin-content')
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Breadcrumbs -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('users.manage.index') }}" class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-blue-400 transition-colors">
                        <i class="fas fa-users mr-2"></i>
                        Usuarios
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-xs text-slate-500 mx-2"></i>
                        <span class="text-sm font-medium text-blue-400">Nuevo usuario</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Form Header -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-white">
                <i class="fas fa-user-plus text-blue-400 mr-2"></i>
                Crear nuevo usuario
            </h1>
        </div>

        <!-- Form -->
        <form action="{{ route('users.manage.store') }}" method="POST" class="bg-slate-800 rounded-xl shadow-lg p-6" enctype="multipart/form-data">
            @csrf

            <!-- Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-slate-400 mb-2">Nombre *</label>
                <input type="text" id="name" name="name" required
                       class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white"
                       value="{{ old('name') }}"
                       placeholder="Nombre completo del usuario">
                @error('name')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-slate-400 mb-2">Email *</label>
                <input type="email" id="email" name="email" required
                       class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white"
                       value="{{ old('email') }}"
                       placeholder="Email del usuario">
                @error('email')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-slate-400 mb-2">Contraseña *</label>
                <input type="password" id="password" name="password" required
                       class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white"
                       placeholder="Contraseña del usuario">
                @error('password')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Profile Photo -->
            <div class="mb-6">
                <label for="profile_photo" class="block text-sm font-medium text-slate-400 mb-2">Foto de perfil</label>
                <input type="file" id="profile_photo" name="profile_photo"
                       class="w-full text-sm text-slate-400
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-lg file:border-0
                              file:text-sm file:font-medium
                              file:bg-slate-700 file:text-white
                              hover:file:bg-slate-600">
                @error('profile_photo')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Roles -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-400 mb-2">Roles *</label>
                <div class="flex flex-wrap gap-3">
                    @foreach($roles as $role)
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                   class="rounded border-slate-600 text-blue-500 focus:ring-blue-500"
                                {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $roleColors[$role->name] ?? 'bg-gray-500 text-white' }}">
                                {{ $role->name }}
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('roles')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 pt-4 border-t border-slate-700">
                <a href="{{ route('users.manage.index') }}"
                   class="px-6 py-2 bg-slate-700 hover:bg-slate-600 text-white font-medium rounded-lg transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Crear usuario
                </button>
            </div>
        </form>
    </div>
@endsection
