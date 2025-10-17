@props([
    'user' => null,
    'bgColor' => 'bg-[var(--color-accent)] text-[var(--color-accent-foreground)] ',
    'size' => 'md',
    'text' => 'U',
])

@php
    $sizes = [
        'sm' => '6', // 32px
        'md' => '10', // 40px
        'lg' => '12', // 48px
        'xl' => '16', // 64px
    ];
    $size = $sizes[$size] ?? '10';
@endphp


<div
    class="w-{{ $size }} h-{{ $size }} {{ $bgColor }}
    rounded-2xl
    flex items-center justify-center font-semibold text-sm">
    {{ $user ? strtoupper(substr($user['name'] ?? 'U', 0, 1)) : $text }}
</div>
