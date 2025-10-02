@if (Auth::check())
    <button x-cloak @click="open = !open"
        class="flex items-center gap-2 focus:outline-none group shadow-md rounded-full bg-white dark:bg-gray-800 px-2 py-1 hover:bg-blue-50 dark:hover:bg-gray-700 transition">
        <div
            class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-base group-hover:ring-2 group-hover:ring-blue-400 transition">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <span
            class="font-medium text-gray-800 dark:text-gray-100 text-sm max-w-[120px] truncate">{{ Auth::user()->name }}</span>
        <svg class="w-4 h-4 text-gray-500 dark:text-gray-300 group-hover:text-blue-600 transition" fill="none"
            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    <div x-cloak x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-55 w-55 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-2 border border-gray-100 dark:border-gray-700 origin-top-right">
        <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700">
            <div class="font-semibold text-gray-800 dark:text-gray-100 text-sm">{{ Auth::user()->name }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Logout</button>
        </form>
        <button @click="window.location='{{ route('mfa.reset') }}'"
            class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Reset
            MFA</button>

    </div>
@else
    <a href="{{ url('/login') }}"
        class="flex items-center gap-2 shadow-md rounded-full bg-white dark:bg-gray-800 px-2 py-1 hover:bg-blue-50 dark:hover:bg-gray-700 transition">
        <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-base">
            L
        </div>
        <span class="font-medium text-gray-800 dark:text-gray-100 text-sm">Login</span>
    </a>
@endif
