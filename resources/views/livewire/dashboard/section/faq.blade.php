<div>
    <section id="faq" class="py-12 lg:py-16">
        <div class="grid md:grid-cols-5 gap-10">
            <div class="md:col-span-2" data-aos="slide-up" data-aos-duration="1000">
                <div class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 mb-2">FAQ</div>
                <h2 class="text-3xl md:text-4xl font-bold tracking-tight leading-tight dark:text-white">
                    Pertanyaan yang <br><span class="text-gradient">sering diajukan</span>
                </h2>
                <p class="mt-4 text-slate-600 dark:text-slate-400 hidden md:block">
                    Tidak menemukan jawaban?
                    <a href="#support" class="text-emerald-600 dark:text-emerald-400 font-semibold hover:underline">Hubungi tim kami</a>.
                </p>
            </div>

            <div class="md:col-span-3 space-y-3" data-aos="slide-up" data-aos-duration="1500">
                @foreach ($this->availableFaq() as $index => $faq)
                    <details class="glass-card gradient-border rounded-2xl group" wire:key="{{ $faq['id'] }}"
                        {{ $index === 0 ? 'open' : '' }}>
                        <summary
                            class="cursor-pointer list-none p-5 flex items-center justify-between gap-4 md:text-lg font-semibold dark:text-white hover:text-emerald-600 dark:hover:text-emerald-400 transition">
                            <span>{{ $faq['title'] }}</span>
                            <span
                                class="shrink-0 size-8 rounded-full glass-card grid place-items-center group-open:rotate-180 transition">
                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M6 9l6 6 6-6" />
                                </svg>
                            </span>
                        </summary>
                        <div class="px-5 pb-5 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            {!! $faq['description'] !!}
                        </div>
                    </details>
                @endforeach
            </div>
        </div>
    </section>
</div>
