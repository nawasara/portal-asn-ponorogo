<div>
    <!-- Features -->
    <section class="max-w-6xl py-10 lg:py-14 mx-auto" id="integration">
        @if (Route::is('integrations'))
            <div class="flex items-center justify-center gap-x-3 md:hidden">
                <img src="{{ asset('img/logo.png') }}" alt="Portal ASN Ponorogo" class="size-6 w-auto" loading="lazy" />
                <span class="dark:text-white text-gray-700 font-semibold">Integrasi SSO Kisara</span>
            </div>
        @endif

        {{-- <!-- Grid --> --}}
        <div class="lg:grid lg:grid-cols-12 lg:gap-16 lg:items-center py-6 mb-12 sm:mb-0">
            <div class="lg:col-span-7" data-aos="slide-up" data-aos-offset="20" data-aos-duration="1000"
                date-aos-delay="2000">
                {{-- <!-- Grid --> --}}
                <div class="grid grid-cols-12 gap-2 sm:gap-6 items-center lg:-translate-x-10">
                    <div class="col-span-4">
                        <img class="rounded-xl"
                            src="https://images.unsplash.com/photo-1606868306217-dbf5046868d2?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=920&q=80"
                            alt="Features Image">
                    </div>
                    {{-- <!-- End Col --> --}}

                    <div class="col-span-3">
                        <img class="rounded-xl"
                            src="https://images.unsplash.com/photo-1605629921711-2f6b00c6bbf4?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=920&q=80"
                            alt="Features Image">
                    </div>
                    {{-- <!-- End Col --> --}}

                    <div class="col-span-5">
                        <img class="rounded-xl"
                            src="https://images.unsplash.com/photo-1600194992440-50b26e0a0309?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=920&q=80"
                            alt="Features Image">
                    </div>
                    {{-- <!-- End Col --> --}}
                </div>
                {{-- <!-- End Grid --> --}}
            </div>
            {{-- <!-- End Col --> --}}

            <div class="mt-5 sm:mt-10 lg:mt-0 lg:col-span-5" data-aos="slide-up" data-aos-duration="1000"
                date-aos-delay="3000">
                <div class="space-y-6 sm:space-y-8">
                    <!-- Title -->
                    <div class="space-y-2 md:space-y-4">
                        <h2 class="font-bold text-3xl lg:text-4xl text-gray-800 dark:text-neutral-200">
                            Butuh Integrasi?
                        </h2>
                        <p class="text-gray-500 dark:text-neutral-500">
                            Kami dapat membantu anda untuk integrasi dengan Sistem Single Sign On (SSO).
                        </p>
                    </div>
                    <!-- End Title -->

                    <!-- List -->
                    <ul class="space-y-2 sm:space-y-4">

                        <li class="flex gap-x-3">
                            <div class="">
                                <a href="https://wa.me/6285126061182"
                                    class="px-6 py-3 flex items-center gap-x-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-lg font-medium transition transform hover:-translate-y-0.5 hover:shadow-lg">

                                    Hubungi Kami
                                </a>
                            </div>
                        </li>

                    </ul>
                    <!-- End List -->
                </div>
            </div>
            <!-- End Col -->
        </div>
        <!-- End Grid -->
    </section>
    <!-- End Features -->
</div>