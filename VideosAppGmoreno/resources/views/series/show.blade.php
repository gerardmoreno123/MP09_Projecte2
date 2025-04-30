@extends('layouts.videos-app-layout')

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
        <!-- Breadcrumbs -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('series.index') }}" class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-blue-400 transition-colors">
                        <i class="fas fa-list mr-2"></i>
                        Series
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-xs text-slate-500 mx-2"></i>
                        <span class="text-sm font-medium text-blue-400">{{ Str::limit($serie->title, 30) }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Serie Header -->
        <div class="bg-slate-800 rounded-xl shadow-2xl overflow-hidden mb-8">
            <!-- Serie Cover Image -->
            <div class="relative h-64 sm:h-80 w-full">
                @if($serie->image)
                    <img src="{{ asset('storage/' . $serie->image) }}"
                         alt="Imagen de la serie {{ $serie->title }}"
                         class="w-full h-full object-cover"
                         onerror="this.onerror=null;this.src='https://via.placeholder.com/1200x400?text=Sin+Imagen';">
                @else
                    <div class="w-full h-full bg-gradient-to-r from-blue-900 to-slate-800 flex items-center justify-center">
                        <i class="fas fa-photo-film text-4xl text-slate-500"></i>
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/50 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between">
                        <div>
                            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-2">{{ $serie->title }}</h1>
                            <div class="flex items-center space-x-4 text-sm">
                                <span class="bg-blue-600 text-white px-3 py-1 rounded-full font-medium">
                                    {{ $serie->videos->count() }} videos
                                </span>
                                <span class="bg-slate-600 text-white px-3 py-1 rounded-full font-medium">
                                    Creada {{ $serie->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        @auth
                            @if(auth()->user()->name === $serie->user_name || auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('serie-manager'))
                                <div class="flex gap-2 mt-4 md:mt-0">
                                    <a href="{{ route('series.edit', $serie->id) }}"
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                        <i class="fas fa-edit mr-2"></i>
                                        Editar Serie
                                    </a>
                                    <a href="{{ route('series.delete', $serie->id) }}"
                                       class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                                        <i class="fas fa-trash-alt mr-2"></i>
                                        Eliminar Serie
                                    </a>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Serie Description -->
            <div class="p-6 sm:p-8">
                @if($serie->description)
                    <div class="prose prose-invert max-w-none mb-6">
                        <h2 class="text-xl font-semibold text-white mb-3">Acerca de esta serie</h2>
                        <p class="text-slate-300 leading-relaxed">{{ $serie->description }}</p>
                    </div>
                @endif

                <!-- Author Info -->
                    <div class="flex items-center mt-3 space-x-2">
                        @if($serie->user_photo_url)
                            <img src="{{ $serie->user_photo_url }}" alt="{{ $serie->user_name }}"
                                 class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center text-white"
                                 onerror="this.onerror=null;this.src='data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%2264%22%20height%3D%2264%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2064%2064%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_18d9b7b6b8f%20text%20%7B%20fill%3A%23ffffff%3Bfont-weight%3Abold%3Bfont-family%3AArial%2C%20Helvetica%2C%20Open%20Sans%2C%20sans-serif%2C%20monospace%3Bfont-size%3A10pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_18d9b7b6b8f%22%3E%3Crect%20width%3D%2264%22%20height%3D%2264%22%20fill%3D%22%232779be%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%2213.5546875%22%20y%3D%2236.5%22%3E64x64%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E';">
                        @else
                            <div class="w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs">
                                {{ substr($serie->user_name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <p class="text-sm font-medium text-white">Creada por</p>
                            <p class="text-sm text-slate-400">{{ $serie->user_name }}</p>
                        </div>
                    </div>
            </div>
        </div>

        <!-- Videos Section -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold flex items-center">
                    <i class="fas fa-video text-blue-400 mr-3"></i>
                    <span>Videos en esta serie</span>
                </h2>
                <span class="bg-slate-700 text-white px-3 py-1 rounded-full text-sm">
                    {{ $serie->videos->count() }} videos
                </span>
            </div>

            @if($serie->videos->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($serie->videos as $video)
                        <div class="bg-slate-800 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-transform hover:-translate-y-1">
                            <a href="{{ route('videos.show', $video->id) }}" class="block">
                                <!-- Video Thumbnail -->
                                <div class="relative aspect-w-16 aspect-h-9">
                                    @php
                                        preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $video->url, $matches);
                                        $videoId = $matches[1] ?? null;
                                    @endphp

                                    @if($videoId)
                                        <img src="https://img.youtube.com/vi/{{ $videoId }}/mqdefault.jpg"
                                             alt="{{ $video->title }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <img src="https://via.placeholder.com/640x360"
                                             alt="Default Thumbnail"
                                             class="w-full h-full object-cover">
                                    @endif
                                    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30">
                                        <i class="fas fa-play text-3xl text-white opacity-80"></i>
                                    </div>
                                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-emerald-500"></div>
                                </div>

                                <!-- Video Info -->
                                <div class="p-4">
                                    <h3 class="font-semibold text-white mb-1 line-clamp-2">{{ $video->title }}</h3>
                                    <div class="flex justify-between items-center mt-3 text-xs text-slate-400">
                                        <span class="flex items-center">
                                            <i class="far fa-calendar-alt mr-1"></i>
                                            {{ $video->published_at ? $video->published_at->format('d/m/Y') : 'No publicado' }}
                                        </span>
                                        @auth
                                            @if(auth()->user()->hasAnyRole('serie-manager', 'super-admin') || auth()->id() === $video->user_id)
                                                <div class="flex gap-2">
                                                    <a href="{{ route('videos.edit', $video->id) }}"
                                                       class="text-blue-400 hover:text-blue-300"
                                                       title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('videos.delete', $video->id) }}"
                                                       class="text-red-400 hover:text-red-300"
                                                       title="Eliminar">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </div>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-slate-800 rounded-xl p-12 text-center">
                    <i class="fas fa-video-slash text-5xl text-slate-600 mb-4"></i>
                    <h3 class="text-xl font-medium text-white mb-2">Esta serie no tiene videos</h3>
                    <p class="text-slate-400 mb-6">Aún no se han añadido videos a esta serie.</p>
                    @auth
                        @if(auth()->user()->name === $serie->user_name)
                            <a href="{{ route('videos.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                <i class="fas fa-plus mr-2"></i>
                                Añadir video
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>

        <!-- Back Button -->
        <div class="text-center">
            <a href="{{ route('series.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver a todas las series
            </a>
        </div>
    </div>
@endsection
