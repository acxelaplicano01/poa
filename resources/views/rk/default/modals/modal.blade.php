@props([
    'show' => false,
    'size' => 'md', // xs, sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, full, screen
    'closeOnEsc' => true,
    'closeOnClickOutside' => true,
    'closeButton' => true,
    'closePosition' => 'right',
    'headerHeight' => 'h-16'
])

@php
$maxWidthClasses = [
    'xs' => 'max-w-xs',
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
    '3xl' => 'max-w-3xl',
    '4xl' => 'max-w-4xl',
    '5xl' => 'max-w-5xl',
    'full' => 'max-w-full',
    'screen' => 'w-screen'
];
@endphp

<x-rk.default::modals.base-modal
    :show="$show"
    :close-on-esc="$closeOnEsc"
    :close-on-click-outside="$closeOnClickOutside"
    :close-button="$closeButton"
    :close-position="$closePosition"
>
    <!-- Trigger -->
    @isset($trigger)
        <x-slot name="trigger">
            {{ $trigger }}
        </x-slot>
    @endisset

    <!-- Modal estilizado -->
    <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 w-full {{ $maxWidthClasses[$size] }} overflow-hidden">

        <!-- Header -->
        <div class="px-6 {{ $headerHeight }} flex items-center justify-between border-b border-gray-200 dark:border-gray-700">
            {{ $header ?? '' }}
        </div>

        <!-- Content -->
        <div class="px-6 py-4">
            {{ $slot }}
        </div>

        <!-- Footer -->
        @isset($footer)
            <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $footer }}
            </div>
        @endisset
    </div>
</x-rk.default::modals.base-modal>
