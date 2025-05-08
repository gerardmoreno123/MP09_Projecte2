@extends('layouts.admin')

@section('admin-content')
    <div class="container mx-auto px-4 py-12">
        <!-- Notifications -->
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

        <!-- Header and Navigation -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <h1 class="text-2xl font-bold text-white">
                <i class="fas fa-video text-blue-400 mr-2" aria-hidden="true"></i>
                Gestión de Videos
            </h1>

            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4 w-full md:w-auto">
                <a href="{{ route('videos.manage.index') }}"
                   class="btn {{ request()->routeIs('videos.manage.index') ? 'btn-primary' : 'btn-secondary' }}">
                    Listado
                </a>
                <a href="{{ route('videos.manage.create') }}"
                   class="btn {{ request()->routeIs('videos.manage.create') ? 'btn-primary' : 'btn-secondary' }}">
                    Nuevo Video
                </a>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="mb-8 bg-slate-800 p-4 rounded-xl shadow-md flex flex-col md:flex-row gap-4 items-center">
            <form action="{{ route('videos.manage.index') }}" method="GET" class="flex-1 flex gap-4">
                <div class="relative flex-1">
                    <input type="text" name="search" placeholder="Buscar videos..." value="{{ request('search') }}"
                           class="input input-primary"
                           aria-label="Buscar videos">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400" aria-hidden="true"></i>
                </div>
                <button type="submit" class="btn btn-primary">
                    Buscar
                </button>
            </form>
            @if(request('search'))
                <a href="{{ route('videos.manage.index') }}" class="btn btn-ghost" aria-label="Limpiar búsqueda">
                    <i class="fas fa-times-circle mr-1" aria-hidden="true"></i> Limpiar
                </a>
            @endif
        </div>

        <!-- Videos Table (Desktop) -->
        <div class="bg-slate-800 rounded-xl shadow-lg overflow-hidden">
            <div class="hidden sm:block">
                <table class="w-full text-left">
                    <thead class="bg-slate-700">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-medium text-white">Título</th>
                        <th scope="col" class="px-6 py-4 font-medium text-white text-center">Serie</th>
                        <th scope="col" class="px-6 py-4 font-medium text-white text-center">Publicado</th>
                        <th scope="col" class="px-6 py-4 font-medium text-white text-right">Acciones</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                    @forelse($videos as $video)
                        <tr onclick="window.location='{{ route('videos.manage.show', $video) }}';" class="hover:bg-slate-700/50 transition-colors cursor-pointer">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="w-8 h-8 bg-gradient-to-r from-blue-900 to-slate-800 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-video text-slate-500" aria-hidden="true"></i>
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
                                    {{ $video->serie?->title ?? 'Sin serie' }}
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
                                       class="btn btn-primary btn-sm"
                                       title="Editar video"
                                       aria-label="Editar video"
                                       onclick="event.stopPropagation();">
                                        <i class="fas fa-edit text-sm" aria-hidden="true"></i>
                                    </a>
                                    <a href="{{ route('videos.manage.delete', $video->id) }}"
                                       class="btn btn-danger btn-sm"
                                       title="Eliminar video"
                                       aria-label="Eliminar video">
                                        <i class="fas fa-trash-alt text-sm" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                <div class="bg-slate-800 rounded-xl">
                                    <i class="fas fa-list-ol text-5xl mb-4" aria-hidden="true"></i>
                                    <h3 class="text-xl font-medium text-white mb-2">No hay videos creados</h3>
                                    <p class="text-slate-400">Aún no se han creado videos en la plataforma.</p>
                                    <a href="{{ route('videos.manage.create') }}"
                                       class="inline-block mt-4 btn btn-primary">
                                        <i class="fas fa-plus mr-2" aria-hidden="true"></i> Crear nuevo video
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Videos List (Mobile) -->
            <div class="block sm:hidden space-y-4 p-4">
                @forelse($videos as $video)
                    <div class="bg-slate-900 rounded-lg p-4 shadow-md hover:bg-slate-700/50 transition-colors cursor-pointer"
                         onclick="window.location='{{ route('videos.manage.show', $video) }}';">
                        <div class="flex items-center space-x-4 mb-3">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-900 to-slate-800 rounded-lg flex items-center justify-center">
                                <i class="fas fa-video text-slate-500" aria-hidden="true"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-medium text-white">{{ $video->title }}</div>
                                @if($video->description)
                                    <div class="text-sm text-slate-400 line-clamp-1 overflow-hidden text-ellipsis word-break break-all">{{ $video->description }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-400">Serie:</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-900/30 text-blue-400">
                                    {{ $video->serie?->title ?? 'Sin serie' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-400">Publicado:</span>
                                <div class="text-right text-sm text-slate-400">
                                    <div>{{ $video->published_at ? $video->published_at->format('d/m/Y') : 'No publicado' }}</div>
                                    @if($video->published_at)
                                        <div class="text-xs">{{ $video->published_at->diffForHumans() }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex justify-end space-x-2 mt-3">
                                <a href="{{ route('videos.manage.edit', $video->id) }}"
                                   class="btn btn-primary btn-sm"
                                   title="Editar video"
                                   aria-label="Editar video"
                                   onclick="event.stopPropagation();">
                                    <i class="fas fa-edit text-sm" aria-hidden="true"></i>
                                </a>
                                <a href="{{ route('videos.manage.delete', $video->id) }}"
                                   class="btn btn-danger btn-sm"
                                   title="Eliminar video"
                                   aria-label="Eliminar video">
                                    <i class="fas fa-trash-alt text-sm" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-slate-800 rounded-xl p-12 text-center">
                        <i class="fas fa-list-ol text-5xl text-slate-600 mb-4" aria-hidden="true"></i>
                        <h3 class="text-xl font-medium text-white mb-2">No hay videos creados</h3>
                        <p class="text-slate-400">Aún no se han creado videos en la plataforma.</p>
                        <a href="{{ route('videos.manage.create') }}"
                           class="inline-block mt-4 btn btn-primary">
                            <i class="fas fa-plus mr-2" aria-hidden="true"></i> Crear nuevo video
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($videos->hasPages())
                <div class="px-6 py-4 border-t border-slate-700 flex justify-center">
                    {{ $videos->links('vendor.pagination.custom-tailwind') }}
                </div>
            @endif
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
            color: #d1d5db;
        }

        .btn-secondary:hover {
            background-color: #374151;
        }

        .btn-ghost {
            color: #3b82f6;
            background-color: transparent;
        }

        .btn-ghost:hover {
            color: #2563eb;
            background-color: rgba(255, 255, 255, 0.1);
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

        /* Input Styles */
        .input {
            width: 100%;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            background-color: #334155;
            border: 1px solid #475569;
            color: white;
            transition: border-color 0.3s ease;
        }

        .input-primary {
            padding-left: 2.5rem;
        }

        .input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
        }

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
        document.addEventListener('DOMContentLoaded', () => {
            const notifications = document.querySelectorAll('.notification');
            notifications.forEach(notification => {
                setTimeout(() => {
                    notification.classList.add('show');
                }, 100);

                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => notification.remove(), 300);
                }, 3000);

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
