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
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-200 text-gray-900 relative">

    {{-- User info floating di pojok kanan atas --}}
    @if (Auth::check())
        <div class="fixed top-6 right-8 z-50" x-data="{ open: false }">
            <button @click="open = !open"
                class="flex items-center gap-2 focus:outline-none group shadow-md rounded-full bg-white px-2 py-1 hover:bg-blue-50 transition">
                <div
                    class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-base group-hover:ring-2 group-hover:ring-blue-400 transition">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <span class="font-medium text-gray-800 text-sm max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                <svg class="w-4 h-4 text-gray-500 group-hover:text-blue-600 transition" fill="none"
                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 border border-gray-100 origin-top-right">
                <div class="px-4 py-2 border-b border-gray-100">
                    <div class="font-semibold text-gray-800 text-sm">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                </form>
                <form method="POST" action="{{ url('/') }}">
                    @csrf
                    <button type="submit"
                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Reset
                        MFA</button>
                </form>
            </div>
        </div>
    @endif

    <main class="max-w-6xl mx-auto p-6">
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-1">Aplikasi Terintegrasi</h2>
            <p class="text-base text-gray-500">Gunakan satu akun untuk mengakses seluruh layanan.</p>
        </div>

        <form method="GET" class="flex flex-col sm:flex-row sm:items-center gap-4 mb-8">
            <input type="text" name="search" placeholder="Cari aplikasi..." value="{{ $search }}"
                class="px-4 py-2 rounded-md border border-gray-300 bg-white text-sm w-full sm:w-1/3 shadow-sm focus:ring-2 focus:ring-blue-200">
            <select name="category"
                class="px-4 py-2 rounded-md border border-gray-300 bg-white text-sm w-full sm:w-1/4 shadow-sm focus:ring-2 focus:ring-blue-200">
                @foreach ($categories as $cat)
                    <option value="{{ $cat }}" @selected($cat === $selectedCategory)>{{ $cat }}</option>
                @endforeach
            </select>
            <button type="submit"
                class="px-4 py-2 text-sm rounded-md bg-blue-600 text-white hover:bg-blue-700 shadow-sm">Filter</button>
        </form>

        <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($filteredApps as $app)
                <div
                    class="bg-white rounded-2xl border border-gray-100 p-6 shadow-md hover:shadow-lg transition flex flex-col justify-between min-h-[220px]">
                    <div class="flex items-start gap-4 mb-3">
                        <div class="text-5xl">{{ $app['icon'] }}</div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-1">{{ $app['name'] }}</h3>
                            <p class="text-sm text-gray-500 leading-snug">{{ $app['description'] }}</p>
                        </div>
                    </div>
                    <div class="mt-auto flex justify-between items-center">
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
