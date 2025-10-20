<div>
    {{-- Support Section --}}
    <section class="max-w-6xl py-10 lg:py-14 mx-auto" id="support" data-aos="fade-up">

        @if (Route::is('supports'))
            <div class="flex items-center justify-center gap-x-3 md:hidden">
                <img src="{{ asset('img/logo.png') }}" alt="Portal ASN Ponorogo" class="size-6 w-auto" loading="lazy" />
                <span class="dark:text-white text-gray-700 font-semibold">Bantuan SSO Kisara</span>
            </div>
        @endif

        <div class="py-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white md:text-4xl text-center mx-auto py-6">
                Mengalami Kendala?
            </h2>
        </div>

        <div class="grid sm:grid-cols-3 lg:grid-cols-3 items-center gap-6">

            {{-- Form Bantuan Card --}}
            <a class="group flex bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition justify-between min-h-40"
                href="https://asn.ponorogo.go.id/bantuan" target="_blank" data-aos="zoom-in" data-aos-duration="1000"
                date-aos-delay="3000">

                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-file-text-icon lucide-file-text shrink-0 size-8 text-gray-800 dark:text-white mt-0.5 me-6">
                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                    <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                    <path d="M10 9H8" />
                    <path d="M16 13H8" />
                    <path d="M16 17H8" />
                </svg>

                <div>
                    <div>
                        <h3 class="block font-bold text-gray-800 dark:text-white">
                            Formulir Aduan Single Sign On (SSO)
                        </h3>
                        <p class="text-gray-600 dark:text-neutral-400">
                            Isi formulir aduan dan tim akan menghubungi Anda
                        </p>
                    </div>

                    <p
                        class="mt-3 inline-flex items-center gap-x-1 text-sm font-semibold text-gray-800 dark:text-neutral-200">
                        Isi Formulir
                        <svg class="shrink-0 size-4 transition ease-in-out group-hover:translate-x-1 group-focus:translate-x-1"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </p>
                </div>
            </a>

            {{-- Panduan Card --}}
            <a class="group flex bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition justify-between min-h-40"
                href="{{ \App\Constants\Constants::DOC_URL }}" target="_blank" data-aos="zoom-in"
                data-aos-duration="1000" date-aos-delay="3000">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-book-open-text-icon lucide-book-open-text shrink-0 size-8 text-gray-800 dark:text-white mt-0.5 me-6">
                    <path d="M12 7v14" />
                    <path d="M16 12h2" />
                    <path d="M16 8h2" />
                    <path
                        d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z" />
                    <path d="M6 12h2" />
                    <path d="M6 8h2" />
                </svg>

                <div>
                    <div>
                        <h3 class="block font-bold text-gray-800 dark:text-white">
                            Panduan
                        </h3>
                        <p class="text-gray-600 dark:text-neutral-400">
                            Dapatkan panduan berupa PDF pengaktifan MFA Single Sign On (SSO)
                        </p>
                    </div>

                    <p
                        class="mt-3 inline-flex items-center gap-x-1 text-sm font-semibold text-gray-800 dark:text-neutral-200">
                        Dapatkan panduan
                        <svg class="shrink-0 size-4 transition ease-in-out group-hover:translate-x-1 group-focus:translate-x-1"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </p>
                </div>
            </a>
            <!-- End Card -->

            {{-- Reset MFA Card --}}
            <a class="group flex bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition justify-between min-h-40"
                href="" target="_blank" data-aos="zoom-in" data-aos-duration="1000" date-aos-delay="3000">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-settings-icon lucide-settings shrink-0 size-8 text-gray-800 dark:text-white mt-0.5 me-6">
                    <path
                        d="M9.671 4.136a2.34 2.34 0 0 1 4.659 0 2.34 2.34 0 0 0 3.319 1.915 2.34 2.34 0 0 1 2.33 4.033 2.34 2.34 0 0 0 0 3.831 2.34 2.34 0 0 1-2.33 4.033 2.34 2.34 0 0 0-3.319 1.915 2.34 2.34 0 0 1-4.659 0 2.34 2.34 0 0 0-3.32-1.915 2.34 2.34 0 0 1-2.33-4.033 2.34 2.34 0 0 0 0-3.831A2.34 2.34 0 0 1 6.35 6.051a2.34 2.34 0 0 0 3.319-1.915" />
                    <circle cx="12" cy="12" r="3" />
                </svg>

                <div>
                    <div>
                        <h3 class="block font-bold text-gray-800 dark:text-white">
                            Reset MFA Single Sign On (SSO)
                        </h3>
                        <p class="text-gray-600 dark:text-neutral-400">
                            Layanan untuk mereset Multi-Factor Authentication (MFA) SSO Anda secara mandiri
                        </p>
                    </div>

                    <p
                        class="mt-3 inline-flex items-center gap-x-1 text-sm font-semibold text-gray-800 dark:text-neutral-200">
                        Reset MFA Sekarang
                        <svg class="shrink-0 size-4 transition ease-in-out group-hover:translate-x-1 group-focus:translate-x-1"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </p>
                </div>
            </a>
            {{-- End Card --}}

        </div>
    </section>
    <!-- End Icon Blocks -->
</div>
