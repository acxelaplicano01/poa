@props([
    'app' => [],
    'showProgress' => false,
    'header' => null,
])

@php

@endphp

<div class="p-4 group cursor-pointer transform transition-all duration-300 hover:scale-105 hover:-translate-y-1">
    <div
        class="py-4 overflow-hidden rounded-3xl border-2 border-gray-200 dark:border-gray-700 hover:border-primary-500/50 transition-all duration-300 bg-white dark:bg-zinc-800 shadow-sm hover:shadow-lg">
        <!-- Header -->
        {{ $header }}

        <!-- Content -->
        {{ $slot }}

        <!-- Footer -->
        {{ $footer ?? '' }}
    </div>
</div>
