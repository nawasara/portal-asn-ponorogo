<div>
    <nav class="fixed bottom-3 inset-x-3 z-50 glass rounded-2xl shadow-xl shadow-slate-900/10 md:hidden">
        <ul class="grid grid-cols-5 h-16 text-[10px] font-medium text-slate-600 dark:text-slate-300">
            <li>
                <a @if (Route::is('index')) href="#hero" @else href="{{ url('/#hero') }}" wire:navigate.hover @endif
                    class="flex flex-col items-center justify-center gap-1 h-full rounded-2xl hover:text-emerald-600 dark:hover:text-emerald-400 transition">
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        <polyline points="9 22 9 12 15 12 15 22" />
                    </svg>
                    <span>Beranda</span>
                </a>
            </li>

            <li>
                <a @if (Route::is('index')) href="#app-lists" @else href="{{ url('/apps') }}" wire:navigate.hover @endif
                    class="flex flex-col items-center justify-center gap-1 h-full rounded-2xl hover:text-emerald-600 dark:hover:text-emerald-400 transition">
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7" />
                        <rect x="14" y="3" width="7" height="7" />
                        <rect x="14" y="14" width="7" height="7" />
                        <rect x="3" y="14" width="7" height="7" />
                    </svg>
                    <span>Aplikasi</span>
                </a>
            </li>

            <li>
                <a @if (Route::is('index')) href="#support" @else href="{{ url('/supports') }}" wire:navigate.hover @endif
                    class="flex flex-col items-center justify-center gap-1 h-full rounded-2xl hover:text-emerald-600 dark:hover:text-emerald-400 transition">
                    <svg @click="$dispatch('set-arrow');" class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <circle cx="12" cy="12" r="4" />
                        <line x1="4.93" y1="4.93" x2="9.17" y2="9.17" />
                        <line x1="14.83" y1="14.83" x2="19.07" y2="19.07" />
                        <line x1="14.83" y1="9.17" x2="19.07" y2="4.93" />
                        <line x1="9.17" y1="14.83" x2="4.93" y2="19.07" />
                    </svg>
                    <span>Bantuan</span>
                </a>
            </li>

            <li>
                <a @if (Route::is('index')) href="#integration" @else href="/integrations" wire:navigate.hover @endif
                    class="flex flex-col items-center justify-center gap-1 h-full rounded-2xl hover:text-emerald-600 dark:hover:text-emerald-400 transition">
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="18" height="12" x="3" y="8" rx="1" />
                        <path d="M10 8V5c0-.6-.4-1-1-1H6a1 1 0 0 0-1 1v3" />
                        <path d="M19 8V5c0-.6-.4-1-1-1h-3a1 1 0 0 0-1 1v3" />
                    </svg>
                    <span>Integrasi</span>
                </a>
            </li>

            <li>
                @auth
                    <a href="/profiles" wire:navigate.hover
                        class="flex flex-col items-center justify-center gap-1 h-full rounded-2xl hover:text-emerald-600 dark:hover:text-emerald-400 transition">
                        <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M4 21v-2a4 4 0 0 1 3-3.87" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                        <span>Profil</span>
                    </a>
                @else
                    <a href="/login"
                        class="flex flex-col items-center justify-center gap-1 h-full rounded-2xl hover:text-emerald-600 dark:hover:text-emerald-400 transition">
                        <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10 17l5-5-5-5" />
                            <path d="M15 12H3" />
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                        </svg>
                        <span>Masuk</span>
                    </a>
                @endauth
            </li>
        </ul>
    </nav>
</div>
