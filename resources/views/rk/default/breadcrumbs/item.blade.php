@props([
    'href' => null,
    'active' => false,
    'separator' => '>',
])

<li class="inline-flex items-center">
    @if(!$active && $href)
        <a href="{{ $href }}"
           class="inline-flex items-center hover:text-zinc-700 dark:hover:text-zinc-200 transition-colors">
            {{ $slot }}
        </a>
    @else
        <span class="inline-flex items-center font-medium text-zinc-800 dark:text-zinc-100">
            {{ $slot }}
        </span>
    @endif

    @if(!$active)
        <span class="mx-2 text-zinc-400 dark:text-zinc-500">{{ $separator }}</span>
    @endif
</li>
