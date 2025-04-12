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
    <div class="container mx-auto px-4">
        <!-- Header and Tabs -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <h1 class="text-2xl font-bold text-white mb-4 md:mb-0">
                <i class="fas fa-users text-blue-400 mr-2"></i>
                Gestión de Usuarios
            </h1>

            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4 w-full md:w-auto">
                <a href="{{ route('users.manage.index') }}"
                   class="px-4 py-2 text-center font-medium rounded-lg {{ request()->routeIs('users.manage.index') ? 'bg-blue-600 text-white' : 'bg-slate-700 text-slate-300 hover:bg-slate-600' }}">
                    Listado
                </a>
                <a href="{{ route('users.manage.create') }}"
                   class="px-4 py-2 text-center font-medium rounded-lg {{ request()->routeIs('users.manage.create') ? 'bg-blue-600 text-white' : 'bg-slate-700 text-slate-300 hover:bg-slate-600' }}">
                    Nuevo Usuario
                </a>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-slate-800 rounded-xl p-4 mb-6">
            <form action="{{ route('users.manage.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative">
                    <input type="text" name="search" placeholder="Buscar usuarios..." value="{{ request('search') }}"
                           class="w-full pl-10 pr-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white">
                    <i class="fas fa-search absolute left-3 top-3 text-slate-400"></i>
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    Buscar
                </button>
                @if(request('search'))
                    <a href="{{ route('users.manage.index') }}" class="px-4 py-2 text-slate-400 hover:text-blue-400 transition-colors flex items-center">
                        <i class="fas fa-times mr-2"></i> Limpiar
                    </a>
                @endif
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-slate-800 rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-700">
                    <tr>
                        <th class="px-6 py-4 font-medium text-white">Usuario</th>
                        <th class="px-6 py-4 font-medium text-white">Email</th>
                        <th class="px-6 py-4 font-medium text-white">Roles</th>
                        <th class="px-6 py-4 font-medium text-white">Registro</th>
                        <th class="px-6 py-4 font-medium text-white text-right">Acciones</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                    @forelse($users as $user)
                        <tr onclick="window.location='{{ route('users.show', $user) }}';" class="hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
                                             class="w-10 h-10 rounded-full object-cover"
                                             onerror="this.onerror=null;this.src='data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%2264%22%20height%3D%2264%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2064%2064%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_18d9b7b6b8f%20text%20%7B%20fill%3A%23ffffff%3Bfont-weight%3Abold%3Bfont-family%3AArial%2C%20Helvetica%2C%20Open%20Sans%2C%20sans-serif%2C%20monospace%3Bfont-size%3A10pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_18d9b7b6b8f%22%3E%3Crect%20width%3D%2264%22%20height%3D%2264%22%20fill%3D%22%232779be%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%2213.5546875%22%20y%3D%2236.5%22%3E64x64%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E';">
                                    </div>
                                    <div>
                                        <div class="font-medium text-white">{{ $user->name }}</div>
                                        <div class="text-sm text-slate-400">ID: {{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-white">{{ $user->email }}</div>
                            </td>
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
                                       class="p-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-blue-400 hover:text-blue-300 transition-colors"
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('users.manage.delete', $user->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-red-400 hover:text-red-300 transition-colors"
                                                title="Eliminar">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                <i class="fas fa-user-slash text-3xl mb-3"></i>
                                <p>No hay usuarios registrados</p>
                                <a href="{{ route('users.manage.create') }}" class="inline-block mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                    <i class="fas fa-plus mr-2"></i> Crear nuevo usuario
                                </a>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-slate-700">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
