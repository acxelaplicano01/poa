@props(['active'])

@php
    $classes = ($active ?? false)
        ? 'transition duration-100 ease-in-out text-indigo-500 dark:text-white group'
        : 'transition duration-100 ease-in-out text-zinc-500 dark:text-zinc-400 dark:group-hover:text-zinc-300 group-hover:text-zinc-800 group';
@endphp

<svg {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</svg>