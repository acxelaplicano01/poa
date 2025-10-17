@props([
    'node' => null,
])
@if (!isset($node) || $node->getIsHidden())
    @php
        return;
    @endphp
@endif

@props([
    'node' => null,
])

@if ($node->makerMethod !== 'makeLink')
    <flux:navlist.item :href="$node->getUrl()" :icon="$node->getHeroIcon()" :current="$node->isActive()" wire:navigate.hover
        badge="{{ $node->getFinalBage() }}" badge-color="pink">
        {{ $node->label }}
    </flux:navlist.item>
@endif

@if ($node->makerMethod === 'makeLink')
    <flux:navlist.item 
        :href="$node->getUrl()" 
        :icon="$node->getHeroIcon()" 
        :current="$node->isActive()" 
        badge="{{ $node->getFinalBage() }}" 
        badge-color="pink"
        target="_blank"   {{-- Esto hace que abra en nueva pestaña --}}
    >
        {{ $node->label }}
    </flux:navlist.item>
@endif
