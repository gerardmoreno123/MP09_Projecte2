@extends('layouts.admin')

@section('admin-content')
    <div class="container mx-auto px-4 py-12">
        <!-- Breadcrumbs -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('videos.manage.index') }}" class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-blue-400 transition-colors"
                       aria-label="Volver a la lista de videos">
                        <i class="fas fa-video mr-2" aria-hidden="true"></i>
                        Videos
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-xs text-slate-500 mx-2" aria-hidden="true"></i>
                        <span class="text-sm font-medium text-blue-400">{{ Str::limit($video->title, 30) }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Video Header -->
        <div class="bg-slate-800 rounded-xl shadow-xl overflow-hidden mb-8">
            <div class="p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6">
                    <h1 class="text-3xl font-bold text-white mb-4 sm:mb-0">{{ $video->title }}</h1>
                    <div class="flex space-x-3">
                        <a href="{{ route('videos.manage.edit', $video->id) }}"
                           class="btn btn-primary flex items-center"
                           aria-label="Editar video">
                            <i class="fas fa-edit mr-2" aria-hidden="true"></i> Editar
                        </a>
                        <a href="{{ route('videos.manage.delete', $video->id) }}"
                           class="btn btn-danger flex items-center"
                           aria-label="Eliminar video">
                            <i class="fas fa-trash-alt mr-2" aria-hidden="true"></i> Eliminar
                        </a>
                    </div>
                </div>

                <!-- Video Info -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-4 sm:mb-6">
                    <div class="bg-slate-700/50 p-3 sm:p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-slate-400 mb-1 sm:mb-2">Fecha de publicación</h3>
                        <p class="text-white">{{ $video->published_at ? $video->published_at->format('d/m/Y H:i') : 'No publicado' }}</p>
                    </div>
                    <div class="bg-slate-700/50 p-3 sm:p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-slate-400 mb-1 sm:mb-2">Serie</h3>
                        <p class="text-white">{{ $video->serie ? $video->serie->title : 'Sin serie' }}</p>
                    </div>
                    <div class="bg-slate-700/50 p-3 sm:p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-slate-400 mb-1 sm:mb-2">Última actualización</h3>
                        <p class="text-white">{{ $video->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <!-- Author Info -->
                <div class="bg-slate-700/50 p-3 sm:p-4 rounded-lg mb-4 sm:mb-6">
                    <h3 class="text-sm font-medium text-slate-400 mb-1 sm:mb-2">Creador</h3>
                    <div class="flex items-center space-x-3">
                        @if($video->user->profile_photo_path)
                            <img src="{{ $video->user->profile_photo_url }}" alt="{{ $video->user->name }}" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full"
                                 aria-label="Foto de perfil de {{ $video->user->name }}">
                        @else
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-blue-500 flex items-center justify-center text-white">
                                {{ substr($video->user->name, 0, 1) }}
                            </div>
                        @endif
                        <span class="text-white">{{ $video->user->name }}</span>
                    </div>
                </div>

                <!-- Description -->
                @if($video->description)
                    <div class="bg-slate-700/50 p-3 sm:p-4 rounded-lg mb-4 sm:mb-6">
                        <h3 class="text-sm font-medium text-slate-400 mb-1 sm:mb-2">Descripción</h3>
                        <p class="text-white whitespace-pre-line">{{ $video->description }}</p>
                    </div>
                @endif

                <!-- URL -->
                <div class="bg-slate-700/50 p-3 sm:p-4 rounded-lg mb-4 sm:mb-6">
                    <h3 class="text-sm font-medium text-slate-400 mb-1 sm:mb-2">URL</h3>
                    <p class="text-white break-all">
                        <a href="{{ $video->url }}" class="text-blue-400 hover:underline" target="_blank" aria-label="Abrir URL externa">{{ $video->url }}</a>
                    </p>
                </div>

                <!-- URL APP -->
                <div class="bg-slate-700/50 p-3 sm:p-4 rounded-lg mb-4 sm:mb-6">
                    <h3 class="text-sm font-medium text-slate-400 mb-1 sm:mb-2">URL en la APP</h3>
                    <p class="text-white">
                        <a href="{{ route('videos.show', $video->id) }}" class="text-blue-400 hover:underline" target="_blank" aria-label="Ver video en la aplicación">Ver video</a>
                    </p>
                </div>

                <!-- Video Preview -->
                <div class="bg-slate-700/50 p-3 sm:p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-slate-400 mb-1 sm:mb-2">Vista previa</h3>
                    @php
                        preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $video->url, $matches);
                        $videoId = $matches[1] ?? null;
                    @endphp

                    @if($videoId)
                        <div class="relative aspect-video w-full">
                            <iframe src="https://www.youtube.com/embed/{{ $videoId }}"
                                    class="w-full h-full rounded-lg"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen
                                    aria-label="Vista previa del video"></iframe>
                        </div>
                    @else
                        <div class="w-full aspect-video bg-gradient-to-r from-blue-900 to-slate-800 rounded-lg flex items-center justify-center">
                            <i class="fas fa-video text-3xl sm:text-4xl text-slate-500" aria-hidden="true"></i>
                            <span class="sr-only">Sin vista previa</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="text-center mt-6">
            <a href="{{ route('videos.manage.index') }}" class="btn btn-secondary flex items-center"
               aria-label="Volver al listado de videos">
                <i class="fas fa-arrow-left mr-2" aria-hidden="true"></i>
                Volver al listado de videos
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
            color: white;
        }

        .btn-secondary:hover {
            background-color: #374151;
        }

        .btn-danger {
            background-color: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background-color: #dc2626;
        }
    </style>
@endsection
