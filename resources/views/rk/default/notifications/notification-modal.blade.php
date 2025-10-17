@props([
    'position' => 'top-right',
])

@php
$positionClasses = [
    'center' => 'absolute inset-0 flex flex-col items-center gap-2 justify-center',
    'top-left' => 'absolute top-4 left-4 flex flex-col items-start gap-2',
    'top-right' => 'absolute top-4 right-4 flex flex-col items-end gap-2',
    'bottom-left' => 'absolute bottom-4 left-4 flex flex-col-reverse items-start gap-2',
    'bottom-right' => 'absolute bottom-4 right-4 flex flex-col-reverse items-end gap-2',
    'top-center' => 'absolute top-4 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2',
    'bottom-center' => 'absolute bottom-4 left-1/2 -translate-x-1/2 flex flex-col-reverse items-center gap-2'
];
@endphp

<template x-data x-init="
    (() => {
        const modal = document.getElementById('invisible-modal');
        if (modal && modal.__x) {
            modal.__x.$data.notifications.push({
                content: $refs.slot.innerHTML,
                positionClasses: '{{ $positionClasses[$position] }}'
            });
        }
    })()
">
    <div x-ref="slot" class="hidden">
        {{ $slot }}
    </div>
</template>
