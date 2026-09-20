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

    {{-- Tema gelap, diterapkan SEBELUM gaya apa pun tergambar.

         Harus di <head> dan tanpa `defer`: menaruhnya di akhir <body> membuat
         halaman sempat tergambar terang lebih dulu, lalu berkedip jadi gelap.

         Didaftarkan sebagai fungsi global supaya bisa dipanggil ulang setelah
         wire:navigate, yang mengganti isi halaman tanpa memuat ulang dokumen
         sehingga skrip sekali-jalan tidak pernah berjalan lagi. --}}
    <script>
        window.applyTheme = function () {
            const tersimpan = localStorage.getItem('hs_theme');
            const sukaGelap = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const gelap = tersimpan === 'dark' || (!tersimpan && sukaGelap);

            document.documentElement.classList.toggle('dark', gelap);
        };

        window.applyTheme();
    </script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://unpkg.com/@alpinejs/ui@3.15.0/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/@alpinejs/focus@3.15.0/dist/cdn.min.js"></script>

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 overflow-x-hidden">

    <x-aurora />

    {{-- topbar desktop --}}
    <div class="fixed top-0 inset-x-0 z-[10]">
        <livewire:shared-components.topbar />
    </div>

    {{-- mobile bottom navbar --}}
    <livewire:shared-components.navbar-mobile />

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-28 md:pb-8">
        {{ $slot }}
    </main>

    @stack('modals')
    @livewireScripts

    <script>
        // Terapkan ulang tema setiap kali Livewire selesai berpindah halaman.
        //
        // wire:navigate menukar isi <body> tanpa memuat ulang dokumen, jadi
        // skrip di <head> hanya berjalan sekali seumur kunjungan. Tanpa baris
        // ini, mengklik logo di topbar (yang memakai wire:navigate.hover)
        // membuat kelas `dark` hilang dan tampilan kembali terang, padahal
        // pilihan pengguna di localStorage tidak berubah.
        document.addEventListener('livewire:navigated', () => window.applyTheme());

        // Ikuti perubahan tema sistem, tetapi hanya selama pengguna belum
        // memilih sendiri. Sekali mereka menekan tombol, pilihan itu yang
        // berlaku dan tidak boleh ditimpa saat sistem berganti mode.
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            if (!localStorage.getItem('hs_theme')) window.applyTheme();
        });
    </script>

</body>

</html>