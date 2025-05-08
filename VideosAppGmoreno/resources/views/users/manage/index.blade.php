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
        <!-- Header and Tabs -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 md:mb-8 gap-4">
            <h1 class="text-2xl font-bold text-white">
                <i class="fas fa-users text-blue-400 mr-2" aria-hidden="true"></i>
                Gestión de Usuarios
            </h1>

            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4 w-full md:w-auto">
                <a href="{{ route('users.manage.index') }}"
                   class="btn {{ request()->routeIs('users.manage.index') ? 'btn-primary' : 'btn-secondary' }}">
                    Listado
                </a>
                <a href="{{ route('users.manage.create') }}"
                   class="btn {{ request()->routeIs('users.manage.create') ? 'btn-primary' : 'btn-secondary' }}">
                    Nuevo Usuario
                </a>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="mb-6 bg-slate-800 p-4 rounded-xl shadow-md flex flex-col md:flex-row gap-4 items-center">
            <form action="{{ route('users.manage.index') }}" method="GET" class="flex-1 flex gap-4">
                <div class="relative flex-1">
                    <input type="text" name="search" placeholder="Buscar usuarios..." value="{{ request('search') }}"
                           class="input input-primary"
                           aria-label="Buscar usuarios">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400" aria-hidden="true"></i>
                </div>
                <button type="submit" class="btn btn-primary">
                    Buscar
                </button>
            </form>
            @if(request('search'))
                <a href="{{ route('users.manage.index') }}" class="btn btn-ghost" aria-label="Limpiar búsqueda">
                    <i class="fas fa-times-circle mr-1" aria-hidden="true"></i> Limpiar
                </a>
            @endif
        </div>

        <!-- Users Table (Desktop) -->
        <div class="bg-slate-800 rounded-xl shadow-lg overflow-hidden">
            <div class="hidden sm:block">
                <table class="w-full text-left">
                    <thead class="bg-slate-700">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-medium text-white">Usuario</th>
                        <th scope="col" class="px-6 py-4 font-medium text-white">Email</th>
                        <th scope="col" class="px-6 py-4 font-medium text-white">Roles</th>
                        <th scope="col" class="px-6 py-4 font-medium text-white">Registro</th>
                        <th scope="col" class="px-6 py-4 font-medium text-white text-right">Acciones</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                    @forelse($users as $user)
                        <tr onclick="window.location='{{ route('users.show', $user) }}';" class="hover:bg-slate-700/50 transition-colors cursor-pointer">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
                                             class="w-10 h-10 rounded-full object-cover"
                                             onerror="this.onerror=null;this.src='data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%2264%22%20height%3D%2264%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2064%2064%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_18d9b7b6b8f%20text%20%7B%20fill%3A%23ffffff%3Bfont-weight%3Abold%3Bfont-family%3AArial%2C%20Helvetica%2C%20Open%20Sans%2C%20sans-serif%2C%20monospace%3Bfont-size%3A10pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_18d9b7b6b8f%22%3E%3Crect%20width%3D%2264%22%20height%3D%2264%22%20fill%3D%22%232779be%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%2213.5546875%22%20y%3D%2236.5%22%3E64x64%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E';"
                                             aria-label="Foto de perfil de {{ $user->name }}">
                                    </div>
                                    <div>
                                        <div class="font-medium text-white">{{ $user->name }}</div>
                                        <div class="text-sm text-slate-400">ID: {{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-white">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($user->roles as $role)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $roleColors[$role->name] ?? 'bg-gray-500 text-white' }}">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-400">
                                <div class="text-sm">{{ $user->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs">{{ $user->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('users.manage.edit', $user->id) }}"
                                       class="btn btn-primary btn-sm"
                                       title="Editar usuario"
                                       aria-label="Editar usuario"
                                       onclick="event.stopPropagation();">
                                        <i class="fas fa-edit text-sm" aria-hidden="true"></i>
                                    </a>
                                    <a href="{{ route('users.manage.delete', $user->id) }}"
                                       class="btn btn-danger btn-sm"
                                       title="Eliminar usuario"
                                       aria-label="Eliminar usuario"
                                       onclick="event.stopPropagation();">
                                        <i class="fas fa-trash-alt text-sm" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                <div class="bg-slate-800 rounded-xl">
                                    <i class="fas fa-user-slash text-5xl mb-4" aria-hidden="true"></i>
                                    <h3 class="text-xl font-medium text-white mb-2">No hay usuarios registrados</h3>
                                    <p class="text-slate-400">Aún no se han registrado usuarios en la plataforma.</p>
                                    <a href="{{ route('users.manage.create') }}"
                                       class="inline-block mt-4 btn btn-primary">
                                        <i class="fas fa-plus mr-2" aria-hidden="true"></i> Crear nuevo usuario
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Users List (Mobile) -->
            <div class="block sm:hidden space-y-4 p-4">
                @forelse($users as $user)
                    <div class="bg-slate-900 rounded-lg p-4 shadow-md hover:bg-slate-700/50 transition-colors cursor-pointer"
                         onclick="window.location='{{ route('users.show', $user) }}';">
                        <div class="flex items-center space-x-4 mb-3">
                            <div class="flex-shrink-0">
                                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
                                     class="w-8 h-8 rounded-full object-cover"
                                     onerror="this.onerror=null;this.src='data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%2264%22%20height%3D%2264%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2064%2064%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_18d9b7b6b8f%20text%20%7B%20fill%3A%23ffffff%3Bfont-weight%3Abold%3Bfont-family%3AArial%2C%20Helvetica%2C%20Open%20Sans%2C%20sans-serif%2C%20monospace%3Bfont-size%3A10pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_18d9b7b6b8f%22%3E%3Crect%20width%3D%2264%22%20height%3D%2264%22%20fill%3D%22%232779be%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%2213.5546875%22%20y%3D%2236.5%22%3E64x64%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E';"
                                     aria-label="Foto de perfil de {{ $user->name }}">
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-white">{{ $user->name }}</div>
                                <div class="text-sm text-slate-400">ID: {{ $user->id }}</div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-400">Email:</span>
                                <span class="text-sm text-white break-all">{{ $user->email }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-400">Roles:</span>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($user->roles as $role)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $roleColors[$role->name] ?? 'bg-gray-500 text-white' }}">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-400">Registro:</span>
                                <div class="text-right text-sm text-slate-400">
                                    <div>{{ $user->created_at->format('d/m/Y') }}</div>
                                    <div class="text-xs">{{ $user->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            <div class="flex justify-end space-x-2 mt-3">
                                <a href="{{ route('users.manage.edit', $user->id) }}"
                                   class="btn btn-primary btn-sm"
                                   title="Editar usuario"
                                   aria-label="Editar usuario"
                                   onclick="event.stopPropagation();">
                                    <i class="fas fa-edit text-sm" aria-hidden="true"></i>
                                </a>
                                <a href="{{ route('users.manage.delete', $user->id) }}"
                                   class="btn btn-danger btn-sm"
                                   title="Eliminar usuario"
                                   aria-label="Eliminar usuario"
                                   onclick="event.stopPropagation();">
                                    <i class="fas fa-trash-alt text-sm" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-slate-800 rounded-xl p-12 text-center">
                        <i class="fas fa-user-slash text-5xl text-slate-600 mb-4" aria-hidden="true"></i>
                        <h3 class="text-xl font-medium text-white mb-2">No hay usuarios registrados</h3>
                        <p class="text-slate-400">Aún no se han registrado usuarios en la plataforma.</p>
                        <a href="{{ route('users.manage.create') }}"
                           class="inline-block mt-4 btn btn-primary">
                            <i class="fas fa-plus mr-2" aria-hidden="true"></i> Crear nuevo usuario
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-slate-700 flex justify-center">
                    {{ $users->links('vendor.pagination.custom-tailwind') }}
                </div>
            @endif
        </div>
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
            color: #d1d5db;
        }

        .btn-secondary:hover {
            background-color: #374151;
        }

        .btn-ghost {
            color: #3b82f6;
            background-color: transparent;
        }

        .btn-ghost:hover {
            color: #2563eb;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .btn-danger {
            background-color: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background-color: #dc2626;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
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
            padding-left: 2.5rem;
        }

        .input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
        }
    </style>
@endsection
