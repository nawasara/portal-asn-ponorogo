<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#ffffff">

    <!-- Favicon untuk berbagai perangkat -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">
    <link rel="shortcut icon" href="{{ asset('favicon/favicon.ico') }}">

    <!-- Untuk Android / PWA -->
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon/android-chrome-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('favicon/android-chrome-512x512.png') }}">
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <title>{{ config('app.name', 'Laravel') }}</title>

    </script>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <script defer src="https://unpkg.com/@alpinejs/ui@3.15.0/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/@alpinejs/focus@3.15.0/dist/cdn.min.js"></script>

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">

    <div class="absolute top-0 inset-x-0 z-[10] ">
        <livewire:shared-components.topbar />
    </div>

    <div class="bg-gray-100 dark:bg-gray-900">
        <main class="">
            {{ $slot }}
        </main>
    </div>

    @stack('modals')
    @livewireScripts

    <script>
        // Global dark mode handler
        ( function ()
        {
            const theme = localStorage.getItem( 'hs_theme' );
            const prefersDark = window.matchMedia( '(prefers-color-scheme: dark)' ).matches;
            if ( theme === 'dark' || ( !theme && prefersDark ) ) {
                document.documentElement.classList.add( 'dark' );
            } else {
                document.documentElement.classList.remove( 'dark' );
            }
        } )();
    </script>


    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        AOS.init();
    </script>
</body>

</html>