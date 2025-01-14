<!-- resources/views/layouts/videos-app-layout.blade.php -->

<!DOCTYPE html>
<html lang="ca">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>VídeosApp Gmoreno</title>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>
    <body>
        <header>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container">
                    <a class="navbar-brand" href="{{ route('videos.index') }}">VídeosApp</a>
                </div>
            </nav>
        </header>

        <main>
            <div class="container mt-4">
                @yield('content')
            </div>
        </main>

        <footer class="bg-light text-center text-lg-start mt-4">
            <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2)">
                &copy; 2021 <a class="text-dark" href="https://github.com/gerardmoreno123" target="_blank">Gmoreno</a>
            </div>
        </footer>
    </body>
</html>
