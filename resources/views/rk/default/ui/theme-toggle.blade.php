<x-rk.default::dropdowns.menu-dropdown triggerClass="bg-zinc-100 dark:bg-zinc-800   dark:border-gray-600 rounded-2xl">
    <!-- Trigger -->
    <x-slot name="trigger">
        <span class="font-medium text-gray-700 dark:text-gray-300 px-3 py-2 rounded hover:bg-zinc-100 dark:hover:bg-zinc-700">
            Tema
        </span>
    </x-slot>

    <!-- Contenido del dropdown -->
    <div x-data="{
        darkMode: localStorage.getItem('darkMode') === 'true' ||
            (!localStorage.getItem('darkMode') &&
                window.matchMedia('(prefers-color-scheme: dark)').matches),
    
        setTheme(mode) {
            this.darkMode = mode;
            localStorage.setItem('darkMode', this.darkMode);
            this.updateTheme();
        },
    
        updateTheme() {
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    }" x-init="updateTheme()" class="flex flex-col gap-1">

        <!-- Claro -->
        <button @click="setTheme(false)"
            class="w-full flex items-center gap-2 px-3 py-2 text-sm rounded-md 
                   hover:bg-zinc-100 dark:hover:bg-zinc-700"
            :class="!darkMode ? 'bg-zinc-100 dark:bg-zinc-700' : ''">
            Claro
        </button>

        <!-- Oscuro -->
        <button @click="setTheme(true)"
            class="w-full flex items-center gap-2 px-3 py-2 text-sm rounded-md 
                   hover:bg-zinc-100 dark:hover:bg-zinc-700"
            :class="darkMode ? 'bg-zinc-100 dark:bg-zinc-700' : ''">
            Oscuro
        </button>
        
    </div>
</x-rk.default::dropdowns.menu-dropdown>
