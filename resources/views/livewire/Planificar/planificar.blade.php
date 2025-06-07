<div>
    <x-nav current_module="planificacion">
        <!-- Los enlaces se cargarán automáticamente según el módulo -->

        <x-slot name="actions">
            <!-- Aquí podemos agregar más botones o elementos a la derecha -->
            <div class="ml-3">
                <!-- Notificaciones -->
                <button
                    class="text-zinc-500 dark:text-white hover:bg-zinc-100 dark:hover:bg-zinc-700 focus:outline-none rounded-lg text-sm p-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                        </path>
                    </svg>
                </button>
            </div>
        </x-slot>
    </x-nav>
</div>