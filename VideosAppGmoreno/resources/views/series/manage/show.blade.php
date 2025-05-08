@extends('layouts.admin')

@section('admin-content')
    <div class="container mx-auto px-4 py-12">
        <!-- Breadcrumbs -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('series.manage.index') }}" class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-blue-400 transition-colors"
                       aria-label="Volver a la lista de series">
                        <i class="fas fa-list mr-2" aria-hidden="true"></i>
                        Series
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-xs text-slate-500 mx-2" aria-hidden="true"></i>
                        <span class="text-sm font-medium text-blue-400">{{ Str::limit($serie->title, 30) }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Serie Header -->
        <div class="bg-slate-800 rounded-xl shadow-xl overflow-hidden mb-6">
            <div class="p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                    <h1 class="text-3xl font-bold text-white">{{ $serie->title }}</h1>
                    <div class="flex space-x-3 mt-4 md:mt-0">
                        <a href="{{ route('series.manage.edit', $serie->id) }}"
                           class="btn btn-primary flex items-center"
                           aria-label="Editar serie">
                            <i class="fas fa-edit mr-2" aria-hidden="true"></i> Editar
                        </a>
                        <a href="{{ route('series.manage.delete', $serie->id) }}"
                           class="btn btn-danger flex items-center"
                           aria-label="Eliminar serie">
                            <i class="fas fa-trash-alt mr-2" aria-hidden="true"></i> Eliminar
                        </a>
                    </div>
                </div>

                <!-- Serie Info -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-6">
                    <div class="card-info">
                        <h3 class="text-sm font-medium text-slate-400 mb-2">Fecha de creación</h3>
                        <p class="text-white">{{ $serie->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="card-info">
                        <h3 class="text-sm font-medium text-slate-400 mb-2">Total de videos</h3>
                        <p class="text-white">{{ $serie->videos->count() }}</p>
                    </div>
                    <div class="card-info">
                        <h3 class="text-sm font-medium text-slate-400 mb-2">Última actualización</h3>
                        <p class="text-white">{{ $serie->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <!-- Author Info -->
                <div class="card-info mb-6">
                    <h3 class="text-sm font-medium text-slate-400 mb-2">Creador</h3>
                    <div class="flex items-center space-x-3">
                        @if($serie->user_photo_url)
                            <img src="{{ $serie->user_photo_url }}" alt="{{ $serie->user_name }}"
                                 class="w-10 h-10 rounded-full"
                                 aria-label="Foto de {{ $serie->user_name }}">
                        @else
                            <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white">
                                {{ substr($serie->user_name, 0, 1) }}
                            </div>
                        @endif
                        <span class="text-white">{{ $serie->user_name }}</span>
                    </div>
                </div>

                <!-- Description -->
                @if($serie->description)
                    <div class="card-info mb-6">
                        <h3 class="text-sm font-medium text-slate-400 mb-2">Descripción</h3>
                        <p class="text-white whitespace-pre-line">{{ $serie->description }}</p>
                    </div>
                @endif

                <!-- Serie Image -->
                <div class="card-info">
                    <h3 class="text-sm font-medium text-slate-400 mb-2">Imagen destacada</h3>
                    @if($serie->image)
                        <img src="{{ asset('storage/' . $serie->image) }}"
                             alt="Imagen de la serie {{ $serie->title }}"
                             class="w-full max-w-xs md:max-w-md rounded-lg shadow-lg"
                             aria-label="Imagen destacada de {{ $serie->title }}">
                    @else
                        <div class="w-full max-w-xs md:max-w-md h-48 bg-gradient-to-r from-blue-900 to-slate-800 rounded-lg flex items-center justify-center">
                            <i class="fas fa-image text-4xl text-slate-500" aria-hidden="true"></i>
                            <span class="sr-only">Sin imagen</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Videos Section -->
        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <h2 class="text-2xl font-bold flex items-center">
                    <i class="fas fa-video text-blue-400 mr-3" aria-hidden="true"></i>
                    <span>Videos en esta serie</span>
                </h2>
                <span class="bg-slate-700 text-white px-3 py-1 rounded-full text-sm">
                    {{ $serie->videos->count() }} videos
                </span>
            </div>

            @if($serie->videos->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                    @foreach($serie->videos as $video)
                        <div class="bg-slate-800 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-transform hover:-translate-y-1">
                            <a href="{{ route('videos.manage.show', $video->id) }}" class="block">
                                <!-- Video Thumbnail -->
                                <div class="relative aspect-w-16 aspect-h-9">
                                    @php
                                        preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $video->url, $matches);
                                        $videoId = $matches[1] ?? null;
                                    @endphp

                                    @if($videoId)
                                        <img src="https://img.youtube.com/vi/{{ $videoId }}/mqdefault.jpg"
                                             alt="{{ $video->title }}"
                                             class="w-full h-full object-cover"
                                             aria-label="Miniatura de {{ $video->title }}">
                                    @else
                                        <img src="https://via.placeholder.com/640x360"
                                             alt="Default Thumbnail"
                                             class="w-full h-full object-cover"
                                             aria-label="Miniatura predeterminada">
                                    @endif
                                    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30">
                                        <i class="fas fa-play text-3xl text-white opacity-80" aria-hidden="true"></i>
                                    </div>
                                </div>

                                <!-- Video Info -->
                                <div class="p-4">
                                    <h3 class="font-semibold text-white mb-1 line-clamp-2">{{ $video->title }}</h3>
                                    <div class="flex justify-between items-center mt-2 text-xs text-slate-500">
                                        <span>{{ $video->published_at ? $video->published_at->format('d/m/Y') : 'No publicado' }}</span>
                                        <a href="{{ route('videos.manage.edit', $video->id) }}" class="text-blue-400 hover:text-blue-300"
                                           aria-label="Editar video {{ $video->title }}">
                                            <i class="fas fa-edit" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-slate-800 rounded-xl p-12 text-center">
                    <i class="fas fa-video-slash text-5xl text-slate-600 mb-4" aria-hidden="true"></i>
                    <h3 class="text-xl font-medium text-white mb-2">Esta serie no tiene videos</h3>
                    <p class="text-slate-400 mb-6">Aún no se han añadido videos a esta serie.</p>
                    @auth
                        @if(auth()->user()->hasAnyRole('video-manager', 'serie-manager', 'super-admin'))
                            <a href="{{ route('videos.manage.create') }}" class="btn btn-primary"
                               aria-label="Añadir video a la serie">
                                <i class="fas fa-plus mr-2" aria-hidden="true"></i> Añadir video
                            </a>
                        @endif
                    @endauth
                </div>
                @endforelse
        </div>

        <!-- Back Button -->
        <div class="text-center">
            <a href="{{ route('series.manage.index') }}" class="btn btn-secondary"
               aria-label="Volver al listado de series">
                <i class="fas fa-arrow-left mr-2" aria-hidden="true"></i>
                Volver al listado de series
            </a>
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
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-primary {
            background-color: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2563eb;
        }

        .btn-danger {
            background-color: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background-color: #dc2626;
        }

        .btn-secondary {
            background-color: #4b5563;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #374151;
        }

        /* Card Info Styles */
        .card-info {
            background-color: rgba(107, 114, 128, 0.5);
            padding: 1rem;
            border-radius: 0.5rem;
        }
    </style>
@endsection
