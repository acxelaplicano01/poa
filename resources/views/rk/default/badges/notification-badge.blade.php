@props([
    'count' => 0,
    'max' => 99,
    'dot' => false,
    'position' => 'top-right'
])

@php
$positionClasses = [
    'top-right' => '-top-2 -right-2',
    'top-left' => '-top-2 -left-2',
    'bottom-right' => '-bottom-2 -right-2',
    'bottom-left' => '-bottom-2 -left-2',
    'center-right' => 'top-1/2 -right-2 transform -translate-y-1/2',
    'center-left' => 'top-1/2 -left-2 transform -translate-y-1/2',
];

$displayCount = $count > $max ? $max . '+' : $count;
@endphp

<div class="relative inline-block">
    {{ $slot }}
    
    @if($count > 0 || $dot)
        <span class="absolute {{ $positionClasses[$position] }} flex items-center justify-center">
            @if($dot)
                <span class="w-3 h-3 bg-[var(--color-accent)] text-[var(--color-accent-foreground)] rounded-full animate-pulse"></span>
            @else
                <span class="min-w-[1.25rem] h-5 px-1 bg-[var(--color-accent)] text-[var(--color-accent-foreground)] text-xs font-bold rounded-full flex items-center justify-center shadow-lg">
                    {{ $displayCount }}
                </span>
            @endif
        </span>
    @endif
</div>
