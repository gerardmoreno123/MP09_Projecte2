<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VídeosApp Gmoreno</title>
    <script src="{{ asset('js/app.js') }}"></script>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

<!-- Cabecera -->
<header class="bg-gradient-to-r from-blue-500 to-blue-700 text-white py-4">
    <nav class="navbar navbar-expand-lg navbar-light container">
        <a class="mx-5 navbar-brand text-3xl font-semibold" href="{{ route('videos.show', ['id' => rand(1,3)]) }}"> Video Random</a>
    </nav>
</header>

<!-- Contenido principal -->
<main class="flex-grow bg-gray-200">
    <div class="container mx-auto px-4 md:px-6 py-8">
        @yield('content')
    </div>
</main>

<!-- Pie de página -->
<footer class="bg-gray-800 text-center text-white py-4">
    <div class="text-center p-3">
        &copy; 2025 <a class="text-blue-400" href="https://github.com/gerardmoreno123" target="_blank">Gmoreno</a>
    </div>
</footer>

</body>
</html>
