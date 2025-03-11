@extends('layouts.videos-app-layout')

@section('content')
    <div class="flex flex-col md:flex-row">
        <aside class="w-full md:w-64 bg-gray-800 text-white p-4 md:fixed md:left-0 md:top-0 md:bottom-0 rounded shadow-lg flex flex-col">
            <div>
                <h2 class="text-lg font-bold mb-4 text-green-400">⚙️ CRUDS</h2>
                <ul class="space-y-2">
                    @if(auth()->user()->hasAnyRole('video-manager', 'super-admin'))
                        <li>
                            <a href="{{ route('videos.manage.index') }}"
                               class="block p-3 rounded-lg transition-all
                                      {{ request()->routeIs('videos.manage.*') ? 'bg-green-600 font-bold border-green-400' : 'hover:bg-gray-700' }}">
                                🎥 Videos
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->hasAnyRole('user-manager', 'super-admin'))
                        <li>
                            <a href="{{ route('users.manage.index') }}"
                               class="block p-3 rounded-lg transition-all
                                      {{ request()->routeIs('users.manage.*') ? 'bg-green-600 font-bold border-green-400' : 'hover:bg-gray-700' }}">
                                👤 Usuarios
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </aside>

        <main class="flex-1 p-6 md:ml-64">
            @yield('admin-content')
        </main>
    </div>
@endsection
