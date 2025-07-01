<!-- resources/views/components/portal-dashboard.blade.php -->
@props([
    'apps' => [
        [
            'name' => 'E-Arsip',
            'icon' => '📂',
            'description' => 'Sistem pengarsipan elektronik Dinas Kearsipan.',
            'status' => 'connected',
            'category' => 'Dokumen',
        ],
        [
            'name' => 'E-Surat',
            'icon' => '✉️',
            'description' => 'Aplikasi surat-menyurat internal pemerintah.',
            'status' => 'connected',
            'category' => 'Komunikasi',
        ],
        [
            'name' => 'Absensi Pegawai',
            'icon' => '🕒',
            'description' => 'Sistem kehadiran pegawai Diskominfo.',
            'status' => 'disconnected',
            'category' => 'Kepegawaian',
        ],
    ],
])

@php
    $categories = collect($apps)->pluck('category')->unique()->prepend('All');
    $search = request('search', '');
    $selectedCategory = request('category', 'All');
    $filteredApps = collect($apps)->filter(
        fn($app) => str_contains(strtolower($app['name']), strtolower($search)) &&
            ($selectedCategory === 'All' || $app['category'] === $selectedCategory),
    );
@endphp

<div x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', toggle() { this.darkMode = !this.darkMode;
        localStorage.setItem('darkMode', this.darkMode); } }" x-init="$watch('darkMode', val => document.documentElement.classList.toggle('dark', val))">
    <div
        class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-200 dark:from-gray-900 dark:to-gray-800 text-gray-900 dark:text-gray-100">
        <header
            class="bg-white dark:bg-gray-900 shadow-sm border-b border-gray-200 dark:border-gray-700 p-4 flex items-center justify-between">
            <div class="text-2xl font-bold text-blue-700 dark:text-blue-400">PortalAkses</div>
            <div class="flex items-center gap-4">
                <button @click="toggle"
                    class="px-3 py-1 rounded-full text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-100">
                    <span x-text="darkMode ? '☀️ Light' : '🌙 Dark'"></span>
                </button>
                <span class="text-sm text-gray-600 dark:text-gray-300">Login sebagai
                    <strong>{{ auth()->user()->name }}</strong></span>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}"
                    class="w-9 h-9 rounded-full border border-gray-300" alt="User" />
            </div>
        </header>

        <main class="p-8">
            <div class="mb-6">
                <h2 class="text-3xl font-semibold text-gray-800 dark:text-white">Aplikasi Terintegrasi</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Gunakan satu akun untuk mengakses seluruh
                    layanan.</p>
            </div>

            <form method="GET" class="flex flex-col sm:flex-row sm:items-center gap-4 mb-8">
                <input type="text" name="search" placeholder="Cari aplikasi..." value="{{ $search }}"
                    class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm w-full sm:w-1/3">
                <select name="category"
                    class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm w-full sm:w-1/4">
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" @selected($cat === $selectedCategory)>{{ $cat }}</option>
                    @endforeach
                </select>
                <button type="submit"
                    class="px-4 py-2 text-sm rounded-md bg-blue-600 text-white hover:bg-blue-700">Filter</button>
            </form>

            <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($filteredApps as $app)
                    <div
                        class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm hover:shadow-md transition">
                        <div class="flex items-start gap-4">
                            <div class="text-5xl">{{ $app['icon'] }}</div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-800 dark:text-gray-100 mb-1">
                                    {{ $app['name'] }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 leading-snug">
                                    {{ $app['description'] }}</p>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-between items-center">
                            <span
                                class="text-xs font-medium px-2.5 py-1.5 rounded-full {{ $app['status'] === 'connected' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $app['status'] === 'connected' ? 'Terhubung' : 'Belum Terhubung' }}
                            </span>
                            <button
                                class="px-4 py-1.5 text-sm rounded-lg font-medium shadow-sm {{ $app['status'] === 'connected' ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-gray-200 text-gray-500 cursor-not-allowed' }}"
                                {{ $app['status'] !== 'connected' ? 'disabled' : '' }}>
                                Buka
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </main>
    </div>
</div>
