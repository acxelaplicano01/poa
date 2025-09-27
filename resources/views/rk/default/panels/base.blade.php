<x-rk.default::layouts.app>
    <div x-data="{
        sidebarOpen: true,
        mobileMenuOpen: false,
        activeTab: 'home',
        notifications: 5,
        progress: 0
    }" x-init="setTimeout(() => progress = 100, 1000)" x-cloak class="relative min-h-screen bg-white dark:bg-zinc-900">
        {{ $sidebar ?? '' }}
        <!-- Main Content -->
        <div :class="sidebarOpen ? 'md:pl-64' : 'md:pl-0'"
            class="min-h-screen bg-white dark:bg-zinc-800 transition-[padding] duration-300 ease-in-out">

            {{ $header ?? '' }}

            <main>
                {{ $slot }}
            </main>
        </div>
    </div>
</x-rk.default::layouts.app>
