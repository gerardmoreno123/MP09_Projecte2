@extends('layouts.videos-app-layout')

@section('content')
    <!-- Mobile Navbar -->
    <nav class="md:hidden fixed top-14 z-40 left-0 right-0 bg-slate-800 text-white p-4 shadow-md">
        <div class="flex items-center justify-between">
            <button id="sidebar-toggle"
                    class="btn btn-ghost text-xl"
                    aria-label="Abrir menú de administración"
                    aria-expanded="false">
                <i id="sidebar-icon" class="fas fa-bars"></i>
            </button>
            <h2 class="text-lg font-bold text-blue-400 flex items-center">
                <i class="fas fa-cogs mr-2"></i>
                Panel de Administración
            </h2>
        </div>
    </nav>

    <div class="flex flex-col md:flex-row">
        <!-- Sidebar -->
        <aside id="sidebar"
               class="w-full md:w-64 bg-slate-800 text-white p-4 md:fixed md:left-0 md:top-16 md:bottom-0 md:overflow-y-auto z-40
                      fixed top-16 bottom-0 left-0 transform -translate-x-full md:translate-x-0 transition-transform duration-300">
            <div class="mb-8">
                <h2 class="text-xl font-bold mb-6 flex items-center text-blue-400 md:block hidden">
                    <i class="fas fa-cogs mr-2"></i>
                    Panel de Administración
                </h2>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard.index') }}"
                           class="sidebar-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}"
                           aria-label="Ir al Dashboard">
                            <i class="fas fa-tachometer-alt mr-3"></i>
                            Dashboard
                        </a>
                    </li>
                    @if(auth()->user()->hasAnyRole('video-manager', 'super-admin'))
                        <li>
                            <a href="{{ route('videos.manage.index') }}"
                               class="sidebar-link {{ request()->routeIs('videos.manage.*') ? 'active' : '' }}"
                               aria-label="Gestionar Videos">
                                <i class="fas fa-video mr-3"></i>
                                Videos
                            </a>
                        </li>
                    @endif
                    @if(auth()->user()->hasAnyRole('serie-manager', 'super-admin'))
                        <li>
                            <a href="{{ route('series.manage.index') }}"
                               class="sidebar-link {{ request()->routeIs('series.manage.*') ? 'active' : '' }}"
                               aria-label="Gestionar Series">
                                <i class="fas fa-film mr-3"></i>
                                Series
                            </a>
                        </li>
                    @endif
                    @if(auth()->user()->hasAnyRole('user-manager', 'super-admin'))
                        <li>
                            <a href="{{ route('users.manage.index') }}"
                               class="sidebar-link {{ request()->routeIs('users.manage.*') ? 'active' : '' }}"
                               aria-label="Gestionar Usuarios">
                                <i class="fas fa-users mr-3"></i>
                                Usuarios
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <div class="mt-auto pt-4 border-t border-slate-700">
                <a href="{{ route('videos.index') }}"
                   class="btn btn-ghost w-full text-left"
                   aria-label="Volver al sitio público">
                    <i class="fas fa-arrow-left mr-3"></i>
                    Volver al sitio
                </a>
            </div>
        </aside>

        <!-- Overlay for Mobile Sidebar -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden"></div>

        <!-- Main Content -->
        <main class="flex-1 p-6 md:ml-64 bg-slate-900 min-h-screen pt-16 md:pt-6">
            <div class="container mx-auto px-4 py-12">
                @yield('admin-content')
            </div>
        </main>
    </div>

    <style>
        /* Sidebar Link Styles */
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease;
        }

        .sidebar-link:hover {
            background-color: #334155;
        }

        .sidebar-link.active {
            background-color: #3b82f6;
            font-weight: 500;
        }

        /* Button Styles */
        .btn {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .btn-ghost {
            color: #94a3b8;
            background-color: transparent;
        }

        .btn-ghost:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.1);
        }
    </style>

    <script>
        // Sidebar Toggle
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('sidebar-toggle');
            const overlay = document.getElementById('sidebar-overlay');
            const sidebarIcon = document.getElementById('sidebar-icon');

            toggleBtn.addEventListener('click', () => {
                const isOpen = !sidebar.classList.contains('-translate-x-full');
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
                toggleBtn.setAttribute('aria-expanded', !isOpen);
                toggleBtn.setAttribute('aria-label', isOpen ? 'Abrir menú de administración' : 'Cerrar menú de administración');
                sidebarIcon.classList.toggle('fa-bars');
                sidebarIcon.classList.toggle('fa-times');
            });

            overlay.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                toggleBtn.setAttribute('aria-expanded', 'false');
                toggleBtn.setAttribute('aria-label', 'Abrir menú de administración');
                sidebarIcon.classList.add('fa-bars');
                sidebarIcon.classList.remove('fa-times');
            });
        });
    </script>
@endsection
