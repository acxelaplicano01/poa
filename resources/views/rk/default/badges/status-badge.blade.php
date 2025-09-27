@props([
    'status' => 'default',
    'size' => 'md',
    'variant' => 'solid'
])

@php
$statusClasses = [
    'success' => $variant === 'outline' 
        ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800' 
        : 'bg-emerald-200 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',   // suave
    'warning' => $variant === 'outline' 
        ? 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-900/20 dark:text-amber-400 dark:border-amber-800' 
        : 'bg-amber-200 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400',
    'error' => $variant === 'outline' 
        ? 'bg-red-50 text-red-700 border-red-200 dark:bg-red-900/20 dark:text-red-400 dark:border-red-800' 
        : 'bg-red-200 text-red-800 dark:bg-red-900/30 dark:text-red-400',
    'info' => $variant === 'outline' 
        ? 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-800' 
        : 'bg-blue-200 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
    'default' => $variant === 'outline' 
        ? 'bg-zinc-50 text-gray-700 border-gray-200 dark:bg-zinc-800 dark:text-gray-300 dark:border-gray-700' 
        : 'bg-zinc-200 text-gray-800 dark:bg-zinc-800/50 dark:text-gray-300'
];

$sizeClasses = [
    'sm' => 'px-2 py-1 text-xs',
    'md' => 'px-3 py-1.5 text-sm',
    'lg' => 'px-4 py-2 text-base'
];

$baseClasses = 'inline-flex items-center font-medium rounded-full transition-all duration-200 hover:scale-105';
$borderClass = $variant === 'outline' ? 'border' : '';
@endphp

<span {{ $attributes->merge(['class' => $baseClasses . ' ' . $statusClasses[$status] . ' ' . $sizeClasses[$size] . ' ' . $borderClass]) }}>
    {{ $slot }}
</span>
