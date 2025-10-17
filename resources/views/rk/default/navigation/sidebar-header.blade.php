<header
    class="sticky top-0 z-20 bg-white
     dark:bg-zinc-900 p-2 flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700">
    <div class="mouse-pointer flex items-center space-x-2">
        <button @click="sidebarOpen = !sidebarOpen"
            class="p-2 rounded-2xl hover:bg-zinc-100 dark:hover:bg-zinc-800 md:block hidden">
            <svg class="text-zinc-500 dark:text-zinc-400" width="20" height="20" viewBox="0 0 20 20"
                fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M7.5 3.75V16.25M3.4375 16.25H16.5625C17.08 16.25 17.5 15.83 17.5 15.3125V4.6875C17.5 4.17 17.08 3.75 16.5625 3.75H3.4375C2.92 3.75 2.5 4.17 2.5 4.6875V15.3125C2.5 15.83 2.92 16.25 3.4375 16.25Z"
                    stroke="currentColor" stroke-width="1.25" stroke-linecap="round"
                    stroke-linejoin="round"></path>
            </svg>
        </button>

        <button @click="mobileMenuOpen = true"
            class="p-2 rounded-2xl hover:bg-zinc-100 dark:hover:bg-zinc-800 flex md:hidden">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>
    {{ $slot }}


    <div class="flex items-center space-x-4">
      {{ $rightMenu ?? '' }}
    </div>
    
</header>