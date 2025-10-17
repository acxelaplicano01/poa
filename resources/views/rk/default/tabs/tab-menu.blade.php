@props([
    'orientation' => 'horizontal', // horizontal | vertical
])

<div class="mb-2 sm:mb-4 flex flex-col md:flex-row items-start md:justify-between gap-4">

    <!-- Tab Navigation -->
    <div
        class="
            flex 
            max-w-full 
            overflow-x-auto 
            rounded-2xl 
            bg-zinc-100 dark:bg-zinc-900 
            p-1 
            scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-700

            {{-- siempre horizontal en mobile --}}
            flex-row space-x-1

            {{-- en desktop se adapta según orientación --}}
            md:{{ $orientation === 'vertical' ? 'flex-col space-x-0 space-y-1 max-w-[200px]' : 'flex-row space-x-1' }}
        ">
        {{ $slot }}
    </div>

    @if(isset($extra))
        <div class="flex gap-2 shrink-0">
            {{ $extra }}
        </div>
    @endif
</div>
