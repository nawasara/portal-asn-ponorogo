<div>
    <section id="hero"
        class="max-w-6xl mx-auto flex flex-col-reverse lg:flex-row items-center justify-between max-h-screen pt-16 sm:pt-32 lg:pt-52 gap-10 md:gap-16">
        <div class="flex-1 text-center lg:text-left" data-aos="fade-up" data-aos-duration="1000">

            <h1 class="dark:text-white text-4xl md:text-5xl font-bold leading-tight mb-8 sm:mb-4">

                @auth
                    <span class="dark:text-white">Selamat Datang</span>
                    <br>
                    <span class="text-emerald-600 dark:text-emerald-400">{{ auth()->user()->name }}</span>
                @else

                    <span class="dark:text-white">Satu Portal, Banyak Layanan</span>
                    <br>
                    <span class="text-emerald-600 dark:text-emerald-400">Untuk ASN Ponorogo</span>
                @endauth
            </h1>

            <p class="text-slate-600 dark:text-slate-300 text-lg mb-16 sm:mb-8 text-pretty">
                @auth <span>Sekarang anda dapat mengakses</span> @else <span>Akses</span> @endauth seluruh aplikasi ASN
                Ponorogo dalam satu tempat. Cepat, aman, dan
                terintegrasi.
            </p>

            <div class="flex flex-col md:flex-row justify-center lg:justify-start gap-4">
                @auth
                    <button
                        class="px-6 py-3 bg-emerald-600 flex text-center justify-center items-center gap-x-2 hover:bg-emerald-500 text-white rounded-lg text-lg font-medium transition transform hover:-translate-y-0.5 hover:shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-search-icon lucide-search">
                            <path d="m21 21-4.34-4.34" />
                            <circle cx="11" cy="11" r="8" />
                        </svg>
                        Cari Aplikasi
                    </button>
                @else

                    <a href="/login"
                        class="px-6 py-3 flex items-center justify-center gap-x-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-lg font-medium transition transform hover:-translate-y-0.5 hover:shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-log-in-icon lucide-log-in">
                            <path d="m10 17 5-5-5-5" />
                            <path d="M15 12H3" />
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                        </svg>
                        Masuk Sekarang
                    </a>
                @endauth

                <a href="#support"
                    class="px-6 py-3 bg-slate-300 flex text-center justify-center items-center gap-x-2 hover:bg-slate-400 dark:text-slate-800 rounded-lg text-lg font-medium transition transform hover:-translate-y-0.5 hover:shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-info-icon lucide-info">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 16v-4" />
                        <path d="M12 8h.01" />
                    </svg>
                    Bantuan
                </a>
            </div>

        </div>

        {{-- <!-- Illustration --> --}}
        <div class="justify-center" data-aos="fade-up" data-aos-duration="1500" data-aos-delay="100">
            <img src="{{asset('img/undraw_hello_ccwj.svg')}}" alt="ASN illustration"
                class="sm:w-64 hidden sm:flex md:w-96 drop-shadow-lg select-none" loading="lazy">
            <img src="{{asset('img/logo.png')}}" alt="ASN illustration"
                class="sm:hidden flex w-20 drop-shadow-lg select-none" loading="lazy">
        </div>
    </section>

    <div class="lg:pt-40 pt-24 text-center hidden sm:block" data-aos="fade-down" data-aos-duration="1000">
        {{--
        <hr class="text-gray-300 dark:text-gray-700 mb-16"> --}}
        <div
            class="flex mx-auto justify-center gap-x-16 bg-gray-200 dark:bg-slate-800 pt-2 2xl:pt-20 sm:rounded-tr-4xl sm:rounded-tl-4xl">
            <a href="#app-lists" class="hover:bg-gray-300 dark:hover:text-gray-800 select-none no-underline flex bg-transparent rounded-lg py-4 px-8
                 items-center gap-x-3 duration-400 transition-all text-gray-800 dark:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-grid" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7" />
                    <rect x="14" y="3" width="7" height="7" />
                    <rect x="14" y="14" width="7" height="7" />
                    <rect x="3" y="14" width="7" height="7" />
                </svg>
                <div class="text-xl">Aplikasi</div>
            </a>
            <a href="#integration" class="hover:bg-gray-300 dark:hover:text-gray-800 select-none no-underline flex bg-transparent rounded-lg py-4 px-8
                 items-center gap-x-3 duration-400 transition-all text-gray-800 dark:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-toy-brick-icon lucide-toy-brick">
                    <rect width="18" height="12" x="3" y="8" rx="1" />
                    <path d="M10 8V5c0-.6-.4-1-1-1H6a1 1 0 0 0-1 1v3" />
                    <path d="M19 8V5c0-.6-.4-1-1-1h-3a1 1 0 0 0-1 1v3" />
                </svg>
                <div class="text-xl">Integrasi</div>
            </a>
        </div>
    </div>
</div>