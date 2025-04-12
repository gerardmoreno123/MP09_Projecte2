@extends('layouts.videos-app-layout')

@section('content')
    <div class="container mx-auto px-4 py-10">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                <span class="gradient-text">Nuestra Comunidad</span>
            </h1>
            <p class="text-lg text-slate-300 max-w-2xl mx-auto">
                Conoce a los miembros que forman parte de nuestra plataforma
            </p>
        </div>

        <!-- Search and Filter -->
        <div class="mb-8 bg-slate-800 p-4 rounded-xl shadow-md">
            <form action="{{ route('users.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative">
                    <input type="text" name="search" placeholder="Buscar usuarios..." value="{{ request('search') }}"
                           class="w-full pl-10 pr-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white">
                    <i class="fas fa-search absolute left-3 top-3 text-slate-400"></i>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Buscar</button>
            </form>
            @if(request('search'))
                <a href="{{ route('users.index') }}" class="text-blue-400 hover:text-blue-600 mt-2 inline-block">
                    <i class="fas fa-times-circle mr-1"></i> Limpiar búsqueda
                </a>
            @endif
        </div>

        <!-- Users Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($users as $userItem)
                <div class="bg-slate-800 rounded-xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <a href="{{ route('users.show', $userItem->id) }}" class="block">
                        <!-- User Avatar -->
                        <div class="relative pt-6 px-6">
                            <div class="w-32 h-32 mx-auto rounded-full border-4 border-blue-500 p-1">
                                <img src="{{ $userItem->profile_photo_url }}" alt="{{ $userItem->name }}"
                                     class="w-full h-full object-cover rounded-full">
                            </div>
                        </div>

                        <!-- User Info -->
                        <div class="p-6 text-center">
                            <h3 class="text-xl font-bold text-white mb-1">{{ $userItem->name }}</h3>
                            <p class="text-slate-400 text-sm mb-4 truncate">{{ $userItem->email }}</p>

                            <!-- Stats -->
                            <div class="flex justify-center space-x-4 text-center mb-4">
                                <div>
                                    <p class="text-white font-bold">{{ $userItem->videos_count }}</p>
                                    <p class="text-slate-400 text-xs">Videos</p>
                                </div>
                            </div>

                            <!-- View Profile Button -->
                            <button class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                Ver perfil
                            </button>
                        </div>
                    </a>
                </div>
            @empty
                <p class="text-center text-slate-400 col-span-full">No se encontraron usuarios.</p>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="mt-10 flex justify-center">
                {{ $users->links() }}
            </div>
        @endif

    </div>
@endsection
