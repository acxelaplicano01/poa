@if(!isset($node) || $node->getIsHidden())
    @php
        return;
    @endphp
@endif
<flux:navmenu.item href="{{ $node->getUrl() }}" >
    {{ $node->getLabel() }}
</flux:navmenu.item>
