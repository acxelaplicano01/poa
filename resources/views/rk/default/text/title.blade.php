@props([
    'title' => null,
    'subtitle' => null,
    'class' => null,
    'color' => 'primary',
])

@php
    $titleClass = $class ?? "text-2xl font-bold text-{$color}-600";
    $colors = [
        'primary' => 'text-blue-600',
        'secondary' => 'text-gray-600',
        'success' => 'text-green-600',
        'danger' => 'text-red-600',
        'warning' => 'text-yellow-600',
        'info' => 'text-teal-600',
        'light' => 'text-gray-200',
        'dark' => 'text-gray-800',
    ];
    $colorClass = $colors[$color] ?? $colors['primary'];
@endphp

<div class="max-w-xl">
    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white leading-snug">
        {{ $title ?? 'Título de la Sección' }}
    </h1>

    {{-- Aquí usamos un slot para subtitle, si se pasa, se renderiza --}}
    <x-rk.default::text.description class="mt-2">
        {{ $subtitle ?? 'Descripción o subtítulo de la sección' }}
    </x-rk.default::text.description>

</div>
