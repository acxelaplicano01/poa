@props(['active'])

@php
    // Si no se proporciona el estado "active", mantener el comportamiento existente
    $isActive = $active ?? false;
@endphp

<a {{ $attributes->merge(['class' =>
    ($isActive  ? 'bg-indigo-100 flex transition duration-100 ease-in-out dark:bg-white/[7%] items-center p-1 text-indigo-600 rounded-lg dark:text-white border-indigo-200 border dark:border-zinc-800  dark:hover:text-white dark:hover:bg-white/[7%] group' 
    : 'bg-zinc-0 flex transition duration-100 ease-in-out items-center p-1 text-zinc-500 hover:text-zinc-800 rounded-lg dark:text-zinc-400 dark:hover:text-zinc-300 hover:bg-zinc-800/5 dark:hover:bg-white/[7%] group')]) }}>
    {{ $slot }}
</a>