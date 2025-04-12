@extends('layouts.videos-app-layout')

@section('content')
    <div class="flex flex-col md:flex-row">
        <!-- Sidebar -->
        <aside class="w-full md:w-64 bg-slate-800 text-white p-4 md:fixed md:left-0 md:top-16 md:bottom-0 md:overflow-y-auto z-40">
            <div class="mb-8">
                <h2 class="text-xl font-bold mb-6 flex items-center text-blue-400">
                    <i class="fas fa-cogs mr-2"></i>
                    Panel de Administración
                </h2>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard.index') }}"
                           class="flex items-center p-3 rounded-lg transition-all
                                  {{ request()->routeIs('dashboard.index') ? 'bg-blue-600 font-medium' : 'hover:bg-slate-700' }}">
                            <i class="fas fa-tachometer-alt mr-3"></i>
                            Dashboard
                        </a>
                    </li>
                    @if(auth()->user()->hasAnyRole('video-manager', 'super-admin'))
                        <li>
                            <a href="{{ route('videos.manage.index') }}"
                               class="flex items-center p-3 rounded-lg transition-all
                                      {{ request()->routeIs('videos.manage.*') ? 'bg-blue-600 font-medium' : 'hover:bg-slate-700' }}">
                                <i class="fas fa-video mr-3"></i>
                                Videos
                            </a>
                        </li>
                    @endif
                    @if(auth()->user()->hasAnyRole('serie-manager', 'super-admin'))
                        <li>
                            <a href="{{ route('series.manage.index') }}"
                               class="flex items-center p-3 rounded-lg transition-all
                                      {{ request()->routeIs('series.manage.*') ? 'bg-blue-600 font-medium' : 'hover:bg-slate-700' }}">
                                <i class="fas fa-film mr-3"></i>
                                Series
                            </a>
                        </li>
                    @endif
                    @if(auth()->user()->hasAnyRole('user-manager', 'super-admin'))
                        <li>
                            <a href="{{ route('users.manage.index') }}"
                               class="flex items-center p-3 rounded-lg transition-all
                                      {{ request()->routeIs('users.manage.*') ? 'bg-blue-600 font-medium' : 'hover:bg-slate-700' }}">
                                <i class="fas fa-users mr-3"></i>
                                Usuarios
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <div class="mt-auto pt-4 border-t border-slate-700">
                <a href="{{ route('videos.index') }}"
                   class="flex items-center p-3 text-slate-400 hover:text-white transition-colors">
                    <i class="fas fa-arrow-left mr-3"></i>
                    Volver al sitio
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 md:ml-64 bg-slate-900 min-h-screen">
            @yield('admin-content')
        </main>
    </div>
@endsection
