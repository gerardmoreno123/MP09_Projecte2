<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VídeosApp Gmoreno</title>
    <script src="{{ asset('js/app.js') }}"></script>
    @vite('resources/css/app.css')
    <style>
        body {
            background-color: #1a202c; /* Tailwind's bg-gray-900 */
            color: #fff; /* Tailwind's text-white */
        }
        .btn-green {
            background-color: #38a169; /* Tailwind's green-500 */
            color: #fff;
        }
        .btn-green:hover {
            background-color: #2f855a; /* Tailwind's green-600 */
        }
        .btn-orange {
            background-color: #ed8936; /* Tailwind's orange-500 */
            color: #fff;
        }
        .btn-orange:hover {
            background-color: #dd6b20; /* Tailwind's orange-600 */
        }
    </style>
</head>
<body class="bg-gray-900 text-white flex flex-col min-h-screen">

<!-- Header -->
<header class="bg-gray-800 text-white py-4 shadow-lg">
    <div class="container mx-auto px-4 sm:px-6 flex justify-between items-center">
        <a href="{{ route('videos.index') }}" class="text-white">
            <h1 class="text-3xl font-semibold">VídeosApp Gmoreno</h1>
        </a>

        <!-- Navigation Menu -->
        <nav class="space-x-6">
            @guest
                <a href="{{ route('login') }}" class="text-white hover:text-green-400 transition-colors">Iniciar sesión</a>
                <a href="{{ route('register') }}" class="text-white hover:text-green-400 transition-colors">Registrarse</a>
            @else
                <a href="{{ route('dashboard') }}" class="text-white hover:text-green-400 transition-colors">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-white hover:text-green-400 transition-colors">Cerrar sesión</button>
                </form>
            @endguest
        </nav>
    </div>
</header>

<!-- Main Content -->
<main class="flex-grow bg-gray-900">
    <div class="container mx-auto px-4 sm:px-6 py-8">
        @yield('content')
    </div>
</main>

<!-- Footer -->
<footer class="bg-gray-800 text-center text-white py-4 mt-6">
    <div class="text-center p-3">
        &copy; 2025 <a class="text-green-400 hover:text-green-600 transition-colors" href="https://github.com/gerardmoreno123" target="_blank">Gmoreno</a>
    </div>
</footer>

</body>
</html>
