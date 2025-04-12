@extends('layouts.videos-app-layout')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
        <!-- Breadcrumbs -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('videos.index') }}" class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-blue-400 transition-colors">
                        <i class="fas fa-home mr-2"></i>
                        Inicio
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

        <!-- Video Container -->
        <div class="bg-slate-800 rounded-xl shadow-2xl overflow-hidden">
            <!-- Video Player -->
            <div class="aspect-w-16 aspect-h-9 bg-black">
                @php
                    $videoUrl = $video->url;
                    if (strpos($videoUrl, '?') === false) {
                        $videoUrl .= '?autoplay=0&rel=0&modestbranding=1';
                    } else {
                        $videoUrl .= '&autoplay=0&rel=0&modestbranding=1';
                    }
                @endphp
                <iframe
                    width="100%"
                    height="450"
                    src="{{ $videoUrl }}"
                    title="{{ $video->title }}"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    referrerpolicy="strict-origin-when-cross-origin"
                    allowfullscreen
                    class="w-full h-[450px]">
                </iframe>
            </div>

            <!-- Video Info -->
            <div class="p-6 sm:p-8">
                <!-- Title and Metadata -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                    <h1 class="text-2xl md:text-4xl font-bold text-white mb-4 md:mb-0">{{ $video->title }}</h1>
                    <div class="flex items-center space-x-4 text-sm text-slate-400">
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
                </div>

                <!-- Description -->
                <div class="prose prose-invert max-w-none mb-6">
                    <p class="text-slate-200 leading-relaxed">{{ $video->description ?? 'No hay descripción disponible.' }}</p>
                </div>

                <!-- Author Info -->
                <a href="{{ route('users.show', $video->user->id) }}"
                   class="flex items-center space-x-4 mb-6 hover:bg-slate-700/50 p-2 rounded-lg transition-colors">
                    <div class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center text-white">
                        {{ substr($video->user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-white">Subido por</p>
                        <p class="text-sm text-slate-400">{{ $video->user->name }}</p>
                    </div>
                </a>

                <!-- Actions -->
                <div class="flex flex-wrap gap-4 pt-4 border-t border-slate-700">
                    <a href="{{ $video->url }}" target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Ver en YouTube
                    </a>
                    <button onclick="navigator.clipboard.writeText('{{ $video->url }}')"
                            class="inline-flex items-center px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white font-medium rounded-lg transition-colors">
                        <i class="fas fa-copy mr-2"></i>
                        Copiar enlace
                    </button>
                    @auth
                        @if(auth()->id() === $video->user_id)
                            <a href="{{ route('videos.edit', $video->id) }}"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                <i class="fas fa-edit mr-2"></i>
                                Editar Video
                            </a>
                            <a href="{{ route('videos.delete', $video->id) }}"
                               class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                                <i class="fas fa-trash-alt mr-2"></i>
                                Eliminar Video
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
@endsection
