<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#f8fafc">

    {{-- Logo Portal ASN — sistem logo Kisara v1 (nawasara-dev docs/brand/kisara-logo) --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon/mark.svg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">
    <link rel="shortcut icon" href="{{ asset('favicon/favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <title>{{ config('app.name', 'Portal ASN') }}</title>

    {{-- Tema gelap: lihat components/theme-script.blade.php. --}}
    <x-theme-script />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://unpkg.com/@alpinejs/ui@3.15.0/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/@alpinejs/focus@3.15.0/dist/cdn.min.js"></script>

    @livewireStyles
</head>

<body class="font-sans antialiased bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 overflow-x-hidden">

    <div class="ks-strip"></div>

    {{-- Bilah atas (desktop); di HP navigasi ada di bawah --}}
    <livewire:shared-components.topbar />

    {{-- Navigasi bawah (HP) --}}
    <livewire:shared-components.navbar-mobile />

    <main class="max-w-6xl mx-auto px-4 sm:px-6 pb-28 md:pb-8">
        {{ $slot }}
    </main>

    @stack('modals')
    @livewireScripts

</body>

</html>
