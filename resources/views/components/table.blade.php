{{-- resources/views/components/table.blade.php --}}
<div {{ $attributes }}>
    <!-- Vista de tabla para pantallas medianas y grandes -->
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-700">
                <tr>
                    @foreach ($columns as $column)
                        <th scope="col" 
                            class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider {{ isset($column['sortable']) && $column['sortable'] ? 'cursor-pointer' : '' }}"
                            @if(isset($column['sortable']) && $column['sortable'])
                                wire:click="{{ $column['sortBy'] ?? 'sortBy(\''.$column['key'].'\')' }}"
                            @endif
                        >
                            <div class="flex items-center">
                                {{ $column['label'] }}
                                @if(isset($column['sortable']) && $column['sortable'] && $sortField === $column['key'])
                                    @if($sortDirection === 'asc')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" 
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </div>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 dark:bg-zinc-800 dark:divide-zinc-700">
                {{ $desktop ?? '' }}
            </tbody>
        </table>
    </div>

    <!-- Vista de tarjetas para pantallas pequeñas (móviles) -->
    @if($showMobile)
        <div class="md:hidden space-y-4">
            {{ $mobile ?? '' }}
        </div>
    @endif

    <!-- Paginación o mensaje de "no hay datos" controlados por el slot footer -->
    <div class="mt-6">
        {{ $footer ?? '' }}
    </div>
</div>