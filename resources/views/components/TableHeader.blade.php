{{-- resources/views/components/table-header.blade.php --}}
<th {{ $attributes->merge(['class' => 'px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider ' . ($sortable ? 'cursor-pointer' : '')]) }}>
    <div class="flex items-center">
        {{ $slot }}
        @if($sortable && $sortField === $field)
            @if($sortDirection === 'asc')
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
            @else
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            @endif
        @endif
    </div>
</th>