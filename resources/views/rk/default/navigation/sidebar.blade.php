<div>
    <!-- Mobile menu overlay -->
    <div x-show="mobileMenuOpen" x-transition.opacity @click="mobileMenuOpen = false"
        class="fixed inset-0 z-40 bg-black/50 md:hidden" x-cloak></div>

    <!-- Sidebar - Mobile -->
    <div :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 transform bg-white
        dark:bg-zinc-900 transition-transform duration-300 ease-in-out md:hidden border-r border-zinc-200 dark:border-zinc-700">
        <div class="flex h-full flex-col">
            <div class="flex items-center justify-between p-4">
                {{ $header ?? '' }}
                <button @click="mobileMenuOpen = false" class="p-2 rounded-2xl hover:bg-zinc-100 dark:hover:bg-zinc-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="flex-1 px-3 py-2 overflow-y-auto">
                {{ $slot }}
            </div>
            {{ $footer ?? '' }}
        </div>
    </div>

    <!-- Sidebar - Desktop -->
    <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-64'"
        class="fixed inset-y-0 left-0 z-30 hidden w-64 transform border-r border-zinc-200 dark:border-zinc-700 bg-white
                dark:bg-zinc-900 transition-transform duration-300 ease-in-out md:block">
        <div class="flex h-full flex-col">
            <div class="p-4 flex justify-between items-center">
                {{ $header ?? '' }}

            </div>
            <div class="flex-1 px-3 py-2 overflow-y-auto">
                {{ $slot }}
            </div>
            {{ $footer ?? '' }}
        </div>
    </div>
</div>
