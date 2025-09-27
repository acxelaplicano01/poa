@props([
    'variant' => 'primary',
    'size' => 'md',
    'loading' => false,
    'disabled' => false,
    'icon' => null,
    'iconPosition' => 'left'
])

@php
$variantClasses = [
    'primary' => 'bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600 text-white shadow-lg hover:shadow-xl',
    'secondary' => 'bg-white dark:bg-zinc-800 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 hover:bg-zinc-50 dark:hover:bg-zinc-700',
    'success' => 'bg-gradient-to-r from-emerald-500 to-green-500 hover:from-emerald-600 hover:to-green-600 text-white shadow-lg hover:shadow-xl',
    'danger' => 'bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white shadow-lg hover:shadow-xl',
    'ghost' => 'text-gray-700 dark:text-gray-300 hover:bg-zinc-100 dark:hover:bg-zinc-800',
    
];

$sizeClasses = [
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-6 py-3 text-base',
    'xl' => 'px-8 py-4 text-lg'
];

$baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed hover:-translate-y-0.5';
@endphp

<button 
    {{ $attributes->merge([
        'class' => $baseClasses . ' ' . $variantClasses[$variant] . ' ' . $sizeClasses[$size],
        'disabled' => $disabled || $loading
    ]) }}
>
    @if($loading)
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Cargando...
    @else
        @if($icon && $iconPosition === 'left')
            <span class="mr-2 w-4 h-4">{!! $icon !!}</span>
        @endif
        
        {{ $slot }}
        
        @if($icon && $iconPosition === 'right')
            <span class="ml-2 w-4 h-4">{!! $icon !!}</span>
        @endif
    @endif
</button>
