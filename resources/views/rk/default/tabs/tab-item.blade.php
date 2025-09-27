@props([
    'href' => '#',
    'tab' => null,
    'active' => false,
    'positionBadge' => 'center-right',

    // badges
    'badgeNumber' => null, // number | text
    'statusBadge' => null, // success | warning | error | info | default
    'badgeText' => null, // text for statusBadge

    'badge' => null,
    'badgePosition' => 'right', // left | right
    'icon' => null, // heroicon-o-home-modern
])

@php
    $classActive = 'bg-[var(--color-zinc-200)] dark:bg-[var(--color-zinc-800)] dark:text-[var(--color-accent)] border-[var(--color-accent)]';
    $classInactive = 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white';
    $classBase =
        'h-10 lg:h-8 relative min-w-[120px] text-center rounded-xl px-3 py-2 text-sm font-medium transition-all duration-200 flex items-center justify-center gap-2 whitespace-nowrap';
@endphp

<a href="{{ $href }}" class="{{ $classBase }} {{ $active ? $classActive : $classInactive }}">
    <x-rk.default::icons.icon :icon="$icon" :active="$active" />
    {{-- Label principal --}}
    <span>{{ $tab }}</span>

    {{-- Badge numérico o de texto (propiedad o slot) --}}
    @if ($badgeNumber)
        <x-rk.default::badges.notification-badge :count="$badgeNumber" :position="$positionBadge" />
    @else
        {{ $badgeNumberSlot ?? '' }}
    @endif

    {{-- Status badge (con texto por prop o por slot) --}}
    @if ($badgeText)
        <x-rk.default::badges.status-badge class="ml-1" status="{{ $statusBadge }}">
            {{ $badgeText }}
        </x-rk.default::badges.status-badge>
    @else
        {{ $statusBadgeSlot ?? '' }}
    @endif

    @if (($badge || trim($badge ?? '') !== '') && $badgePosition === 'right')
        <x-rk.default::badges.badge class="ml-2" variant="primary" :badge="$badge" />
    @elseif (($badge || trim($badge ?? '') !== '') && $badgePosition === 'left')
        <x-rk.default::badges.badge class="mr-2" variant="primary" :badge="$badge" />
    @endif

    {{-- Slot badge --}}
    @isset($badgeSlot)
        {{ $badgeSlot }}
    @endisset
</a>
