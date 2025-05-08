<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VídeosApp Gmoreno</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        window.PUSHER_APP_KEY = "{{ config('broadcasting.connections.pusher.key') }}";
        window.PUSHER_APP_CLUSTER = "{{ config('broadcasting.connections.pusher.options.cluster') }}";
        window.AUTH_USER_ID = {{ auth()->id() ?? 'null' }};
    </script>
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
            --font-size-sm: 12px;
            --font-size-base: 16px;
            --font-size-md: 20px;
            --font-size-lg: 24px;
        }

        body {
            background-color: var(--darker);
            color: var(--light);
            font-family: 'Inter', sans-serif;
            font-size: var(--font-size-base);
        }

        .gradient-text {
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .video-container:hover .play-icon {
            opacity: 1;
            transform: scale(1.2);
        }

        /* Overlay for dropdowns */
        .overlay {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 50;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .overlay.show {
            opacity: 1;
        }

        /* Mobile menu animation */
        #mobile-menu {
            transition: transform 0.3s ease;
            transform: translateX(100%);
        }

        #mobile-menu.show {
            transform: translateX(0);
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
                    <div class="relative group" id="user-menu-container">
                        <!-- Contenedor para botón, menú y overlay -->
                        <div class="inline-block">
                            <button id="user-menu-button" class="flex items-center space-x-2 focus:outline-none" aria-label="Obrir menú d'usuari" role="button">
                                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="text-slate-200">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                            </button>

                            <!-- Overlay -->
                            <div id="user-menu-overlay" class="overlay hidden"></div>

                            <!-- Menú desplegable -->
                            <div id="user-menu-dropdown" class="absolute right-0 top-full mt-2 w-56 hidden z-50">
                                <div class="bg-slate-800 rounded-md shadow-lg py-1">
                                    @if(auth())
                                        <a href="{{ route('videos.index') }}" class="block px-4 py-2 text-slate-200 hover:bg-slate-700 transition-colors flex items-center space-x-2">
                                            <i class="fas fa-video text-blue-400"></i>
                                            <span>Videos</span>
                                        </a>
                                        <a href="{{ route('series.index') }}" class="block px-4 py-2 text-slate-200 hover:bg-slate-700 transition-colors flex items-center space-x-2">
                                            <i class="fas fa-film text-emerald-400"></i>
                                            <span>Series</span>
                                        </a>
                                        <a href="{{ route('users.index') }}" class="block px-4 py-2 text-slate-200 hover:bg-slate-700 transition-colors flex items-center space-x-2">
                                            <i class="fas fa-users text-purple-400"></i>
                                            <span>Usuarios</span>
                                        </a>
                                        <a href="{{ route('notifications.index') }}" class="block px-4 py-2 text-slate-200 hover:bg-slate-700 transition-colors flex items-center space-x-2">
                                            <i class="fas fa-bell text-yellow-400"></i>
                                            <span>Notificaciones</span>
                                        </a>
                                    @endif

                                    @if(auth()->user()->hasAnyRole('video-manager', 'serie-manager', 'user-manager', 'super-admin'))
                                        <a href="{{ route('dashboard.index') }}" class="block px-4 py-2 text-slate-200 hover:bg-slate-700 transition-colors flex items-center space-x-2">
                                            <i class="fas fa-tools text-teal-400"></i>
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
        <button id="mobile-menu-button" class="md:hidden text-slate-200 focus:outline-none" aria-label="Obrir menú mòbil" role="button">
            <i class="fas fa-bars text-2xl"></i>
        </button>
    </div>

    <!-- Mobile Overlay -->
    <div id="mobile-overlay" class="overlay hidden"></div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-slate-800 px-4 py-3 fixed top-0 right-0 h-full w-64 z-50">
        @guest
            <a href="{{ route('login') }}" class="block py-2 text-slate-200 hover:text-blue-400 transition-colors font-medium flex items-center space-x-3">
                <i class="fas fa-sign-in-alt w-6 text-center text-blue-400"></i>
                <span>Iniciar sesión</span>
            </a>
            <a href="{{ route('register') }}" class="block py-2 text-slate-200 hover:text-emerald-400 transition-colors font-medium flex items-center space-x-3">
                <i class="fas fa-user-plus w-6 text-center text-emerald-400"></i>
                <span>Registrarse</span>
            </a>
        @else
            <div class="py-2 font-medium flex items-center space-x-3">
                <i class="fas fa-user w-6 text-center text-blue-400"></i>
                <span>Hola, {{ Auth::user()->name }}</span>
            </div>

            @if(auth())
                <a href="{{ route('videos.index') }}" class="block px-4 py-2 text-slate-200 hover:bg-slate-700 transition-colors flex items-center space-x-2">
                    <i class="fas fa-video text-blue-400"></i>
                    <span>Videos</span>
                </a>
                <a href="{{ route('series.index') }}" class="block px-4 py-2 text-slate-200 hover:bg-slate-700 transition-colors flex items-center space-x-2">
                    <i class="fas fa-film text-emerald-400"></i>
                    <span>Series</span>
                </a>
                <a href="{{ route('users.index') }}" class="block px-4 py-2 text-slate-200 hover:bg-slate-700 transition-colors flex items-center space-x-2">
                    <i class="fas fa-users text-purple-400"></i>
                    <span>Usuarios</span>
                </a>
                <a href="{{ route('notifications.index') }}" class="block px-4 py-2 text-slate-200 hover:bg-slate-700 transition-colors flex items-center space-x-2">
                    <i class="fas fa-bell text-yellow-400"></i>
                    <span>Notificaciones</span>
                </a>
            @endif

            @if(auth()->user()->hasAnyRole('video-manager', 'serie-manager', 'user-manager', 'super-admin'))
                <a href="{{ route('dashboard.index') }}" class="block px-4 py-2 text-slate-200 hover:bg-slate-700 transition-colors flex items-center space-x-2">
                    <i class="fas fa-tools text-teal-400"></i>
                    <span>CRUDS</span>
                </a>
            @endif

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
<footer class="bg-gradient-to-r from-slate-900 to-slate-800 py-6 border-t border-slate-700 sticky z-40">
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
            © 2025 <a href="https://github.com/gerardmoreno123" target="_blank" class="text-emerald-400 hover:text-emerald-300 transition-colors">Gmoreno</a>. Todos los derechos reservados.
        </div>
    </div>
</footer>

<script>
    // User dropdown menu functionality
    const userMenuButton = document.getElementById('user-menu-button');
    const userMenuDropdown = document.getElementById('user-menu-dropdown');
    const userMenuOverlay = document.getElementById('user-menu-overlay');
    const userMenuContainer = document.getElementById('user-menu-container');

    let menuTimeout;

    function showMenu() {
        clearTimeout(menuTimeout);
        userMenuDropdown.classList.remove('hidden');
        userMenuOverlay.classList.remove('hidden');
        userMenuOverlay.classList.add('show');
    }

    function hideMenu() {
        menuTimeout = setTimeout(() => {
            userMenuDropdown.classList.add('hidden');
            userMenuOverlay.classList.remove('show');
            userMenuOverlay.classList.add('hidden');
        }, 300);
    }

    // Solo registra eventos si userMenuButton existe
    if (userMenuButton) {
        userMenuButton.addEventListener('mouseenter', showMenu);
        userMenuButton.addEventListener('click', showMenu);
        userMenuButton.addEventListener('mouseleave', hideMenu);
    }

    // Solo registra eventos si userMenuDropdown existe
    if (userMenuDropdown) {
        userMenuDropdown.addEventListener('mouseenter', () => {
            clearTimeout(menuTimeout);
        });
        userMenuDropdown.addEventListener('mouseleave', hideMenu);
    }

    // Solo registra eventos si userMenuOverlay existe
    if (userMenuOverlay) {
        userMenuOverlay.addEventListener('click', hideMenu);
    }

    // Solo registra eventos si userMenuContainer existe
    if (userMenuContainer) {
        document.addEventListener('click', (event) => {
            if (!userMenuContainer.contains(event.target) && !userMenuDropdown.contains(event.target)) {
                hideMenu();
            }
        });
    }

    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileOverlay = document.getElementById('mobile-overlay');

    if (mobileMenuButton && mobileMenu && mobileOverlay) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
            mobileMenu.classList.toggle('show');
            mobileOverlay.classList.toggle('hidden');
            mobileOverlay.classList.toggle('show');
        });

        mobileOverlay.addEventListener('click', function() {
            mobileMenu.classList.add('hidden');
            mobileMenu.classList.remove('show');
            mobileOverlay.classList.add('hidden');
            mobileOverlay.classList.remove('show');
        });
    } else {
        console.error('Uno o más elementos del menú móvil no se encontraron:', {
            mobileMenuButton: !!mobileMenuButton,
            mobileMenu: !!mobileMenu,
            mobileOverlay: !!mobileOverlay
        });
    }
</script>

</body>
</html>
