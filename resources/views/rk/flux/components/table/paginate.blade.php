@props(['paginator'])

@php
    $current = $paginator->currentPage();
    $last = $paginator->lastPage();

    $pages = [];

    if ($last <= 5) {
        // Mostrar todas
        for ($i = 1; $i <= $last; $i++) {
            $pages[] = $i;
        }
    } else {
        $pages[] = 1;

        if ($current <= 3) {
            $pages[] = 2;
            $pages[] = 3;
            $pages[] = null; // ...
        } elseif ($current > 3 && $current < $last - 2) {
            $pages[] = null;
            $pages[] = $current - 1;
            $pages[] = $current;
            $pages[] = $current + 1;
            $pages[] = null;
        } else {
            $pages[] = null;
            $pages[] = $last - 3;
            $pages[] = $last - 2;
        }

        $pages[] = $last;
    }
@endphp

<div class="flex justify-between items-center mt-4 flex-wrap gap-2">
    {{-- Botón anterior --}}
    @if ($paginator->onFirstPage())
        <span
            class="px-4 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 rounded-md dark:bg-zinc-800 dark:text-gray-500">
            Anterior
        </span>
    @else
        <button
            wire:click="previousPage"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-zinc-800 dark:text-gray-200 dark:border-zinc-700 dark:hover:bg-zinc-700">
            Anterior
        </button>
    @endif

    {{-- Números de página --}}
    <div class="space-x-2 hidden sm:flex">
        @foreach ($pages as $page)
            @if (is_null($page))
                <span class="px-3 py-1 text-sm font-medium text-gray-500 dark:text-gray-400">...</span>
            @elseif ($page == $paginator->currentPage())
                <span class="px-3 py-1 text-sm font-medium text-white bg-blue-600 rounded-md">{{ $page }}</span>
            @else
                <button
                    wire:click="gotoPage({{ $page }})"
                    class="px-3 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-zinc-800 dark:text-gray-200 dark:border-zinc-700 dark:hover:bg-zinc-700">
                    {{ $page }}
                </button>
            @endif
        @endforeach
    </div>

    {{-- Botón siguiente --}}
    @if ($paginator->hasMorePages())
        <button
            wire:click="nextPage"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-zinc-800 dark:text-gray-200 dark:border-zinc-700 dark:hover:bg-zinc-700">
            Siguiente
        </button>
    @else
        <span
            class="px-4 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 rounded-md dark:bg-zinc-800 dark:text-gray-500">
            Siguiente
        </span>
    @endif
</div>
