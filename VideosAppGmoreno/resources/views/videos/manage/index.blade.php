@extends('layouts.admin')

@section('admin-content')
    <div class="container mx-auto px-4">
        <!-- Header and Navigation -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <h1 class="text-2xl font-bold text-white mb-4 md:mb-0">
                <i class="fas fa-video text-blue-400 mr-2"></i>
                Gestión de Videos
            </h1>

            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4 w-full md:w-auto">
                <a href="{{ route('videos.manage.index') }}"
                   class="px-4 py-2 text-center font-medium rounded-lg {{ request()->routeIs('videos.manage.index') ? 'bg-blue-600 text-white' : 'bg-slate-700 text-slate-300 hover:bg-slate-600' }}">
                    Listado
                </a>
                <a href="{{ route('videos.manage.create') }}"
                   class="px-4 py-2 text-center font-medium rounded-lg {{ request()->routeIs('videos.manage.create') ? 'bg-blue-600 text-white' : 'bg-slate-700 text-slate-300 hover:bg-slate-600' }}">
                    Nuevo Video
                </a>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="mb-8 bg-slate-800 p-4 rounded-xl shadow-md">
            <form action="{{ route('videos.manage.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative">
                    <input type="text" name="search" placeholder="Buscar videos..." value="{{ request('search') }}"
                           class="w-full pl-10 pr-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white">
                    <i class="fas fa-search absolute left-3 top-3 text-slate-400"></i>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Buscar</button>
            </form>
            @if(request('search'))
                <a href="{{ route('videos.manage.index') }}" class="text-blue-400 hover:text-blue-600 mt-2 inline-block">
                    <i class="fas fa-times-circle mr-1"></i> Limpiar búsqueda
                </a>
            @endif
        </div>

        <!-- Videos Table -->
        <div class="bg-slate-800 rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-700">
                    <tr>
                        <th class="px-6 py-4 font-medium text-white">Título</th>
                        <th class="px-6 py-4 font-medium text-white text-center">Serie</th>
                        <th class="px-6 py-4 font-medium text-white text-center">Publicado</th>
                        <th class="px-6 py-4 font-medium text-white text-right">Acciones</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                    @forelse($videos as $video)
                        <tr onclick="window.location='{{ route('videos.manage.show', $video) }}';" class="hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="w-8 h-8 bg-gradient-to-r from-blue-900 to-slate-800 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-video text-slate-500"></i>
                                        <span class="sr-only">Video</span>
                                    </div>
                                    <div>
                                        <div class="font-medium text-white">{{ $video->title }}</div>
                                        @if($video->description)
                                            <div class="text-sm text-slate-400 line-clamp-1">{{ $video->description }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-900/30 text-blue-400">
                                        {{ $video->serie ? $video->serie->title : 'Sin serie' }}
                                    </span>
                            </td>
                            <td class="px-6 py-4 text-slate-400 text-center">
                                <div class="text-sm">{{ $video->published_at ? $video->published_at->format('d/m/Y') : 'No publicado' }}</div>
                                @if($video->published_at)
                                    <div class="text-xs">{{ $video->published_at->diffForHumans() }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('videos.manage.edit', $video->id) }}"
                                       class="p-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-blue-400 hover:text-blue-300 transition-colors"
                                       title="Editar" onclick="event.stopPropagation();">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('videos.manage.delete', $video->id) }}"
                                       class="p-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-red-400 hover:text-red-300 transition-colors"
                                       title="Eliminar" onclick="event.stopPropagation();">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                                <i class="fas fa-video text-3xl mb-3"></i>
                                <p>No hay videos creados</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($videos->hasPages())
                <div class="px-6 py-4 border-t border-slate-700">
                    {{ $videos->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
