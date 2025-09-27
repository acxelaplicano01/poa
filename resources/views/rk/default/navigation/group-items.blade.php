@props([
    'label' => null,
    'active' => false,
    'expandable' => false,
    'badge' => null,
    'badgePosition' => 'right'
])

@php
    $menuKey = "menu-" . \Str::slug($label ?? uniqid());
@endphp

<div 
    x-data="{ 
        expandable: {{ $expandable ? 'true' : 'false' }},
        open: true 
    }"
    x-init="
        if (expandable) {
            open = JSON.parse(localStorage.getItem('{{ $menuKey }}')) ?? true;
        }
    "
    class="space-y-1 py-1"
>
    {{-- Título del grupo --}}
    @if($label)
    <button
        @if($expandable)
            @click="open = !open; localStorage.setItem('{{ $menuKey }}', open)"
        @endif
        class="flex w-full items-center justify-between px-3 py-1 text-xs font-semibold uppercase tracking-wider
            {{ $active ? 'text-[color-mix(in_srgb,var(--color-accent)_70%,#333_30%)]
 dark:text-[var(--color-accent)]' : 'text-gray-500 dark:text-gray-400' }}
            cursor-pointer"
    >
        <span>{{ $label }}</span>
         {{-- Badge --}}
            @if (($badge || trim($badge ?? '') !== ''))
                @if ($badgePosition === 'right')
                    <x-rk.default::badges.badge class="ml-2" variant="blue" :badge="$badge" />
                @else
                    <x-rk.default::badges.badge class="mr-2" variant="blue" :badge="$badge" />
                @endif
            @endif

            {{-- Slot badge --}}
            @isset($badgeSlot)
                {{ $badgeSlot }}
            @endisset
        @if($expandable)
        <svg :class="open ? 'rotate-180' : ''" class="ml-4 h-4 w-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
        @endif
    </button>
    @endif

    {{-- Items --}}
    <div 
        
        x-show="!expandable || open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        class="mt-1 ml-1 pl-1"
        x-cloak
    >
        {{ $slot }}
    </div>
</div>
