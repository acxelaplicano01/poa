<!-- filepath: c:\Users\acxel\Desktop\Desarrollo\Git Repos\POA\resources\views\components\navbar-link.blade.php -->
@props(['active'])

@php
    $classes = $active ?? false
        ? 'flex items-center relative px-4 h-8 text-indigo-600 dark:text-white font-medium transition duration-100 ease-in-out rounded-lg hover:bg-indigo-100 dark:hover:bg-white/[7%] group'
        : 'flex items-center relative px-4 h-8 text-zinc-500 dark:text-white/80 hover:text-zinc-800 dark:hover:text-white transition duration-100 ease-in-out rounded-lg hover:bg-zinc-800/5 dark:hover:bg-white/[7%] group';
    
    // Generar un ID único para este elemento (para el CSS del indicador)
    $uniqueId = 'nav-link-' . md5(uniqid());
@endphp

<a id="{{ $uniqueId }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
    
    @if($active ?? false)
        <style>
            #{{ $uniqueId }}::after {
                content: "";
                position: absolute;
                bottom: -12px;
                left: 0;
                right: 0;
                height: 2px;
                background-color: #6366f1; /* zinc-500 por defecto */
                width: 100%;
                display: block;
                z-index: 10;
            }
            
            .dark #{{ $uniqueId }}::after {
                background-color: #ffffff;
            }
        </style>
    @endif
</a>