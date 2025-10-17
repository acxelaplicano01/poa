@props([
    'badge' => null,
    'variant' => 'primary'
])

@php
    $classes = match($variant) {
        'primary'   => 'bg-blue-500 text-white',
        'secondary' => 'bg-zinc-500 text-white',
        'success'   => 'bg-green-500 text-white',
        'warning'   => 'bg-yellow-500 text-black',
        'danger'    => 'bg-red-500 text-white',
        'info'      => 'bg-cyan-500 text-white',
        'purple'    => 'bg-purple-500 text-white',
        'pink'      => 'bg-pink-500 text-white',
        'dark'      => 'bg-zinc-800 text-white',
        'light'     => 'bg-zinc-200 text-gray-800',
        default     => 'bg-[var(--color-accent)] text-[var(--color-accent-foreground)]',
    };
@endphp

<span class="ml-auto rounded-full px-2 py-0.5 text-xs font-medium {{ $classes }}">
    {{ $badge }}
</span>
