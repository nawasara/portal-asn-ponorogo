<div>
    <section class="h-screen flex flex-col items-center justify-center px-4 transition-colors duration-300">
        @if (Route::is('profiles'))
            <div class="flex items-center justify-center gap-x-3 md:hidden">
                <img src="{{ asset('img/logo.png') }}" alt="Portal ASN Ponorogo" class="size-6 w-auto" loading="lazy" />
                <span class="dark:text-white text-gray-700 font-semibold">SSO Kisara</span>
            </div>
        @endif

        {{-- <!-- Card --> --}}
        <div data-aos="fade-up" data-aos-offset="20" data-aos-duration="400" data-aos-once="true"
            class="w-full max-w-md mt-20 my-32 sm:mb-0 sm:mt-0 bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-8 flex flex-col items-center gap-6 text-center border border-gray-200 dark:border-slate-700 transition-all">

            {{-- <!-- User Info --> --}}
            <div>
                <h2 class="text-xl font-semibold text-slate-800 dark:text-white">
                    {{ auth()->user()->name ?? 'Selamat datang ASN' }}
                </h2>
                <p class="text-slate-500 dark:text-slate-400">
                    {{ auth()->user()->email ?? 'Masuk untuk mengakses layanan' }}
                </p>
            </div>

            {{-- <!-- Dark Mode Toggle --> --}}
            <div class="w-full">
                <button type="button"
                    class="hs-dark-mode-active:hidden block hs-dark-mode px-6 py-3 text-gray-800 w-full bg-slate-300 text-center justify-center items-center gap-x-2 hover:bg-slate-400 dark:text-slate-800 rounded-lg text-lg font-medium transition transform hover:-translate-y-0.5 hover:shadow-lg"
                    data-hs-theme-click-value="dark">
                    Mode Gelap
                    </span>
                </button>
                <button type="button"
                    class="hs-dark-mode-active:block hidden hs-dark-mode px-6 py-3 text-gray-800 w-full bg-slate-300 text-center justify-center items-center gap-x-2 hover:bg-slate-400 dark:text-slate-800 rounded-lg text-lg font-medium transition transform hover:-translate-y-0.5 hover:shadow-lg"
                    data-hs-theme-click-value="light">
                    Mode Terang
                    </span>
                </button>
            </div>

            {{-- <!-- Action Buttons --> --}}
            <div class="flex flex-col sm:flex-row gap-4 w-full justify-center">
                @auth
                    <form action="{{ route('logout') }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit"
                            class="px-6 py-3 flex items-center w-full justify-center gap-x-2 bg-gray-600 hover:bg-gray-500 text-white rounded-lg text-lg font-medium transition transform hover:-translate-y-0.5 hover:shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-log-out-icon lucide-log-out">
                                <path d="m16 17 5-5-5-5" />
                                <path d="M21 12H9" />
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            </svg>
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('mfa.reset-unauthorization') }}"
                        class="px-6 py-3 flex items-center justify-center gap-x-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-lg font-medium transition transform hover:-translate-y-0.5 hover:shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-settings-icon lucide-settings">
                            <path
                                d="M9.671 4.136a2.34 2.34 0 0 1 4.659 0 2.34 2.34 0 0 0 3.319 1.915 2.34 2.34 0 0 1 2.33 4.033 2.34 2.34 0 0 0 0 3.831 2.34 2.34 0 0 1-2.33 4.033 2.34 2.34 0 0 0-3.319 1.915 2.34 2.34 0 0 1-4.659 0 2.34 2.34 0 0 0-3.32-1.915 2.34 2.34 0 0 1-2.33-4.033 2.34 2.34 0 0 0 0-3.831A2.34 2.34 0 0 1 6.35 6.051a2.34 2.34 0 0 0 3.319-1.915" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                        Reset MFA
                    </a>
                    <a href="/login"
                        class="px-6 py-3 flex items-center justify-center gap-x-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-lg font-medium transition transform hover:-translate-y-0.5 hover:shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-log-in-icon lucide-log-in">
                            <path d="m10 17 5-5-5-5" />
                            <path d="M15 12H3" />
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                        </svg>
                        Masuk Sekarang
                    </a>
                @endauth
            </div>



        </div>

    </section>

</div>
