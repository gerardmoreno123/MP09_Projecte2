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
    <div class="container mx-auto px-4">
        <!-- Pestanyes -->
        <div class="flex flex-col md:flex-row justify-center space-y-2 md:space-y-0 md:space-x-6 pb-2 mb-6 text-center">
            <a href="{{ route('users.manage.index') }}" class="text-lg font-semibold text-green-400 border-b-2 pb-2 inline-block w-full">
                Llista d'Usuaris
            </a>
            <a href="{{ route('users.manage.create') }}" class="text-lg font-semibold text-gray-400 pb-2 inline-block w-full hover:text-green-400 hover:border-green-400 hover:border-b-2 transition duration-200">
                Afegir Usuari
            </a>
        </div>

        <!-- Tabla de Usuarios (Responsive) -->
        <div class="mt-4 overflow-x-auto">
            <table class="w-full min-w-[600px] text-left border-collapse text-white">
                <thead>
                <tr class="border-b border-white">
                    <th class="py-4 px-2 text-center">#</th>
                    <th class="py-4 px-2">Nom</th>
                    <th class="py-4 px-2 text-center">Email</th>
                    <th class="py-4 px-2 text-center">Roles</th>
                    <th class="py-4 px-2 text-center">Accions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $index => $user)
                    <tr onclick="window.location='{{ route('users.show', $user) }}';" class="border-b border-gray-600 hover:bg-gray-700 cursor-pointer transition duration-200">
                        <td class="py-4 px-2 text-center">{{ $index + 1 }}</td>
                        <td class="py-4 px-2 text-green-400">{{ $user->name }}</td>
                        <td class="py-4 px-2 text-center break-all">{{ $user->email }}</td>
                        <td class="py-4 px-2 text-center">
                            @foreach ($user->roles as $role)
                                <span class="inline-block px-2 py-1 rounded-full text-white {{ $roleColors[$role->name] ?? 'bg-gray-500' }}">
                                        {{ $role->name }}
                                    </span>
                            @endforeach
                        </td>
                        <td class="py-4 px-2 text-center space-y-2">
                            <a href="{{ route('users.manage.edit', $user) }}" class="block bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-500 transition duration-200">
                                Editar
                            </a>
                            <a href="{{ route('users.manage.delete', $user) }}" class="block bg-red-600 text-white px-4 py-2 rounded text-sm hover:bg-red-500 transition duration-200">
                                Eliminar
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
