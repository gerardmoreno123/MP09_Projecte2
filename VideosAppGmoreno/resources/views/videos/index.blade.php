@extends('layouts.videos-app-layout')

@section('content')
    <div class="container mx-auto px-4 py-12">
        <!-- Notifications Container -->
        <div id="notifications" class="fixed bottom-4 right-4 z-50">
            @if(session('success'))
                <div class="notification success show">
                    <span>{{ session('success') }}</span>
                    <i class="fas fa-times close-btn"></i>
                </div>
            @endif
            @if(session('error'))
                <div class="notification error show">
                    <span>{{ session('error') }}</span>
                    <i class="fas fa-times close-btn"></i>
                </div>
            @endif
        </div>

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
                <button type="submit" class="btn btn-primary">
                    Buscar
                </button>
            </form>
            @auth
                <a href="{{ route('videos.create') }}" class="btn btn-secondary">
                    <i class="fas fa-plus mr-2"></i> Crear Video
                </a>
            @endauth
            @if(request('search'))
                <a href="{{ route('videos.index') }}" class="text-blue-400 hover:text-blue-600 mt-2 md:mt-0 inline-block md:ml-4">
                    <i class="fas fa-times-circle mr-1"></i> Limpiar búsqueda
                </a>
            @endif
        </div>

        <!-- Video Grid -->
        <div class="mb-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($videos as $video)
                    <div class="bg-slate-800 rounded-xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-1 flex flex-col h-full">
                        <a href="{{ route('videos.show', $video->id) }}" class="block flex-grow">
                            <!-- Video Thumbnail -->
                            <div class="relative aspect-w-16 aspect-h-9">
                                @php
                                    preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $video->url, $matches);
                                    $videoId = $matches[1] ?? null;
                                @endphp

                                @if($videoId)
                                    <img src="https://img.youtube.com/vi/{{ $videoId }}/hqdefault.jpg"
                                         alt="Miniatura del vídeo {{ $video->title }}"
                                         class="w-full h-full object-cover transition-opacity duration-300 hover:opacity-90">
                                @else
                                    <img src="https://placehold.co/640x360?text=Video"
                                         alt="Miniatura por defecto"
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
                            <div class="p-5 flex-grow">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-lg font-semibold text-white line-clamp-2 flex-grow">{{ $video->title }}</h3>

                                    <!-- Action Buttons (only for owner or admin) -->
                                    @auth
                                        @if(auth()->user()->name === $video->user_name || auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('video-manager'))
                                            <div class="flex gap-2 ml-2">
                                                <a href="{{ route('videos.edit', $video->id) }}"
                                                   class="btn btn-primary btn-sm"
                                                   title="Editar">
                                                    <i class="fas fa-edit text-sm"></i>
                                                </a>
                                                <a href="{{ route('videos.delete', $video->id) }}"
                                                   class="btn btn-danger btn-sm"
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
                    <div class="col-span-full bg-slate-800 rounded-xl p-12 text-center">
                        <i class="fas fa-photo-film text-5xl text-slate-600 mb-4"></i>
                        <h3 class="text-xl font-medium text-white mb-2">No hay videos disponibles</h3>
                        <p class="text-slate-400 mb-4">Aún no se han creado videos en la plataforma.</p>
                        @auth
                            @if(auth()->user()->hasAnyRole(['video-manager', 'super-admin']))
                                <a href="{{ route('videos.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus mr-2"></i> Crear nuevo video
                                </a>
                            @endif
                        @endauth
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($videos->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $videos->links('vendor.pagination.custom-tailwind') }}
                </div>
            @endif
        </div>
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

        /* Notification styles */
        .notification {
            position: relative;
            padding: 1rem;
            border-radius: 0.5rem;
            color: white;
            margin-bottom: 0.5rem;
            opacity: 0;
            transform: translateX(100%);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .notification.show {
            opacity: 1;
            transform: translateX(0);
        }

        .notification.success {
            background-color: #10b981;
        }

        .notification.error {
            background-color: #ef4444;
        }

        .notification .close-btn {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            cursor: pointer;
        }
    </style>

    <script>
        // Notification handling
        document.addEventListener('DOMContentLoaded', () => {
            const notifications = document.querySelectorAll('.notification');
            notifications.forEach(notification => {
                // Show notification
                setTimeout(() => {
                    notification.classList.add('show');
                }, 100);

                // Auto-hide after 3 seconds
                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => notification.remove(), 300);
                }, 3000);

                // Close button
                const closeBtn = notification.querySelector('.close-btn');
                if (closeBtn) {
                    closeBtn.addEventListener('click', () => {
                        notification.classList.remove('show');
                        setTimeout(() => notification.remove(), 300);
                    });
                }
            });
        });
    </script>
@endsection
