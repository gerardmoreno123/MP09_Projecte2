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
    <div class="container mx-auto px-4 py-12">
        <!-- Breadcrumbs -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('users.manage.index') }}" class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-blue-400 transition-colors"
                       aria-label="Volver a la lista de usuarios">
                        <i class="fas fa-users mr-2" aria-hidden="true"></i>
                        Usuarios
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-xs text-slate-500 mx-2" aria-hidden="true"></i>
                        <span class="text-sm font-medium text-blue-400">Nuevo usuario</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Form Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white">
                <i class="fas fa-user-plus text-blue-400 mr-2" aria-hidden="true"></i>
                Crear nuevo usuario
            </h1>
        </div>

        <!-- Form -->
        <form action="{{ route('users.manage.store') }}" method="POST" class="bg-slate-800 rounded-xl shadow-lg p-4 sm:p-6 max-w-md mx-auto" enctype="multipart/form-data">
            @csrf

            <!-- Name -->
            <div class="mb-4 sm:mb-6">
                <label for="name" class="block text-sm font-medium text-slate-400 mb-2">Nombre *</label>
                <input type="text" id="name" name="name" required
                       class="input"
                       value="{{ old('name') }}"
                       placeholder="Nombre completo del usuario"
                       aria-label="Nombre del usuario">
                @error('name')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-4 sm:mb-6">
                <label for="email" class="block text-sm font-medium text-slate-400 mb-2">Email *</label>
                <input type="email" id="email" name="email" required
                       class="input"
                       value="{{ old('email') }}"
                       placeholder="Email del usuario"
                       aria-label="Email del usuario">
                @error('email')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4 sm:mb-6">
                <label for="password" class="block text-sm font-medium text-slate-400 mb-2">Contraseña *</label>
                <input type="password" id="password" name="password" required
                       class="input"
                       placeholder="Contraseña del usuario"
                       aria-label="Contraseña del usuario">
                @error('password')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Profile Photo -->
            <div class="mb-4 sm:mb-6">
                <label for="profile_photo" class="block text-sm font-medium text-slate-400 mb-2">Foto de perfil</label>
                <input type="file" id="profile_photo" name="profile_photo"
                       class="input-file"
                       aria-label="Foto de perfil del usuario">
                @error('profile_photo')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Roles -->
            <div class="mb-4 sm:mb-6">
                <label class="block text-sm font-medium text-slate-400 mb-2">Roles *</label>
                <div class="flex flex-wrap gap-2 sm:gap-3">
                    @foreach($roles as $role)
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" id="role_{{ $role->name }}" name="roles[]" value="{{ $role->name }}"
                                   class="rounded border-slate-600 text-blue-500 focus:ring-blue-500"
                                   {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}
                                   aria-label="Seleccionar rol {{ $role->name }}">
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
            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-4 pt-4 border-t border-slate-700">
                <a href="{{ route('users.manage.index') }}"
                   class="btn btn-secondary"
                   aria-label="Cancelar creación de usuario">
                    Cancelar
                </a>
                <button type="submit"
                        class="btn btn-primary flex items-center"
                        aria-label="Crear usuario">
                    <i class="fas fa-save mr-2" aria-hidden="true"></i>
                    Crear usuario
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
