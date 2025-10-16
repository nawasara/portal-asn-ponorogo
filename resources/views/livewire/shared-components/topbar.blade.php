<div>
    <header class="dark:bg-gray-900/50 bg-gray-100/50 backdrop-blur-md shadow shadow-gray-300 dark:shadow-gray-500">
        <nav aria-label="Global" class="mx-auto flex items-center justify-between gap-x-6 px-6 py-2 lg:px-8 max-w-6xl">

            <div class="flex lg:flex-1">
                <a href="/" class="-m-1.5 p-1.5 flex items-center gap-x-2">
                    <span class="sr-only">Portal ASN Ponorogo</span>
                    <img src="{{ asset('img/logo.png') }}" alt="Portal ASN Ponorogo" class="h-12 w-auto" />

                    <span class="dark:text-white font-semibold">Portal ASN Ponorogo</span>
                </a>
            </div>

            <div class="flex flex-1 items-center justify-end gap-x-2">

                {{-- Dark mode toggle --}}
                <x-dark-mode />

                {{-- <a href="#" class="hidden text-sm/6 font-semibold text-white lg:block">Log in</a> --}}

                @auth
                    {{-- <span class="text-xs sm:text-base dark:text-white">Halo, </span> --}}
                    <div class="hs-dropdown relative inline-flex [--placement:bottom-right] ">
                        <button id="portal-user-dropdown" type="button"
                            class="hs-dropdown-toggle py-3 px-4 inline-flex items-center gap-x-2 text-sm cursor-pointer font-medium rounded-lg text-gray-800 hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700"
                            aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                            {{ auth()->user()->name }}
                            <svg class="hs-dropdown-open:rotate-180 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>

                        <div class="hs-dropdown-menu transition-[opacity,margin] mt-5 duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg dark:bg-neutral-800 dark:border dark:border-neutral-700"
                            role="menu" aria-orientation="vertical" aria-labelledby="portal-user-dropdown">
                            <div class="py-3 px-4 border-b border-gray-200 dark:border-neutral-700">
                                <p class="text-sm text-gray-500 dark:text-neutral-400">Login sebagai</p>
                                <p class="text-sm font-medium text-gray-800 dark:text-neutral-300">{{auth()->user()->name}}
                                </p>
                                <p class="text-xs font-medium text-gray-500 dark:text-neutral-300">{{auth()->user()->email}}
                                </p>
                            </div>
                            <div class="p-1 space-y-0.5">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <button type="submit"
                                        class="flex w-full items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-log-out-icon lucide-log-out size-4 text-gray-700">
                                            <path d="m16 17 5-5-5-5" />
                                            <path d="M21 12H9" />
                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                        </svg>
                                        Keluar
                                    </button>
                                    {{-- <div class="hidden lg:block">
                                        <button
                                            class="px-6 py-3  bg-slate-200 flex gap-x-2 items-center mx-auto dark:bg-slate-800 hover:bg-slate-300 rounded-lg text-lg font-medium transition transform hover:-translate-y-0.5 hover:shadow-lg dark:hover:bg-slate-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-log-out-icon lucide-log-out">
                                                <path d="m16 17 5-5-5-5" />
                                                <path d="M21 12H9" />
                                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                            </svg>
                                            Logout
                                        </button>
                                    </div> --}}

                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="/login"
                        class="rounded-md bg-emerald-500 px-3 py-2 text-xs sm:text-sm font-semibold text-white shadow-xs hover:bg-emerald-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500">
                        Masuk
                    </a>
                @endauth
            </div>

        </nav>

    </header>

</div>