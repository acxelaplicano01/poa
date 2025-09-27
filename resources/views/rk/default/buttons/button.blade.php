@props([
    'as' => 'button',
    'href' => '#',
    'color' => 'primary',     // primary, secondary, danger, red, yellow, blue, etc.
    'size' => 'md',
    'icon' => null,
    'iconSet' => 'o',
    'iconPosition' => 'left',
    'disabled' => false,
])

@php
$baseColors = [
    'primary' => 'bg-[var(--color-accent)] text-[var(--color-accent-foreground)]  hover:bg-[color-mix(in_oklab,_var(--color-accent),_transparent_10%)]',  
    'secondary' => 'bg-zinc-100 text-gray-800 border-gray-300 hover:bg-zinc-200 dark:bg-zinc-800 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-zinc-700',
    'danger' => 'bg-red-600 text-white border-transparent hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600',
    'success' => 'bg-green-600 text-white border-transparent hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600',
    
];



// Nuevos colores
$extraColors = [
    'red' => 'bg-red-500 text-white border border-red-600 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700',
    'yellow' => 'bg-yellow-400 text-black border border-yellow-500 hover:bg-yellow-500 dark:bg-yellow-500 dark:hover:bg-yellow-600',
    'blue' => 'bg-blue-500 text-white border border-blue-600 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700',
    'brown' => 'bg-amber-700 text-white border border-amber-800 hover:bg-amber-800 dark:bg-amber-700 dark:hover:bg-amber-800',
    'white' => 'bg-white text-black border border-gray-300 hover:bg-zinc-100 dark:bg-zinc-800 dark:text-gray-100 dark:border-gray-600',
];

// Combinar arrays
$colors = array_merge($baseColors, $extraColors);

$sizes = [
    'sm' => 'px-3 py-1 text-sm min-w-[80px]',
    'md' => 'px-4 py-2 text-sm min-w-[100px]',
    'lg' => 'px-5 py-3 text-base min-w-[120px]',
];

$baseClasses = 'flex items-center px-4 py-2 justify-center gap-2 rounded-2xl border font-medium transition-all';

$stateClasses = $disabled
    ? 'cursor-not-allowed opacity-50'
    : 'cursor-pointer hover:shadow-md';

$classes = $baseClasses . ' ' .
    ($colors[$color] ?? $colors['primary']) . ' ' .
    ($sizes[$size] ?? $sizes['md']) . ' ' .
    $stateClasses;

$iconComponent = $icon ? 'heroicon-' . ($iconSet ?? 'o') . '-' . $icon : null;
@endphp

@if($as === 'a')
    <a href="{{ $href }}" class="{{ $classes }}">
        @if($icon && $iconPosition === 'left')
            @svg($iconComponent, 'h-4 w-4')
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right')
            @svg($iconComponent, 'h-4 w-4')
        @endif
    </a>
@else
    <button type="button" @disabled($disabled) class="{{ $classes }}">
        @if($icon && $iconPosition === 'left')
            @svg($iconComponent, 'h-4 w-4')
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right')
            @svg($iconComponent, 'h-4 w-4')
        @endif
    </button>
@endif
