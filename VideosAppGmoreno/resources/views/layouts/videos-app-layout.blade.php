<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VídeosApp Gmoreno</title>
    <script src="{{ asset('js/app.js') }}"></script>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #3b82f6;
            --primary-hover: #2563eb;
            --secondary: #10b981;
            --secondary-hover: #059669;
            --dark: #1e293b;
            --darker: #0f172a;
            --light: #f8fafc;
            --card-bg: #334155;
        }

        body {
            background-color: var(--darker);
            color: var(--light);
            font-family: 'Inter', sans-serif;
        }

        .gradient-text {
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .card-hover {
            transition: all 0.3s ease;
            transform: translateY(0);
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
        }

        .video-thumbnail {
            transition: transform 0.3s ease;
        }

        .video-thumbnail:hover {
            transform: scale(1.03);
        }

        .play-icon {
            transition: all 0.3s ease;
            opacity: 0;
        }

        .video-container:hover .play-icon {
            opacity: 1;
            transform: scale(1.2);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">

<!-- Header -->
<header class="bg-gradient-to-r from-slate-900 to-slate-800 shadow-xl sticky top-0 z-50">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <!-- Logo/Brand -->
        <a href="{{ route('videos.index') }}" class="flex items-center space-x-2">
            <i class="fas fa-play-circle text-3xl text-blue-400"></i>
            <h1 class="text-2xl font-bold gradient-text">VídeosApp</h1>
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center space-x-6">
            @guest
                <a href="{{ route('login') }}" class="text-slate-200 hover:text-blue-400 transition-colors font-medium flex items-center space-x-1">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Iniciar sesión</span>
                </a>
                <a href="{{ route('register') }}" class="text-slate-200 hover:text-emerald-400 transition-colors font-medium flex items-center space-x-1">
                    <i class="fas fa-user-plus"></i>
                    <span>Registrarse</span>
                </a>
            @else
                <div class="flex items-center space-x-4">
                    <div class="relative group">
                        <!-- Contenedor que agrupa botón y menú para mantener el hover -->
                        <div class="inline-block">
                            <button class="flex items-center space-x-2 focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="text-slate-200">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                            </button>

                            <!-- Menú desplegable - Añadido padding-top en el contenedor padre -->
                            <div class="absolute right-0 pt-2 w-56 z-50">
                                <div class="bg-slate-800 rounded-md shadow-lg py-1 hidden group-hover:block">
                                    @if(auth())
                                        <a href="{{ route('videos.index') }}" class="block px-4 py-2 text-slate-200 hover:bg-slate-700 transition-colors flex items-center space-x-2">
                                            <i class="fas fa-video text-blue-400"></i>
                                            <span>Videos</span>
                                        </a>
                                        <a href="{{ route('series.index') }}" class="block px-4 py-2 text-slate-200 hover:bg-slate-700 transition-colors flex items-center space-x-2">
                                            <i class="fas fa-film text-blue-400"></i>
                                            <span>Series</span>
                                        </a>
                                        <a href="{{ route('users.index') }}" class="block px-4 py-2 text-slate-200 hover:bg-slate-700 transition-colors flex items-center space-x-2">
                                            <i class="fas fa-users text-blue-400"></i>
                                            <span>Usuarios</span>
                                        </a>
                                    @endif

                                    @if(auth()->user()->hasAnyRole('video-manager', 'serie-manager', 'user-manager', 'super-admin'))
                                        <a href="{{ route('dashboard.index') }}" class="block px-4 py-2 text-slate-200 hover:bg-slate-700 transition-colors flex items-center space-x-2">
                                            <i class="fas fa-tools text-emerald-400"></i>
                                            <span>CRUDS</span>
                                        </a>
                                    @endif

                                     <!-- Divider, profile and logout -->
                                    <div class="border-t border-slate-700 my-1"></div>

                                    <a href="{{ route('users.show', Auth::user()->id) }}" class="block px-4 py-2 text-slate-200 hover:bg-slate-700 transition-colors flex items-center space-x-2">
                                        <i class="fas fa-user text-blue-400"></i>
                                        <span>Perfil</span>
                                    </a>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-slate-200 hover:bg-slate-700 transition-colors flex items-center space-x-2">
                                            <i class="fas fa-sign-out-alt text-red-400"></i>
                                            <span>Cerrar sesión</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endguest
        </nav>

        <!-- Mobile Menu Button -->
        <button id="mobile-menu-button" class="md:hidden text-slate-200 focus:outline-none">
            <i class="fas fa-bars text-2xl"></i>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-slate-800 px-4 py-3">
        @guest
            <a href="{{ route('login') }}" class="block py-2 text-slate-200 hover:text-blue-400 transition-colors font-medium flex items-center space-x-3">
                <i class="fas fa-sign-in-alt w-6 text-center"></i>
                <span>Iniciar sesión</span>
            </a>
            <a href="{{ route('register') }}" class="block py-2 text-slate-200 hover:text-emerald-400 transition-colors font-medium flex items-center space-x-3">
                <i class="fas fa-user-plus w-6 text-center"></i>
                <span>Registrarse</span>
            </a>
        @else
            <div class="py-2 font-medium flex items-center space-x-3">
                <i class="fas fa-user w-6 text-center text-slate-200"></i>
                <span>Hola, {{ Auth::user()->name }}</span>
            </div>

            @if(auth())
                <a href="{{ route('videos.index') }}" class="block px-4 py-2 text-slate-200 hover:bg-slate-700 transition-colors flex items-center space-x-2">
                    <i class="fas fa-video text-blue-400"></i>
                    <span>Videos</span>
                </a>
                <a href="{{ route('series.index') }}" class="block px-4 py-2 text-slate-200 hover:bg-slate-700 transition-colors flex items-center space-x-2">
                    <i class="fas fa-film text-blue-400"></i>
                    <span>Series</span>
                </a>
                <a href="{{ route('users.index') }}" class="block px-4 py-2 text-slate-200 hover:bg-slate-700 transition-colors flex items-center space-x-2">
                    <i class="fas fa-users text-blue-400"></i>
                    <span>Usuarios</span>
                </a>
            @endif

            @if(auth()->user()->hasAnyRole('video-manager', 'serie-manager', 'user-manager', 'super-admin'))
                <a href="{{ route('dashboard.index') }}" class="block py-2 text-slate-200 hover:text-emerald-400 transition-colors font-medium flex items-center space-x-3">
                    <i class="fas fa-tools w-6 text-center"></i>
                    <span>CRUDS</span>
                </a>
            @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left py-2 text-slate-200 hover:text-red-400 transition-colors font-medium flex items-center space-x-3">
                    <i class="fas fa-sign-out-alt w-6 text-center"></i>
                    <span>Cerrar sesión</span>
                </button>
            </form>
        @endguest
    </div>
</header>

<!-- Main Content -->
<main class="flex-grow">
    <div class="container mx-auto px-4 py-8">
        @yield('content')
    </div>
</main>

<!-- Footer -->
<footer class="bg-gradient-to-r from-slate-900 to-slate-800 py-6 border-t border-slate-700 sticky z-50">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="mb-4 md:mb-0">
                <a href="{{ route('videos.index') }}" class="flex items-center space-x-2">
                    <i class="fas fa-play-circle text-2xl text-blue-400"></i>
                    <span class="text-xl font-bold gradient-text">VídeosApp</span>
                </a>
                <p class="text-slate-400 text-sm mt-1">Tu plataforma de videos favorita</p>
            </div>

            <div class="flex space-x-4">
                <a href="#" class="text-slate-400 hover:text-blue-400 transition-colors">
                    <i class="fab fa-twitter text-xl"></i>
                </a>
                <a href="#" class="text-slate-400 hover:text-pink-500 transition-colors">
                    <i class="fab fa-instagram text-xl"></i>
                </a>
                <a href="https://github.com/gerardmoreno123" target="_blank" class="text-slate-400 hover:text-slate-200 transition-colors">
                    <i class="fab fa-github text-xl"></i>
                </a>
            </div>
        </div>

        <div class="mt-6 pt-6 border-t border-slate-700 text-center text-slate-500 text-sm">
            &copy; 2025 <a href="https://github.com/gerardmoreno123" target="_blank" class="text-emerald-400 hover:text-emerald-300 transition-colors">Gmoreno</a>. Todos los derechos reservados.
        </div>
    </div>
</footer>

<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });
</script>

</body>
</html>
