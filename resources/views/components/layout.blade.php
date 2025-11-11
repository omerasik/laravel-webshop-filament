@props([
    'title' => 'Webshop Omerasik',
    'metaDescription' => 'Ontdek natuurlijke huidverzorgingsproducten, cadeausets en wellness accessoires bij Webshop Omerasik.',
    'schema' => null,
])

@php
    $pageTitle = trim($title);
    $fullTitle = $pageTitle === 'Webshop Omerasik'
        ? $pageTitle
        : "{$pageTitle} | Webshop Omerasik";

    $description = trim($metaDescription) ?: 'Ontdek natuurlijke huidverzorgingsproducten bij Webshop Omerasik.';
    $schemaJson = $schema
        ? (is_array($schema) ? json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : $schema)
        : null;
@endphp

<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $fullTitle }}</title>
        <meta name="description" content="{{ $description }}">
        <link rel="canonical" href="{{ url()->current() }}">

        {{-- LinkedIn & SEO basic OG tags --}}
        <meta property="og:title" content="{{ $fullTitle }}">
        <meta property="og:description" content="{{ $description }}">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">

        {{-- Fonts & Styles --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">

        {{-- Structured data (Schema.org) --}}
        @if ($schemaJson)
            <script type="application/ld+json">{!! $schemaJson !!}</script>
        @endif
    </head>
    <body>
        {{-- Navigatie en logo --}}
        <header class="site-header">
            <div class="container">
                <x-navigation />
            </div>
        </header>

        {{-- Pagina-inhoud en meldingen --}}
        <main class="site-main">
            <div class="container">
                @if (session('success'))
                    <div class="flash flash--success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="flash flash--error">{{ session('error') }}</div>
                @endif

                @if ($errors->any())
                    <div class="flash flash--error">
                        <p class="flash__title">Er trad een fout op:</p>
                        <ul class="flash__list">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

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
