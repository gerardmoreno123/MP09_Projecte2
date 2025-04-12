@extends('layouts.admin')

@section('admin-content')
    <div class="container mx-auto px-4">
        <!-- Header and Tabs -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <h1 class="text-2xl font-bold text-white mb-4 md:mb-0">
                <i class="fas fa-film text-blue-400 mr-2"></i>
                Gestión de Series
            </h1>

            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4 w-full md:w-auto">
                <a href="{{ route('series.manage.index') }}"
                   class="px-4 py-2 text-center font-medium rounded-lg {{ request()->routeIs('series.manage.index') ? 'bg-blue-600 text-white' : 'bg-slate-700 text-slate-300 hover:bg-slate-600' }}">
                    Listado
                </a>
                <a href="{{ route('series.manage.create') }}"
                   class="px-4 py-2 text-center font-medium rounded-lg {{ request()->routeIs('series.manage.create') ? 'bg-blue-600 text-white' : 'bg-slate-700 text-slate-300 hover:bg-slate-600' }}">
                    Nueva Serie
                </a>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="mb-8 bg-slate-800 p-4 rounded-xl shadow-md">
            <form action="{{ route('series.manage.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative">
                    <input type="text" name="search" placeholder="Buscar series..." value="{{ request('search') }}"
                           class="w-full pl-10 pr-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white">
                    <i class="fas fa-search absolute left-3 top-3 text-slate-400"></i>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Buscar</button>
            </form>
            @if(request('search'))
                <a href="{{ route('series.manage.index') }}" class="text-blue-400 hover:text-blue-600 mt-2 inline-block">
                    <i class="fas fa-times-circle mr-1"></i> Limpiar búsqueda
                </a>
            @endif
        </div>

        <!-- Series Table -->
        <div class="bg-slate-800 rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-700">
                    <tr>
                        <th class="px-6 py-4 font-medium text-white">Título</th>
                        <th class="px-6 py-4 font-medium text-white text-center">Videos</th>
                        <th class="px-6 py-4 font-medium text-white">Creada</th>
                        <th class="px-6 py-4 font-medium text-white text-right">Acciones</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                    @forelse($series as $serie)
                        <tr onclick="window.location='{{ route('series.manage.show', $serie) }}';" class="hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-4">
                                    @if($serie->image)
                                        <img src="{{ asset('storage/' . $serie->image) }}"
                                             alt="Imagen de la serie {{ $serie->title }}"
                                             class="w-8 h-8 rounded-lg shadow-lg">
                                    @else
                                        <div class="w-8 h-8 bg-gradient-to-r from-blue-900 to-slate-800 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-image text-4xl text-slate-500"></i>
                                            <span class="sr-only">Sin imagen</span>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-medium text-white">{{ $serie->title }}</div>
                                        @if($serie->description)
                                            <div class="text-sm text-slate-400 line-clamp-1">{{ $serie->description }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-900/30 text-blue-400">
                                    {{ $serie->videos->count() }} videos
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-400">
                                <div class="text-sm">{{ $serie->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs">{{ $serie->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('series.manage.edit', $serie->id) }}"
                                       class="p-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-blue-400 hover:text-blue-300 transition-colors"
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('series.manage.delete', $serie->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-red-400 hover:text-red-300 transition-colors"
                                                title="Eliminar">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                                <i class="fas fa-list-ol text-3xl mb-3"></i>
                                <p>No hay series creadas</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($series->hasPages())
                <div class="px-6 py-4 border-t border-slate-700">
                    {{ $series->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
