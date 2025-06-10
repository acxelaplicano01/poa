@props(['active'])

@php
    $classes = ($active ?? false)
        ? 'transition duration-100 ease-in-out w-5 h-5 ml-2 text-indigo-500 dark:text-white group'
        : 'transition duration-100 ease-in-out w-5 h-5 ml-2 text-zinc-500 dark:text-zinc-400 dark:group-hover:text-zinc-300 group-hover:text-zinc-800 group';
@endphp

<svg {{ $attributes->merge(['class' => $classes]) }} aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
    height="24" fill="none" viewBox="0 0 24 24">
    {{ $slot }}
</svg>