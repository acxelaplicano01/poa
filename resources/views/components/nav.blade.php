
<header wire:ignore
    class="fixed top-0 right-0 left-0 z-30 sm:left-64 min-h-14 flex items-center bg-white dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700"
    data-flux-header="">

    <!-- Navbar para móviles -->
    <nav class="flex items-center gap-1 py-3 lg:hidden w-full px-4">
        <div class="flex items-center">
            <button id="sidebarToggleBtn" data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
                type="button"
                class="inline-flex items-center p-2 text-sm text-zinc-500 rounded-lg sm:hidden hover:bg-zinc-100 focus:outline-none focus:ring-2 focus:ring-zinc-200 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-zinc-600">
                <span class="sr-only">Open sidebar</span>
                <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path clip-rule="evenodd" fill-rule="evenodd"
                        d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                    </path>
                </svg>
            </button>
        </div>
        <!-- Contenedor con scroll horizontal para enlaces en móvil -->
        <div class="flex-1 overflow-x-auto overflow-y-hidden whitespace-nowrap pb-2">
            @php
                $currentModule = 'dashboard'; // Valor por defecto

                // Detectar el módulo actual basado en la ruta actual
                $currentRoute = request()->route() ? request()->route()->getName() : '';
                foreach (config('rutas') as $moduleKey => $moduleData) {
                    if (isset($moduleData['items'])) {
                        foreach ($moduleData['items'] as $item) {
                            if (isset($item['routes']) && is_array($item['routes']) && in_array($currentRoute, $item['routes'])) {
                                $currentModule = $moduleKey;
                                break 2;
                            }
                        }
                    }
                }
            @endphp

            <div class="flex items-center gap-1 py-1 px-1">
                <x-rutas :module="$currentModule" />
            </div>
        </div>
    </nav>

    <!-- Contenedor principal para alineación de elementos -->
    <div class="flex items-center justify-end flex-1 px-4 lg:px-6">
        <!-- Navegación de escritorio con ítems del módulo actual -->
        <nav class="flex items-center gap-1 py-3 overflow-x-auto overflow-y-hidden">
            @php
                $currentModule = 'dashboard'; // Valor por defecto

                // Detectar el módulo actual basado en la ruta actual
                $currentRoute = request()->route() ? request()->route()->getName() : '';
                foreach (config('rutas') as $moduleKey => $moduleData) {
                    if (isset($moduleData['items'])) {
                        foreach ($moduleData['items'] as $item) {
                            if (isset($item['routes']) && is_array($item['routes']) && in_array($currentRoute, $item['routes'])) {
                                $currentModule = $moduleKey;
                                break 2;
                            }
                        }
                    }
                }
            @endphp

            <x-rutas :module="$currentModule" />
        </nav>
        <!-- Acciones del usuario alineadas a la derecha -->
        <div class="flex items-center ml-auto">
            <div class="flex items-center">
                <div>
                    <button id="theme-toggle" type="button"
                        class="text-zinc-500 dark:text-white hover:bg-zinc-100 dark:hover:bg-zinc-700 focus:outline-none rounded-lg text-sm p-2">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z">
                            </path>
                        </svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Espacio para acciones adicionales del usuario -->
            {{ $actions ?? '' }}
        </div>
    </div>
</header>

<!-- Script para manejar el cierre del menú -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // Fix para el problema del drawer en móvil
        function fixDrawerBackdrop() {
            // Identificar el backdrop creado por Flowbite
            const drawer = document.getElementById('logo-sidebar');
            const toggleBtn = document.getElementById('sidebarToggleBtn');
            
            // Crear un observador de mutaciones para detectar cambios en el DOM
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'aria-hidden') {
                        // Cuando el drawer se cierra
                        if (drawer.getAttribute('aria-hidden') === 'true') {
                            // Buscar y eliminar cualquier backdrop que haya quedado
                            const backdrops = document.querySelectorAll('[drawer-backdrop]');
                            backdrops.forEach(backdrop => {
                                backdrop.remove();
                            });
                            
                            // También eliminar clases de overflow en el body si se agregaron
                            document.body.classList.remove('overflow-hidden');
                        }
                    }
                });
            });
            
            // Observar cambios en atributos del drawer
            if (drawer) {
                observer.observe(drawer, { attributes: true });
            }
            
            // Limpiar cualquier backdrop cuando se hace clic en cualquier lugar del documento
            document.addEventListener('click', function(event) {
                // Solo si el sidebar está visible (en dispositivos móviles)
                if (window.innerWidth < 640) { // sm breakpoint es 640px
                    const backdrops = document.querySelectorAll('[drawer-backdrop]');
                    if (backdrops.length > 0 && !drawer.contains(event.target) && !toggleBtn.contains(event.target)) {
                        backdrops.forEach(backdrop => {
                            backdrop.remove();
                        });
                        document.body.classList.remove('overflow-hidden');
                    }
                }
            });
        }

        // Ejecutar la función de corrección
        fixDrawerBackdrop();
        
        // Cuando la ventana cambia de tamaño, volver a revisar
        window.addEventListener('resize', function() {
            // Si estamos en tamaño de escritorio y hay backdrops, eliminarlos
            if (window.innerWidth >= 640) { // sm breakpoint
                const backdrops = document.querySelectorAll('[drawer-backdrop]');
                backdrops.forEach(backdrop => {
                    backdrop.remove();
                });
                document.body.classList.remove('overflow-hidden');
            }
        });
    });
</script>