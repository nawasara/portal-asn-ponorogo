<div>
    <section
        class="max-w-6xl mx-auto flex flex-col-reverse lg:flex-row items-center justify-between max-h-screen pt-32 lg:pt-52 gap-16">
        <div class="flex-1 text-center lg:text-left" data-aos="fade-up" data-aos-duration="1000">

            @auth
                <h1 class=" text-4xl md:text-5xl font-bold leading-tight mb-4">
                    Selamat Datang
                    <br>
                    <span class="text-emerald-600 dark:text-emerald-400 text-3xl">{{ auth()->user()->name }}</span>
                </h1>
                <p class="text-slate-600 dark:text-slate-300 text-lg mb-8 text-pretty">
                    Sekarang anda dapat mengakses seluruh aplikasi ASN Ponorogo dalam satu tempat. Cepat, aman, dan
                    terintegrasi.
                </p>
                <div class="flex flex-col md:flex-row justify-center lg:justify-start gap-4">
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
            @else
                <h1 class="text-3xl md:text-5xl font-bold leading-tight mb-4">
                    <span class="dark:text-white">Satu Portal, Banyak Layanan</span>
                    <br>
                    <span class="text-emerald-600 dark:text-emerald-400">Untuk ASN Ponorogo</span>
                </h1>
                <p class="text-slate-600 dark:text-slate-300 text-lg mb-8">
                    Akses seluruh aplikasi ASN dalam satu tempat. Cepat, aman, dan terintegrasi.
                </p>
                <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
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
            @endauth

        </div>

        {{-- <!-- Illustration --> --}}
        <div class="flex-1 flex justify-center" data-aos="fade-up" data-aos-duration="1500" data-aos-delay="100">
            <img src="{{asset('img/undraw_hello_ccwj.svg')}}" alt="ASN illustration"
                class="w-64 md:w-96 drop-shadow-lg select-none" loading="lazy">
        </div>
    </section>

    <div class="lg:py-52 py-16 text-center flex justify-center" data-aos="fade-down" data-aos-duration="1000">
        <a href="#app-lists" class="hover:-translate-y-3 hover:underline duration-400 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-chevron-down-icon lucide-chevron-down size-16 md:size-24 text-emerald-400 animate-pulse">
                <path d="m6 9 6 6 6-6" />
            </svg>
            <div class="text-xl text-gray-800 dark:text-white">Telusuri</div>
        </a>
    </div>
</div>