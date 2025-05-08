@extends('layouts.videos-app-layout')

@section('content')
    <div class="container mx-auto px-4 py-12">
        <!-- Header Section -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-4">
                <span class="bg-gradient-to-r from-blue-500 to-emerald-500 bg-clip-text text-transparent">Nuestras Series</span>
            </h1>
            <p class="text-lg md:text-xl text-slate-300 max-w-3xl mx-auto">
                Descubre colecciones organizadas de videos sobre tus temas favoritos
            </p>
        </div>

        <!-- Search and Filter -->
        <div class="mb-8 bg-slate-800 p-4 rounded-xl shadow-md flex flex-col md:flex-row gap-4 items-center">
            <form action="{{ route('series.index') }}" method="GET" class="flex-1 flex gap-4">
                <div class="relative flex-1">
                    <input type="text" name="search" placeholder="Buscar series..." value="{{ request('search') }}"
                           class="w-full pl-10 pr-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white"
                           aria-label="Buscar series">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                </div>
                <button type="submit" class="btn btn-primary">
                    Buscar
                </button>
            </form>
            @auth
                <a href="{{ route('series.create') }}" class="btn btn-secondary">
                    <i class="fas fa-plus mr-2"></i> Crear Serie
                </a>
            @endauth
            @if(request('search'))
                <a href="{{ route('series.index') }}" class="text-blue-400 hover:text-blue-600 mt-2 md:mt-0 inline-block md:ml-4">
                    <i class="fas fa-times-circle mr-1"></i> Limpiar búsqueda
                </a>
            @endif
        </div>

        <!-- Series Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($series as $serie)
                <div class="bg-slate-800 rounded-xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-1 flex flex-col h-full">
                    <a href="{{ route('series.show', $serie->id) }}" class="block flex-grow">
                        <!-- Serie Thumbnail -->
                        <div class="relative h-48 w-full">
                            @if($serie->image)
                                <img src="{{ asset('storage/' . $serie->image) }}"
                                     alt="Imagen de la serie {{ $serie->title }}"
                                     class="w-full h-full object-cover transition-opacity duration-300 hover:opacity-90">
                            @else
                                <div class="w-full h-full bg-gradient-to-r from-blue-900 to-slate-800 flex items-center justify-center">
                                    <i class="fas fa-photo-film text-4xl text-slate-500"></i>
                                </div>
                            @endif
                            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-emerald-500"></div>
                        </div>

                        <!-- Serie Info -->
                        <div class="p-5 flex-grow">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-semibold text-white line-clamp-2 flex-grow">{{ $serie->title }}</h3>

                                <!-- Action Buttons (only for owner or specific roles) -->
                                @auth
                                    @if(auth()->id() === $serie->user_id || auth()->user()->hasAnyRole(['super-admin', 'serie-manager']))
                                        <div class="flex gap-2 ml-2">
                                            <a href="{{ route('series.edit', $serie->id) }}"
                                               class="btn btn-primary btn-sm"
                                               title="Editar serie"
                                               aria-label="Editar serie">
                                                <i class="fas fa-edit text-sm"></i>
                                            </a>
                                            <a href="{{ route('series.delete', $serie->id) }}"
                                               class="p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors flex items-center justify-center"
                                               title="Eliminar">
                                                <i class="fas fa-trash-alt text-sm"></i>
                                            </a>
                                        </div>
                                    @endif
                                @endauth
                            </div>

                            @if($serie->description)
                                <p class="text-sm text-slate-400 mb-3 line-clamp-2">{{ $serie->description }}</p>
                            @endif

                            <!-- Author Info -->
                            <div class="flex items-center space-x-2 text-sm text-slate-400 mb-3">
                                @if($serie->user_photo_url)
                                    <img src="{{ $serie->user_photo_url }}" alt="Foto de {{ $serie->user_name }}"
                                         class="w-6 h-6 rounded-full object-cover"
                                         onerror="this.onerror=null;this.src='data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%2264%22%20height%3D%2264%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2064%2064%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_18d9b7b6b8f%20text%20%7B%20fill%3A%23ffffff%3Bfont-weight%3Abold%3Bfont-family%3AArial%2C%20Helvetica%2C%20Open%20Sans%2C%20sans-serif%2C%20monospace%3Bfont-size%3A10pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_18d9b7b6b8f%22%3E%3Crect%20width%3D%2264%22%20height%3D%2264%22%20fill%3D%22%232779be%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%2213.5546875%22%20y%3D%2236.5%22%3E64x64%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E';">
                                @else
                                    <div class="w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs">
                                        {{ substr($serie->user_name, 0, 1) }}
                                    </div>
                                @endif
                                <span>{{ $serie->user_name }}</span>
                            </div>

                            <!-- Video Count -->
                            <div>
                                <span class="bg-blue-600 bg-opacity-90 text-white px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $serie->videos_count }} videos
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-span-full bg-slate-800 rounded-xl p-12 text-center">
                    <i class="fas fa-photo-film text-5xl text-slate-600 mb-4"></i>
                    <h3 class="text-xl font-medium text-white mb-2">No hay series disponibles</h3>
                    <p class="text-slate-400 mb-4">Aún no se han creado series en la plataforma.</p>
                    @auth
                        @if(auth()->user()->hasAnyRole(['serie-manager', 'super-admin']))
                            <a href="{{ route('series.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus mr-2"></i> Crear nueva serie
                            </a>
                        @endif
                    @endauth
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($series->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $series->links('vendor.pagination.custom-tailwind') }}
            </div>
        @endif
    </div>

    <style>
        /* Button styles (simulating a reusable component) */
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
            background-color: #10b981;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #059669;
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

        @media (max-width: 640px) {
            .btn-sm {
                padding: 0.25rem 0.75rem;
            }
        }
    </style>
@endsection
