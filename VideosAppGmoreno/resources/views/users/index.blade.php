@extends('layouts.videos-app-layout')

@section('content')
    <div class="container mx-auto px-4 py-12">
        <!-- Header Section -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-4">
                <span class="bg-gradient-to-r from-blue-500 to-emerald-500 bg-clip-text text-transparent">Nuestra Comunidad</span>
            </h1>
            <p class="text-lg md:text-xl text-slate-300 max-w-3xl mx-auto">
                Conoce a los miembros que forman parte de nuestra plataforma
            </p>
        </div>

        <!-- Search and Filter -->
        <div class="mb-8 bg-slate-800 p-4 rounded-xl shadow-md flex flex-col md:flex-row gap-4 items-center">
            <form action="{{ route('users.index') }}" method="GET" class="flex-1 flex gap-4">
                <div class="relative flex-1">
                    <input type="text" name="search" placeholder="Buscar usuarios..." value="{{ request('search') }}"
                           class="input input-primary"
                           aria-label="Buscar usuarios">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                </div>
                <button type="submit" class="btn btn-primary">
                    Buscar
                </button>
            </form>
            @if(request('search'))
                <a href="{{ route('users.index') }}" class="btn btn-ghost" aria-label="Limpiar búsqueda">
                    <i class="fas fa-times-circle mr-1"></i> Limpiar
                </a>
            @endif
        </div>

        <!-- Users Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($users as $user)
                <div class="bg-slate-800 rounded-xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <a href="{{ route('users.show', $user->id) }}" class="block">
                        <!-- User Avatar -->
                        <div class="relative pt-6 px-6">
                            <div class="w-32 h-32 mx-auto rounded-full border-4 border-blue-500 p-1">
                                <img src="{{ $user->profile_photo_url }}" alt="Foto de {{ $user->name }}"
                                     class="w-full h-full object-cover rounded-full"
                                     onerror="this.onerror=null;this.src='data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%2264%22%20height%3D%2264%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2064%2064%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_18d9b7b6b8f%20text%20%7B%20fill%3A%23ffffff%3Bfont-weight%3Abold%3Bfont-family%3AArial%2C%20Helvetica%2C%20Open%20Sans%2C%20sans-serif%2C%20monospace%3Bfont-size%3A10pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_18d9b7b6b8f%22%3E%3Crect%20width%3D%2264%22%20height%3D%2264%22%20fill%3D%22%232779be%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%2213.5546875%22%20y%3D%2236.5%22%3E64x64%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E';">
                            </div>
                        </div>

                        <!-- User Info -->
                        <div class="p-6 text-center">
                            <h3 class="text-xl font-bold text-white mb-1">{{ $user->name }}</h3>
                            <p class="text-slate-400 text-sm mb-4 line-clamp-1">{{ $user->email }}</p>

                            <!-- Stats -->
                            <div class="flex justify-center space-x-6 text-center mb-4">
                                <div>
                                    <p class="text-white font-bold">{{ $user->videos_count }}</p>
                                    <p class="text-slate-400 text-xs">Videos</p>
                                </div>
                                @if(isset($user->series_count))
                                    <div>
                                        <p class="text-white font-bold">{{ $user->series_count }}</p>
                                        <p class="text-slate-400 text-xs">Series</p>
                                    </div>
                                @endif
                            </div>

                            <!-- View Profile Button -->
                            <button class="btn btn-primary w-full" aria-label="Ver perfil de {{ $user->name }}">
                                Ver perfil
                            </button>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-span-full bg-slate-800 rounded-xl p-12 text-center">
                    <i class="fas fa-users text-5xl text-slate-600 mb-4"></i>
                    <h3 class="text-xl font-medium text-white mb-2">No se encontraron usuarios</h3>
                    <p class="text-slate-400 mb-4">No hay usuarios que coincidan con tu búsqueda.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $users->links('vendor.pagination.custom-tailwind') }}
            </div>
        @endif
    </div>

    <style>
        /* Button styles */
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

        .btn-ghost {
            color: #3b82f6;
            background-color: transparent;
        }

        .btn-ghost:hover {
            color: #2563eb;
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Input styles */
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
