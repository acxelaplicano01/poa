@props([
    'tabs' => [],
    'activeTab' => 'home'
])

<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4" 
     x-data="{ activeTab: '{{ $activeTab }}' }">
    
    <!-- Tab Navigation -->
    <div class="grid w-full max-w-[600px] grid-cols-5 rounded-2xl bg-zinc-100 dark:bg-zinc-800 p-1">
        @foreach($tabs as $key => $tab)
            <button @click="activeTab = '{{ $key }}'"
                    :class="activeTab === '{{ $key }}' ? 'bg-white dark:bg-zinc-700 text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                    class="rounded-xl px-3 py-2 text-sm font-medium transition-all duration-200">
                {{ $tab }}
            </button>
        @endforeach
    </div>
    
    <!-- Action Buttons -->
    <div class="hidden md:flex gap-2">
        <button class="flex items-center gap-2 rounded-2xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-zinc-800 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-zinc-50 dark:hover:bg-zinc-700">
            <!-- Cambiado download a Heroicons -->
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
            Instalar App
        </button>
        <button class="flex items-center gap-2 rounded-2xl bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700">
            <!-- Cambiado plus a Heroicons -->
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Nuevo Proyecto
        </button>
    </div>
</div>
