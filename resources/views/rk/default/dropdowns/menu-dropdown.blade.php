@props([
    'position' => null, // Posición del dropdown
    'triggerClass' => '', // Clases adicionales para el trigger
])

@php
    $positionClasses = [
        'bottom-left' => 'left-0 top-full mt-1',
        'bottom-right' => 'right-0 top-full mt-1',
        'top-left' => 'left-0 bottom-full mb-1',
        'top-right' => 'right-0 bottom-full mb-1',
    ];
@endphp

<div class="relative inline-block max-w-4sm" x-data="{
    open: false,
    dropdownPosition: '{{ $position ?? '' }}',
    calculatePosition(el) {
        if ('{{ $position }}') {
            this.dropdownPosition = '{{ $position }}';
            return;
        }
        const rect = el.getBoundingClientRect();
        const midX = window.innerWidth / 2;
        const midY = window.innerHeight / 2;

        if (rect.top < midY && rect.left < midX) this.dropdownPosition = 'bottom-left';
        else if (rect.top < midY && rect.left >= midX) this.dropdownPosition = 'bottom-right';
        else if (rect.top >= midY && rect.left < midX) this.dropdownPosition = 'top-left';
        else this.dropdownPosition = 'top-right';
    }
}" @click.away="open = false">

    <!-- Trigger -->
    <div x-ref="trigger" @click="open = !open; if(open) calculatePosition($el)"
        class="flex items-center justify-between ">
        <div class="flex items-center space-x-1 {{ $triggerClass }} px-2" style="min-width: 2rem; max-width: 14.4rem; cursor: pointer;">
            {{ $trigger }}
            <svg class="w-3 h-3 ml-2 transition-transform duration-150" :class="open ? 'rotate-180' : ''" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>

        <!-- Flecha por defecto -->

    </div>

    <!-- Dropdown -->
    <div x-show="open" x-transition x-cloak style="min-width: 12rem; max-width: 16rem;"
        class="absolute bg-white dark:bg-zinc-800 rounded-2xl shadow-md
                py-2 px-2 min-w-max z-50 border border-zinc-200 dark:border-zinc-700"
        :class="{
            '{{ $positionClasses['bottom-left'] }}': dropdownPosition === 'bottom-left',
            '{{ $positionClasses['bottom-right'] }}': dropdownPosition === 'bottom-right',
            '{{ $positionClasses['top-left'] }}': dropdownPosition === 'top-left',
            '{{ $positionClasses['top-right'] }}': dropdownPosition === 'top-right',
        }">
        {{ $slot }}
    </div>
</div>
