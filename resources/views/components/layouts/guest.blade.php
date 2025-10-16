<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.png.png') }}">
    <link rel="icon" href="{{ asset('favicon.png.png') }}" type="image/x-icon">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{--
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> --}}

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{--
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('MAPS_API_KEY') }}&libraries=marker&loading=async">
    </script>
    --}}

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/map.js'])

    <!-- Styles -->
    @livewireStyles

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

</head>

<body class="bg-gray-50 dark:bg-gray-900" x-cloak>


    <div class="bg-gray-50 dark:bg-gray-900">{{ $slot }}</div>

    @livewire('modals.report')
    @stack('modals')
    @livewireScripts
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <x-livewire-alert::scripts />
    @livewire('wire-elements-modal')
</body>

</html>