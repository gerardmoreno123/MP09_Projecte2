@extends('layouts.admin')

@section('admin-content')
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Breadcrumbs -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('videos.manage.index') }}" class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-blue-400 transition-colors">
                        <i class="fas fa-video mr-2"></i>
                        Videos
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-xs text-slate-500 mx-2"></i>
                        <span class="text-sm font-medium text-blue-400">{{ Str::limit($video->title, 30) }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Video Header -->
        <div class="bg-slate-800 rounded-xl shadow-xl overflow-hidden mb-8">
            <div class="p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                    <h1 class="text-3xl font-bold text-white">{{ $video->title }}</h1>
                    <div class="flex space-x-3 mt-4 md:mt-0">
                        <a href="{{ route('videos.manage.edit', $video->id) }}"
                           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors flex items-center">
                            <i class="fas fa-edit mr-2"></i> Editar
                        </a>
                        <a href="{{ route('videos.manage.delete', $video->id) }}"
                           class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors flex items-center">
                            <i class="fas fa-trash-alt mr-2"></i> Eliminar
                        </a>
                    </div>
                </div>

                <!-- Video Info -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-slate-700/50 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-slate-400 mb-2">Fecha de publicación</h3>
                        <p class="text-white">{{ $video->published_at ? $video->published_at->format('d/m/Y H:i') : 'No publicado' }}</p>
                    </div>
                    <div class="bg-slate-700/50 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-slate-400 mb-2">Serie</h3>
                        <p class="text-white">{{ $video->serie ? $video->serie->title : 'Sin serie' }}</p>
                    </div>
                    <div class="bg-slate-700/50 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-slate-400 mb-2">Última actualización</h3>
                        <p class="text-white">{{ $video->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <!-- Author Info -->
                <div class="bg-slate-700/50 p-4 rounded-lg mb-6">
                    <h3 class="text-sm font-medium text-slate-400 mb-2">Creador</h3>
                    <div class="flex items-center space-x-3">
                        @if($video->user->profile_photo_path)
                            <img src="{{ $video->user->profile_photo_url }}" alt="{{ $video->user->name }}" class="w-10 h-10 rounded-full">
                        @else
                            <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white">
                                {{ substr($video->user->name, 0, 1) }}
                            </div>
                        @endif
                        <span class="text-white">{{ $video->user->name }}</span>
                    </div>
                </div>

                <!-- Description -->
                @if($video->description)
                    <div class="bg-slate-700/50 p-4 rounded-lg mb-6">
                        <h3 class="text-sm font-medium text-slate-400 mb-2">Descripción</h3>
                        <p class="text-white whitespace-pre-line">{{ $video->description }}</p>
                    </div>
                @endif

                <!-- URL -->
                <div class="bg-slate-700/50 p-4 rounded-lg mb-6">
                    <h3 class="text-sm font-medium text-slate-400 mb-2">URL</h3>
                    <p class="text-white break-words">
                        <a href="{{ $video->url }}" class="text-blue-400 hover:underline" target="_blank">{{ $video->url }}</a>
                    </p>
                </div>

                <!-- URL APP -->
                <div class="bg-slate-700/50 p-4 rounded-lg mb-6">
                    <h3 class="text-sm font-medium text-slate-400 mb-2">URL en la APP</h3>
                    <p class="text-white">
                        <a href="{{ route('videos.show', $video->id) }}" class="text-blue-400 hover:underline" target="_blank">Ver video</a>
                    </p>
                </div>

                <!-- Video Preview -->
                <div class="bg-slate-700/50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-slate-400 mb-2">Vista previa</h3>
                    @php
                        preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $video->url, $matches);
                        $videoId = $matches[1] ?? null;
                    @endphp

                    @if($videoId)
                        <div class="relative aspect-w-16 aspect-h-9">
                            <iframe src="https://www.youtube.com/embed/{{ $videoId }}"
                                    class="w-full h-full rounded-lg"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                        </div>
                    @else
                        <div class="w-full h-48 bg-gradient-to-r from-blue-900 to-slate-800 rounded-lg flex items-center justify-center">
                            <i class="fas fa-video text-4xl text-slate-500"></i>
                            <span class="sr-only">Sin vista previa</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="text-center">
            <a href="{{ route('videos.manage.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver al listado de videos
            </a>
        </div>
    </div>
@endsection
