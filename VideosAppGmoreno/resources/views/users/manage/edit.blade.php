@php
    $roleColors = [
        'super-admin' => 'bg-red-500',
        'video-manager' => 'bg-blue-500',
        'user-manager' => 'bg-yellow-500',
        'viewer' => 'bg-green-500',
    ];
@endphp

@extends('layouts.admin')

@section('admin-content')
    <div class="container mx-auto px-4 text-center">
        <!-- Tabs -->
        <div class="flex flex-col md:flex-row justify-center space-y-2 md:space-y-0 md:space-x-6 pb-2 mb-6">
            <a href="{{ route('users.manage.index') }}" class="text-lg font-semibold text-gray-400 pb-2 inline-block text-center w-full hover:text-green-400 hover:border-green-400 hover:border-b-2 transition duration-200">Llista d'Usuaris</a>
            <a href="{{ route('users.manage.create') }}" class="text-lg font-semibold text-gray-400 pb-2 inline-block text-center w-full hover:text-green-400 hover:border-green-400 hover:border-b-2 transition duration-200">Afegir Usuari</a>
            <a href="{{ route('users.manage.edit', $user->id) }}" class="text-lg font-semibold text-blue-400 border-b-2 pb-2 inline-block text-center w-full">Editar Usuari</a>
        </div>

        <!-- Contenidor del formulari -->
        <div class="max-w-lg mx-auto bg-gray-800 p-8 rounded-lg shadow-lg">
            <h2 class="text-3xl font-semibold text-teal-400 mb-6">Editar Usuari</h2>

            <form id="user-form" action="{{ route('users.manage.update', $user->id) }}" method="POST" class="space-y-4" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Nom -->
                <div>
                    <label for="name" class="block text-lg font-semibold text-gray-300 mb-1">Nom</label>
                    <input type="text" name="name" id="name" class="w-full p-3 border border-gray-600 rounded-md bg-gray-700 text-white focus:ring-2 focus:ring-teal-400 focus:border-teal-400 transition @error('name') border-red-500 @enderror" placeholder="Introdueix el nom de l'usuari" required value="{{ old('name', $user->name) }}">
                    @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-lg font-semibold text-gray-300 mb-1">Email</label>
                    <input type="email" name="email" id="email" class="w-full p-3 border border-gray-600 rounded-md bg-gray-700 text-white focus:ring-2 focus:ring-teal-400 focus:border-teal-400 transition @error('email') border-red-500 @enderror" placeholder="Introdueix l'email de l'usuari" required value="{{ old('email', $user->email) }}">
                    @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Contrasenya -->
                <div>
                    <label for="password" class="block text-lg font-semibold text-gray-300 mb-1">Contrasenya</label>
                    <input type="password" name="password" id="password" class="w-full p-3 border border-gray-600 rounded-md bg-gray-700 text-white focus:ring-2 focus:ring-teal-400 focus:border-teal-400 transition @error('password') border-red-500 @enderror" placeholder="Introdueix la contrasenya de l'usuari">
                    @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Foto de perfil -->
                <div>
                    <label for="profile_photo" class="block text-lg font-semibold text-gray-300 mb-1">Foto de perfil</label>
                    <input type="file" name="profile_photo" id="profile_photo" class="w-full p-3 border border-gray-600 rounded-md bg-gray-700 text-white focus:ring-2 focus:ring-teal-400 focus:border-teal-400 transition @error('profile_photo') border-red-500 @enderror">
                    @error('profile_photo')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Rols -->
                <div>
                    <label for="roles" class="block text-lg font-semibold text-gray-300 mb-1">Rols</label>
                    <div id="roles-container" class="flex flex-wrap gap-2">
                        @foreach($roles as $role)
                            <span class="role-option inline-block px-2 py-1 rounded-full text-white cursor-pointer {{ $roleColors[$role->name] ?? 'bg-gray-500' }} {{ !in_array($role->name, $user->roles->pluck('name')->toArray()) ? 'opacity-50' : '' }}" data-role="{{ $role->name }}">
                                {{ $role->name }}
                            </span>
                        @endforeach
                    </div>
                    <input type="hidden" name="roles[]" id="roles-input" value="{{ implode(',', $user->roles->pluck('name')->toArray()) }}" required>
                    @error('roles')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    <div id="roles-error" class="text-red-500 text-sm hidden">Please select at least one role.</div>
                </div>

                <button type="submit" class="w-full bg-green-500 text-white font-semibold text-lg py-3 rounded-md hover:bg-green-600 transition">
                    Actualitzar Usuari
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roleOptions = document.querySelectorAll('.role-option');
            const rolesInput = document.getElementById('roles-input');
            const rolesError = document.getElementById('roles-error');
            const userForm = document.getElementById('user-form');
            let selectedRoles = rolesInput.value.split(',');

            roleOptions.forEach(option => {
                option.addEventListener('click', function () {
                    const role = this.getAttribute('data-role');
                    if (selectedRoles.includes(role)) {
                        selectedRoles = selectedRoles.filter(r => r !== role);
                        this.classList.add('opacity-50');
                    } else {
                        selectedRoles.push(role);
                        this.classList.remove('opacity-50');
                    }
                    rolesInput.value = selectedRoles;
                });
            });

            userForm.addEventListener('submit', function (event) {
                if (selectedRoles.length === 0) {
                    event.preventDefault();
                    rolesError.classList.remove('hidden');
                } else {
                    rolesError.classList.add('hidden');
                }
            });
        });
    </script>
@endsection
