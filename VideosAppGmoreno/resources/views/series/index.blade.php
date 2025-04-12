@extends('layouts.videos-app-layout')

@section('content')
    <div class="container mx-auto px-4 py-10">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                <span class="gradient-text">Nuestras Series</span>
            </h1>
            <p class="text-lg text-slate-300 max-w-2xl mx-auto">
                Descubre colecciones organizadas de videos sobre tus temas favoritos
            </p>
        </div>

        <!-- Search and Filter -->
        <div class="mb-8 bg-slate-800 p-4 rounded-xl shadow-md">
            <form action="{{ route('series.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative">
                    <input type="text" name="search" placeholder="Buscar series..." value="{{ request('search') }}"
                           class="w-full pl-10 pr-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white">
                    <i class="fas fa-search absolute left-3 top-3 text-slate-400"></i>
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    Buscar
                </button>
                @if(request('search'))
                    <a href="{{ route('series.index') }}" class="px-4 py-2 text-slate-400 hover:text-blue-400 transition-colors flex items-center">
                        <i class="fas fa-times mr-2"></i> Limpiar
                    </a>
                @endif
            </form>
        </div>

        <!-- Series Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($series as $serie)
                <div class="bg-slate-800 rounded-xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <a href="{{ route('series.show', $serie->id) }}" class="block">
                        <!-- Serie Thumbnail -->
                        <div class="relative h-48 w-full">
                            @if($serie->image)
                                <img src="{{ asset('storage/' . $serie->image) }}"
                                     alt="Imagen de la serie {{ $serie->title }}"
                                     class="w-full max-w-md rounded-lg shadow-lg">
                            @else
                                <div class="w-full h-full bg-gradient-to-r from-blue-900 to-slate-800 flex items-center justify-center">
                                    <i class="fas fa-photo-film text-4xl text-slate-500"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Serie Info -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-white mb-2">{{ $serie->title }}</h3>
                            @if($serie->description)
                                <p class="text-slate-400 text-sm line-clamp-2">{{ $serie->description }}</p>
                            @endif
                            <!-- Author Info -->
                            <div class="flex items-center mt-3 space-x-2">
                                @if($serie->user_photo_url)
                                    <img src="{{ $serie->user_photo_url }}" alt="{{ $serie->user_name }}"
                                         class="w-6 h-6 rounded-full object-cover"
                                         onerror="this.onerror=null;this.src='data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%2264%22%20height%3D%2264%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2064%2064%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_18d9b7b6b8f%20text%20%7B%20fill%3A%23ffffff%3Bfont-weight%3Abold%3Bfont-family%3AArial%2C%20Helvetica%2C%20Open%20Sans%2C%20sans-serif%2C%20monospace%3Bfont-size%3A10pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_18d9b7b6b8f%22%3E%3Crect%20width%3D%2264%22%20height%3D%2264%22%20fill%3D%22%232779be%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%2213.5546875%22%20y%3D%2236.5%22%3E64x64%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E';">
                                @else
                                    <div class="w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs">
                                        {{ substr($serie->user_name, 0, 1) }}
                                    </div>
                                @endif
                                <span class="text-xs text-slate-400">{{ $serie->user_name }}</span>
                            </div>
                            <div class="flex items-center mt-3 space-x-2">
                                <span class="bg-blue-600 bg-opacity-90 text-white px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $serie->videos_count }} videos
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Empty State -->
        @if($series->isEmpty())
            <div class="bg-slate-800 rounded-xl p-12 text-center">
                <i class="fas fa-photo-film text-5xl text-slate-600 mb-4"></i>
                <h3 class="text-xl font-medium text-white mb-2">No hay series disponibles</h3>
                <p class="text-slate-400 mb-4">Aún no se han creado series en la plataforma.</p>
                @auth
                    @if(auth()->user()->hasAnyRole('video-manager', 'super-admin'))
                        <a href="{{ route('series.manage.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Crear nueva serie
                        </a>
                    @endif
                @endauth
            </div>
        @endif

        <!-- Pagination -->
        @if($series->hasPages())
            <div class="mt-10 flex justify-center">
                {{ $series->links() }}
            </div>
        @endif
    </div>
@endsection
