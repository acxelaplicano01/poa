@props([
    'title',
    'href' => '#',
    'icon' => null,
    'badge' => null,
    'active' => false,
    'visible' => true,
    'badgePosition' => 'right',
    'as' => 'a', // elemento por defecto
])

@if ($visible)
    <div class="mb-1">
        {{-- Renderizar como el elemento indicado en 'as' --}}
        <{{ $as }}
            @if($as === 'a') href="{{ $href }}" @endif
            {{ $attributes->merge([
                'class' => 'h-10 lg:h-8 relative flex items-center gap-3 rounded-2xl py-0 text-start w-full px-3 my-px border border-transparent ' . 
                ($active
                    ? 'bg-[var(--color-zinc-200)] dark:bg-[var(--color-zinc-800)] dark:text-[var(--color-accent)] border-[var(--color-accent)]'
                    : 'text-gray-700 dark:text-gray-300 hover:bg-zinc-800/5 dark:hover:bg-zinc-200/5')
            ]) }}
        >
            {{-- Icono + título --}}
            <div class="flex items-center gap-2">
                <x-rk.default::icons.icon :icon="$icon" :active="$active" />
                <span>{{ $title }}</span>
            </div>

            {{-- Badge --}}
            @if ($badge || trim($badge ?? '') !== '')
                @if ($badgePosition === 'right')
                    <x-rk.default::badges.badge class="ml-2" variant="primary" :badge="$badge" />
                @else
                    <x-rk.default::badges.badge class="mr-2" variant="primary" :badge="$badge" />
                @endif
            @endif

            {{-- Slot badge --}}
            @isset($badgeSlot)
                {{ $badgeSlot }}
            @endisset
        </{{ $as }}>
    </div>
@endif
