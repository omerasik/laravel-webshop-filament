@props(['title' => 'Webshop Omerasik'])

<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title }} | Webshop Omerasik</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>
    <body>
        <header class="site-header">
            <div class="container">
                <x-navigation />
            </div>
        </header>

        <main class="site-main">
            <div class="container">
                {{ $slot }}
            </div>
        </main>

        <footer class="site-footer">
            <div class="container">
                &copy; {{ now()->year }} Webshop Omerasik
            </div>
        </footer>
    </body>
</html>
