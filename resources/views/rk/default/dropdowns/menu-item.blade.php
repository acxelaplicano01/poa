@props([
    'href' => null,
    'icon' => null,
    'danger' => false
])

@php
$baseClasses = 'flex items-center w-full  px-2 py-2 text-sm transition-colors duration-150 rounded-2xl';
$colorClasses = $danger 
    ? 'text-red-700 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20' 
    : 'text-gray-700 dark:text-gray-300 hover:bg-zinc-100 dark:hover:bg-zinc-700';
@endphp

@if($href)
    <a href="{{ $href }}" class="{{ $baseClasses }} {{ $colorClasses }}">
        @if($icon)
                @svg($icon, 'h-4 w-4 mr-2')
        @endif
        {{ $slot }}
    </a>
@else
    <button class="{{ $baseClasses }} {{ $colorClasses }}">
        @if($icon)
             @svg($icon, 'h-4 w-4')
        @endif
        {{ $slot }}
    </button>
@endif
