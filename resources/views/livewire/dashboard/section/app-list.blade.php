<div>
    <div class="max-w-6xl py-10 lg:py-14 mx-auto 
           sm:bg-linear-to-b/decreasing from-gray-200 to-white 
           sm:rounded-br-4xl sm:rounded-bl-4xl 
           dark:from-slate-800 dark:to-slate-900 
           dark:sm:bg-linear-to-b/srgb" id="app-lists">

        @if (Route::is('apps'))
            <div class="flex items-center justify-center gap-x-3 md:hidden">
                <img src="{{ asset('img/logo.png') }}" alt="Portal ASN Ponorogo" class="size-6 w-auto" loading="lazy" />
                <span class="dark:text-white text-gray-700 font-semibold">Aplikasi Terintegrasi SSO Kisara</span>
            </div>
        @endif

        @php
            $search = request('search', '');
            // $selectedCategory = request('category', 'All');
            $filteredApps = collect($apps)->filter(fn($app) => str_contains(strtolower($app['name']), strtolower($search)));
        @endphp

        <div class="py-6" data-aos="fade-up" data-aos-duration="1000">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white md:text-4xl text-center mx-auto py-6">
                Daftar Aplikasi
            </h2>
        </div>

        {{-- <!-- Grid --> --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 md:grid-cols-2 gap-6 sm:px-4" data-aos="fade-up"
            data-aos-duration="1000" date-aos-delay="3000">
            @foreach ($filteredApps as $app)

                <a href="{{ $app['link'] }}" class="group border border-gray-200 dark:border-gray-700 rounded-2xl ">
                    <div
                        class="px-6 pt-6 bg-white dark:bg-gray-900 duration-300 rounded-t-2xl group-hover:shadow-lg transition-all justify-between min-h-40 md:min-h-52">
                        <div class="">
                            <img src="{{ asset($app['icon']) }}" alt="{{ $app['name'] }} Icon"
                                class="shrink-0 size-10 mb-3 w-auto">
                            <p class="font-semibold text-gray-800 dark:text-neutral-200">
                                {{ $app['name'] }}
                            </p>
                            <p class="mt-1 text-sm text-gray-600 dark:text-neutral-400">
                                {{ $app['description'] }}
                            </p>
                        </div>
                    </div>
                    <div
                        class="px-6 pb-6 pt-5 md:pt-2 bg-white dark:bg-gray-900 items-center justify-between rounded-b-2xl flex group-hover:shadow-lg duration-300 transition-all">
                        <div
                            class="px-3 rounded-full text-xs py-1 {{ $app['status'] === 'connected' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">
                            {{ $app['status'] === 'connected' ? 'Terhubung' : 'Belum Terhubung' }}
                        </div>
                    </div>
                </a>

            @endforeach
        </div>
        {{-- <!-- End Grid --> --}}

    </div>
</div>