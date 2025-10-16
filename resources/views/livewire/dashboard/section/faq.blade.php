<div>
    {{-- <!-- FAQ --> --}}
    <div class="md:max-w-6xl py-10 lg:py-14 mx-auto px-4">
        <!-- Grid -->
        <div class="grid md:grid-cols-5 gap-10">
            <div class="md:col-span-2" data-aos="slide-up" data-aos-duration="1000">
                <div class="max-w-xs">
                    <h2 class="text-2xl font-bold md:text-4xl md:leading-tight dark:text-white">
                        Pertanyaan yang Sering Diajukan
                    </h2>
                    <p class="mt-1 hidden md:block text-gray-600 dark:text-neutral-400">
                        Jawaban untuk pertanyaan yang paling sering diajukan
                    </p>
                </div>
            </div>
            <!-- End Col -->

            <div class="md:col-span-3" data-aos="slide-up" data-aos-duration="2000" date-aos-delay="3000">
                <!-- Accordion -->
                <div class="hs-accordion-group divide-y divide-gray-200 dark:divide-neutral-700">

                    @foreach ($this->availableFaq() as $faq)
                        <div class="hs-accordion pt-6 pb-3" id="{{$faq['id']}}" wire:key='{{$faq['id']}}'>
                            <button
                                class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full md:text-lg font-semibold text-start text-gray-800 rounded-lg transition hover:text-gray-500 focus:outline-hidden focus:text-gray-500 dark:text-neutral-200 dark:hover:text-neutral-400 dark:focus:text-neutral-400"
                                aria-expanded="false" aria-controls="portal-{{$faq['id']}}">
                                {{$faq['title']}}
                                <svg class="hs-accordion-active:hidden block shrink-0 size-5 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                                <svg class="hs-accordion-active:block hidden shrink-0 size-5 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="m18 15-6-6-6 6" />
                                </svg>
                            </button>
                            <div id="portal-{{$faq['id']}}"
                                class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300"
                                role="region" aria-labelledby="{{$faq['id']}}">
                                <p class="text-gray-600 dark:text-neutral-400">
                                    {!! $faq['description'] !!}
                                </p>
                            </div>
                        </div>
                    @endforeach

                </div>
                <!-- End Accordion -->
            </div>
            <!-- End Col -->
        </div>
        <!-- End Grid -->
    </div>
    <!-- End FAQ -->
</div>