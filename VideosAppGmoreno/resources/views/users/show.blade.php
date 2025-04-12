@extends('layouts.videos-app-layout')

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-8">
        <!-- Breadcrumbs -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('users.index') }}" class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-blue-400 transition-colors">
                        <i class="fas fa-users mr-2"></i>
                        Usuarios
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-xs text-slate-500 mx-2"></i>
                        <span class="text-sm font-medium text-blue-400">{{ Str::limit($user->name, 20) }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Profile Header -->
        <div class="bg-slate-800 rounded-xl shadow-xl overflow-hidden mb-8">
            <div class="flex flex-col md:flex-row">
                <!-- Avatar Section -->
                <div class="md:w-1/3 p-6 flex flex-col items-center justify-center bg-gradient-to-b from-blue-900/30 to-slate-800">
                    <div class="relative">
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
                             class="w-40 h-40 object-cover rounded-full border-4 border-blue-500 shadow-lg">
                        <div class="absolute -bottom-2 -right-2 bg-emerald-500 rounded-full w-10 h-10 flex items-center justify-center border-2 border-slate-800">
                            <i class="fas fa-check text-white text-sm"></i>
                        </div>
                    </div>
                    <h1 class="text-2xl font-bold text-white mt-4">{{ $user->name }}</h1>
                    <p class="text-slate-400">{{ '@'.Str::slug($user->name) }}</p>
                </div>

                <!-- Info Section -->
                <div class="md:w-2/3 p-6">
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-white mb-2">Información del usuario</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-slate-700/50 p-3 rounded-lg">
                                <p class="text-slate-400 text-sm">Correo electrónico</p>
                                <p class="text-white font-medium">{{ $user->email }}</p>
                            </div>
                            <div class="bg-slate-700/50 p-3 rounded-lg">
                                <p class="text-slate-400 text-sm">Miembro desde</p>
                                <p class="text-white font-medium">{{ $user->created_at->format('d M Y') }}</p>
                            </div>
                            <div class="bg-slate-700/50 p-3 rounded-lg">
                                <p class="text-slate-400 text-sm">Videos publicados</p>
                                <p class="text-white font-medium">{{ $user->videos->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Videos Section -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold flex items-center">
                    <i class="fas fa-video text-blue-400 mr-3"></i>
                    <span>Videos publicados</span>
                </h2>
                <span class="bg-slate-700 text-white px-3 py-1 rounded-full text-sm">
                    {{ $user->videos->count() }} videos
                </span>
            </div>

            @if($user->videos->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($user->videos as $video)
                        <div class="bg-slate-800 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-transform hover:-translate-y-1">
                            <a href="{{ route('videos.show', $video->id) }}" class="block">
                                <!-- Video Thumbnail -->
                                <div class="relative aspect-w-16 aspect-h-9">
                                    @php
                                        preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $video->url, $matches);
                                        $videoId = $matches[1] ?? null;
                                    @endphp

                                    @if($videoId)
                                        <img src="https://img.youtube.com/vi/{{ $videoId }}/hqdefault.jpg"
                                             alt="{{ $video->title }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <img src="https://placehold.co/640x360?text=Video"
                                             alt="Default Thumbnail"
                                             class="w-full h-full object-cover">
                                    @endif
                                    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30 opacity-0 hover:opacity-100 transition-opacity">
                                        <i class="fas fa-play text-3xl text-white"></i>
                                    </div>
                                </div>

                                <!-- Video Info -->
                                <div class="p-4">
                                    <h3 class="font-semibold text-white mb-1 line-clamp-2">{{ $video->title }}</h3>
                                    <div class="flex justify-between items-center mt-2 text-xs text-slate-500">
                                        <span>{{ $video->formatted_published_at }}</span>
                                    </div>
                                </div>
                            </a>

                            <!-- Action Buttons -->
                            @auth
                                @if(auth()->id() === $user->id)
                                    <div class="p-4 pt-0 flex justify-end">
                                        <a href="{{ route('videos.edit', $video->id) }}"
                                           class="p-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('videos.delete', $video->id) }}"
                                           class="p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors ml-2"
                                           title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-slate-800 rounded-xl p-8 text-center">
                    <i class="fas fa-video-slash text-4xl text-slate-600 mb-4"></i>
                    <h3 class="text-xl font-medium text-white mb-2">Este usuario no tiene videos</h3>
                    <p class="text-slate-400">Parece que aún no ha publicado ningún contenido.</p>
                </div>
            @endif
        </div>

        <!-- Back Button -->
        <div class="text-center">
            <a href="{{ route('users.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver a la lista de usuarios
            </a>
        </div>
    </div>
@endsection
