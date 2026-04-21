@props([
    'fixed' => true,
])

<div {{ $attributes->class([
    $fixed ? 'fixed' : 'absolute',
    'inset-0 -z-10 overflow-hidden pointer-events-none',
]) }}>
    <div class="absolute top-[-10%] left-[-5%] w-[45rem] h-[45rem] rounded-full bg-emerald-400/30 dark:bg-emerald-500/20 blur-3xl animate-blob-slow"></div>
    <div class="absolute top-[20%] right-[-10%] w-[40rem] h-[40rem] rounded-full bg-sky-400/25 dark:bg-sky-500/15 blur-3xl animate-blob-med"></div>
    <div class="absolute bottom-[-15%] left-[30%] w-[38rem] h-[38rem] rounded-full bg-teal-300/25 dark:bg-teal-400/15 blur-3xl animate-blob-fast"></div>
</div>
