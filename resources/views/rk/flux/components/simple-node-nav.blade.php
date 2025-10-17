@props([
    'node' => null,
])
@if(!isset($node) || $node->getIsHidden())
    @php
        return;
    @endphp
@endif

<flux:navbar.item :href="$node->getUrl()" :icon="$node->getHeroIcon()" :current="$node->isActive()" 
    
     badge="{{ $node->getFinalBage() }}" badge-color="pink"
    

    wire:navigate>
    {{ $node->label }}

</flux:navbar.item>
