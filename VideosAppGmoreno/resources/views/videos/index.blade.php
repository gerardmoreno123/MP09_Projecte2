@extends('layouts.videos-app-layout')

@section('content')
    <div class="container mx-auto px-4 py-12">
        <!-- Hero Section -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-4">
                <span class="bg-gradient-to-r from-blue-500 to-emerald-500 bg-clip-text text-transparent">Explora los Mejores Videos</span>
            </h1>
            <p class="text-lg md:text-xl text-slate-300 max-w-3xl mx-auto">
                Descubre, comparte y disfruta de una amplia colección de videos en nuestra plataforma.
            </p>
        </div>

        <!-- Search and Create Button -->
        <div class="mb-8 bg-slate-800 p-4 rounded-xl shadow-md flex flex-col md:flex-row gap-4 items-center">
            <form action="{{ route('videos.index') }}" method="GET" class="flex-1 flex gap-4">
                <div class="relative flex-1">
                    <input type="text" name="search" placeholder="Buscar videos..." value="{{ request('search') }}"
                           class="w-full pl-10 pr-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg whitespace-nowrap">
                    Buscar
                </button>
            </form>
            @auth
                <a href="{{ route('videos.create') }}"
                   class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg flex items-center whitespace-nowrap">
                    <i class="fas fa-plus mr-2"></i> Crear Video
                </a>
            @endauth
            @if(request('search'))
                <a href="{{ route('videos.index') }}" class="text-blue-400 hover:text-blue-600 mt-2 md:mt-0 inline-block md:ml-4">
                    <i class="fas fa-times-circle mr-1"></i> Limpiar búsqueda
                </a>
            @endif
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-green-600 text-white p-4 rounded-lg flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Video Grid -->
        <div class="mb-12">
            <h2 class="text-3xl font-bold text-white mb-8 flex items-center">
                <i class="fas fa-fire text-orange-500 mr-3"></i>
                Todos los Videos
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($videos as $video)
                    <div class="bg-slate-800 rounded-xl overflow-hidden shadow-lg transition-transform duration-300 hover:shadow-xl hover:-translate-y-1 flex flex-col h-full">
                        <a href="{{ route('videos.show', $video->id) }}" class="block flex-grow">
                            <!-- Video Thumbnail -->
                            <div class="relative aspect-w-16 aspect-h-9">
                                @php
                                    preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $video->url, $matches);
                                    $videoId = $matches[1] ?? null;
                                @endphp

                                @if($videoId)
                                    <img src="https://img.youtube.com/vi/{{ $videoId }}/hqdefault.jpg"
                                         alt="{{ $video->title }}"
                                         class="w-full h-full object-cover transition-opacity duration-300 hover:opacity-90">
                                @else
                                    <img src="https://placehold.co/640x360?text=Video"
                                         alt="Default Thumbnail"
                                         class="w-full h-full object-cover transition-opacity duration-300 hover:opacity-90">
                                @endif

                                <div class="absolute inset-0 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300 bg-black bg-opacity-50">
                                    <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                                        <i class="fas fa-play text-xl text-white"></i>
                                    </div>
                                </div>
                                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-emerald-500"></div>
                            </div>

                            <!-- Video Info -->
                            <div class="p-4 flex-grow">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-lg font-semibold text-white line-clamp-2 flex-grow">{{ $video->title }}</h3>

                                    <!-- Action Buttons (only for owner or admin) -->
                                    @auth
                                        @if(auth()->id() === $video->user_id || auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('video-manager'))
                                            <div class="flex gap-2 ml-2">
                                                <a href="{{ route('videos.edit', $video->id) }}"
                                                   class="p-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center justify-center"
                                                   title="Editar">
                                                    <i class="fas fa-edit text-sm"></i>
                                                </a>
                                                <a href="{{ route('videos.delete', $video->id) }}"
                                                   class="p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors flex items-center justify-center"
                                                   title="Eliminar">
                                                    <i class="fas fa-trash-alt text-sm"></i>
                                                </a>
                                            </div>
                                        @endif
                                    @endauth
                                </div>

                                <p class="text-sm text-slate-400 mb-3 line-clamp-2">{{ $video->description ?? 'No hay descripción disponible.' }}</p>

                                <!-- Metadata -->
                                <div class="flex flex-wrap items-center gap-2 text-xs text-slate-400 mb-3">
                                    <span class="flex items-center">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        {{ $video->formatted_published_at }}
                                    </span>
                                    @if($video->serie)
                                        <a href="{{ route('series.show', $video->serie->id) }}"
                                           class="flex items-center text-blue-400 hover:underline">
                                            <i class="fas fa-list mr-1"></i>
                                            {{ $video->serie->title }}
                                        </a>
                                    @endif
                                </div>

                                <!-- Author Info -->
                                <a href="{{ route('users.show', $video->user->id) }}"
                                   class="flex items-center space-x-2 text-sm text-slate-400 hover:text-blue-400 transition-colors">
                                    <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white">
                                        {{ substr($video->user->name, 0, 1) }}
                                    </div>
                                    <span>{{ $video->user->name }}</span>
                                </a>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-video-slash text-5xl text-slate-600 mb-4"></i>
                        <p class="text-lg text-slate-400">No hay videos disponibles en este momento.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($videos->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $videos->links('pagination::tailwind') }}
                </div>
            @endif
        </div>
    </div>
@endsection
