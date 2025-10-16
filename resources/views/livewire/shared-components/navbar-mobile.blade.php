<div>

    <!-- Mobile Bottom Navigation -->
    <nav
        class="sticky bottom-0 left-0 right-0 z-50 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-t border-slate-200 dark:border-slate-700 shadow-lg md:hidden">
        <ul class="flex justify-around items-center h-16 text-slate-600 dark:text-slate-300">
            <li>
                <a @if (Route::is('index')) href="#hero" @else href="{{ url('/#hero') }}" wire:navigate.hover @endif
                    class="flex flex-col items-center justify-center w-12 h-12 transition-all hover:text-emerald-500 dark:hover:text-emerald-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-home" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        <polyline points="9 22 9 12 15 12 15 22" />
                    </svg>
                    <span class="text-xs">Beranda</span>
                </a>
            </li>

            <li>
                <a @if (Route::is('index')) href="#app-lists" @else href="{{ url('/apps') }}" wire:navigate.hover @endif
                    class="flex flex-col items-center justify-center w-12 h-12 transition-all hover:text-emerald-500 dark:hover:text-emerald-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-grid" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7" />
                        <rect x="14" y="3" width="7" height="7" />
                        <rect x="14" y="14" width="7" height="7" />
                        <rect x="3" y="14" width="7" height="7" />
                    </svg>
                    <span class="text-xs">Aplikasi</span>
                </a>
            </li>

            <li>
                <a @if (Route::is('index')) href="#support" @else href="{{ url('/supports') }}" wire:navigate.hover
                @endif
                    class="flex flex-col items-center justify-center w-12 h-12 transition-all hover:text-emerald-500 dark:hover:text-emerald-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-life-buoy" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <circle cx="12" cy="12" r="4" />
                        <line x1="4.93" y1="4.93" x2="9.17" y2="9.17" />
                        <line x1="14.83" y1="14.83" x2="19.07" y2="19.07" />
                        <line x1="14.83" y1="9.17" x2="19.07" y2="4.93" />
                        <line x1="14.83" y1="9.17" x2="19.07" y2="4.93" />
                        <line x1="9.17" y1="14.83" x2="4.93" y2="19.07" />
                    </svg>
                    <span class="text-xs">Bantuan</span>
                </a>
            </li>

            <li>
                <a @if (Route::is('index')) href="#integration" @else href="/integrations" wire:navigate.hover @endif
                    class="flex flex-col items-center justify-center w-12 h-12 transition-all hover:text-emerald-500 dark:hover:text-emerald-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-toy-brick-icon lucide-toy-brick">
                        <rect width="18" height="12" x="3" y="8" rx="1" />
                        <path d="M10 8V5c0-.6-.4-1-1-1H6a1 1 0 0 0-1 1v3" />
                        <path d="M19 8V5c0-.6-.4-1-1-1h-3a1 1 0 0 0-1 1v3" />
                    </svg>
                    <span class="text-xs">Integrasi</span>
                </a>
            </li>

            <li>
                <a href="/profiles" wire:navigate.hover
                    class="flex flex-col items-center justify-center w-12 h-12 transition-all hover:text-emerald-500 dark:hover:text-emerald-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-user" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M4 21v-2a4 4 0 0 1 3-3.87" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    <span class="text-xs">@auth Profil @else Masuk @endauth</span>
                </a>
            </li>
        </ul>
    </nav>

</div>