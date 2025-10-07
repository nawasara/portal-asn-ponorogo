<!-- resources/views/components/portal-dashboard.blade.php -->

@php
    $search = request('search', '');
    // $selectedCategory = request('category', 'All');
    $filteredApps = collect($apps)->filter(fn($app) => str_contains(strtolower($app['name']), strtolower($search)));
@endphp
<div
    class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-200 dark:from-gray-900 dark:to-gray-800 text-gray-900 dark:text-gray-100 relative">
    <x-benner />
    {{-- Floating top right: toggle dark mode + user/login --}}
    <div class="fixed top-6 right-8 z-50 flex items-center gap-2" x-data="{
        open: false,
    }">
        <x-dark-mode />
        <a href="{{ url('/reset-mfa-unauthorization') }}"
            class="flex items-center gap-2 shadow-md rounded-full bg-white dark:bg-gray-800 px-2 py-1 hover:bg-blue-50 dark:hover:bg-gray-700 transition">
            <div
                class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-base">
                R
            </div>
            <span class="font-medium text-gray-800 dark:text-gray-100 text-sm">Reset MFA</span>
        </a>
        <x-user-menu />
    </div>

    <div class="max-w-6xl mx-auto p-6">
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-1">Portal ASN Ponorogo</h2>
            <p class="text-base text-gray-500 dark:text-gray-400">Gunakan satu akun untuk mengakses seluruh layanan.</p>
        </div>

        <form method="GET" class="flex flex-col sm:flex-row sm:items-center gap-4 mb-8">
            <input type="text" name="search" placeholder="Cari aplikasi..." value="{{ $search }}"
                class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm w-full sm:w-1/3 shadow-sm focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-900 text-gray-900 dark:text-gray-100">

            <button type="submit"
                class="px-4 py-2 text-sm rounded-md bg-blue-600 text-white hover:bg-blue-700 shadow-sm">Filter</button>
        </form>


        @if (session('success'))
            <div class="mt-2 mb-5 bg-green-100 border border-green-200 text-sm text-green-800 rounded-lg p-4 dark:bg-green-800/10 dark:border-green-900 dark:text-green-500"
                role="alert" tabindex="-1" aria-labelledby="hs-soft-color-success-label">
                <span id="hs-soft-color-success-label" class="font-bold">Berhasil!</span> {{ session('success') }}
            </div>
        @endif
        <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 pb-12">
            @foreach ($filteredApps as $app)
                <div
                    class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 shadow-md hover:shadow-lg transition flex flex-col justify-between min-h-[220px]">
                    <div class="flex items-start gap-4 mb-3">
                        @if ($app['icon_type'] === 'image')
                            <img src="{{ $app['icon'] }}" alt="{{ $app['name'] }} Icon" class="w-12 h-auto">
                        @else
                            <div class="text-5xl">{{ $app['icon'] }}</div>
                        @endif
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-1">{{ $app['name'] }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 leading-snug">{{ $app['description'] }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-auto flex justify-between items-center">
                        @if ($app['name'] != 'Dokumentasi')
                            <span
                                class="text-xs font-medium px-2.5 py-1.5 rounded-full {{ $app['status'] === 'connected' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">
                                {{ $app['status'] === 'connected' ? 'Terhubung' : 'Belum Terhubung' }}
                            </span>
                        @endif
                        <a href="{{ $app['link'] }}" @if ($app['link'] != '#') target="_blank" @endif
                            rel="noopener"
                            class="px-4 py-1.5 text-sm rounded-lg font-medium shadow-sm {{ $app['status'] === 'connected' ? 'bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800' : 'bg-gray-200 text-gray-500 cursor-not-allowed dark:bg-gray-800 dark:text-gray-400' }}"
                            {{ $app['status'] !== 'connected' ? 'disabled' : '' }}>
                            Buka
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
