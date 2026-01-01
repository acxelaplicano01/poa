<!-- Placeholder de carga -->
<div wire:loading wire:target="departamentoSeleccionado" class="mb-6">
    <!-- Simular el grid de tarjetas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Tarjeta skeleton 1 -->
        <div
            class="w-full bg-white dark:bg-zinc-800 border-2 border-zinc-200 dark:border-zinc-700 rounded-lg overflow-hidden animate-pulse">
            <!-- Header skeleton -->
            <div class="bg-gradient-to-r from-zinc-300 to-zinc-400 dark:from-zinc-600 dark:to-zinc-700 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="h-6 bg-white/20 rounded w-16 mb-1"></div>
                        <div class="h-4 bg-white/20 rounded w-12"></div>
                    </div>
                    <div class="w-8 h-8 bg-white/20 rounded"></div>
                </div>
            </div>

            <!-- Contenido skeleton -->
            <div class="p-4 space-y-3">
                <!-- Información general -->
                <div class="border-b border-zinc-200 dark:border-zinc-700 pb-3">
                    <div class="h-4 bg-zinc-300 dark:bg-zinc-600 rounded w-3/4 mb-1"></div>
                    <div class="h-3 bg-zinc-300 dark:bg-zinc-600 rounded w-1/2"></div>
                </div>

                <!-- Monto total -->
                <div class="bg-zinc-50 dark:bg-zinc-900 rounded-lg p-3">
                    <div class="flex justify-between items-center">
                        <div class="h-3 bg-zinc-300 dark:bg-zinc-600 rounded w-24"></div>
                        <div class="h-5 bg-zinc-300 dark:bg-zinc-600 rounded w-20"></div>
                    </div>
                </div>

                <!-- Fuentes -->
                <div class="space-y-2">
                    <div class="h-3 bg-zinc-300 dark:bg-zinc-600 rounded w-20"></div>
                    <div class="space-y-1">
                        <div class="h-6 bg-zinc-100 dark:bg-zinc-700 rounded"></div>
                        <div class="h-6 bg-zinc-100 dark:bg-zinc-700 rounded"></div>
                        <div class="h-6 bg-zinc-100 dark:bg-zinc-700 rounded w-3/4"></div>
                    </div>
                </div>

                <!-- Botón -->
                <div class="pt-2">
                    <div class="h-10 bg-zinc-300 dark:bg-zinc-600 rounded"></div>
                </div>
            </div>
        </div>

        <!-- Tarjeta skeleton 2 -->
        <div
            class="w-full bg-white dark:bg-zinc-800 border-2 border-zinc-200 dark:border-zinc-700 rounded-lg overflow-hidden animate-pulse">
            <!-- Header skeleton -->
            <div class="bg-gradient-to-r from-zinc-300 to-zinc-400 dark:from-zinc-600 dark:to-zinc-700 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="h-6 bg-white/20 rounded w-16 mb-1"></div>
                        <div class="h-4 bg-white/20 rounded w-12"></div>
                    </div>
                    <div class="w-8 h-8 bg-white/20 rounded"></div>
                </div>
            </div>

            <!-- Contenido skeleton -->
            <div class="p-4 space-y-3">
                <!-- Información general -->
                <div class="border-b border-zinc-200 dark:border-zinc-700 pb-3">
                    <div class="h-4 bg-zinc-300 dark:bg-zinc-600 rounded w-2/3 mb-1"></div>
                    <div class="h-3 bg-zinc-300 dark:bg-zinc-600 rounded w-1/2"></div>
                </div>

                <!-- Monto total -->
                <div class="bg-zinc-50 dark:bg-zinc-900 rounded-lg p-3">
                    <div class="flex justify-between items-center">
                        <div class="h-3 bg-zinc-300 dark:bg-zinc-600 rounded w-24"></div>
                        <div class="h-5 bg-zinc-300 dark:bg-zinc-600 rounded w-20"></div>
                    </div>
                </div>

                <!-- Fuentes -->
                <div class="space-y-2">
                    <div class="h-3 bg-zinc-300 dark:bg-zinc-600 rounded w-20"></div>
                    <div class="space-y-1">
                        <div class="h-6 bg-zinc-100 dark:bg-zinc-700 rounded"></div>
                        <div class="h-6 bg-zinc-100 dark:bg-zinc-700 rounded w-4/5"></div>
                    </div>
                </div>

                <!-- Botón -->
                <div class="pt-2">
                    <div class="h-10 bg-zinc-300 dark:bg-zinc-600 rounded"></div>
                </div>
            </div>
        </div>

        <!-- Tarjeta skeleton 3 -->
        <div
            class="w-full bg-white dark:bg-zinc-800 border-2 border-zinc-200 dark:border-zinc-700 rounded-lg overflow-hidden animate-pulse hidden lg:block">
            <!-- Header skeleton -->
            <div class="bg-gradient-to-r from-zinc-300 to-zinc-400 dark:from-zinc-600 dark:to-zinc-700 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="h-6 bg-white/20 rounded w-16 mb-1"></div>
                        <div class="h-4 bg-white/20 rounded w-12"></div>
                    </div>
                    <div class="w-8 h-8 bg-white/20 rounded"></div>
                </div>
            </div>

            <!-- Contenido skeleton -->
            <div class="p-4 space-y-3">
                <!-- Información general -->
                <div class="border-b border-zinc-200 dark:border-zinc-700 pb-3">
                    <div class="h-4 bg-zinc-300 dark:bg-zinc-600 rounded w-3/4 mb-1"></div>
                    <div class="h-3 bg-zinc-300 dark:bg-zinc-600 rounded w-1/2"></div>
                </div>

                <!-- Monto total -->
                <div class="bg-zinc-50 dark:bg-zinc-900 rounded-lg p-3">
                    <div class="flex justify-between items-center">
                        <div class="h-3 bg-zinc-300 dark:bg-zinc-600 rounded w-24"></div>
                        <div class="h-5 bg-zinc-300 dark:bg-zinc-600 rounded w-20"></div>
                    </div>
                </div>

                <!-- Fuentes -->
                <div class="space-y-2">
                    <div class="h-3 bg-zinc-300 dark:bg-zinc-600 rounded w-20"></div>
                    <div class="space-y-1">
                        <div class="h-6 bg-zinc-100 dark:bg-zinc-700 rounded"></div>
                        <div class="h-6 bg-zinc-100 dark:bg-zinc-700 rounded"></div>
                        <div class="h-6 bg-zinc-100 dark:bg-zinc-700 rounded w-2/3"></div>
                    </div>
                </div>

                <!-- Botón -->
                <div class="pt-2">
                    <div class="h-10 bg-zinc-300 dark:bg-zinc-600 rounded"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensaje de carga 
    <div class="mt-6 text-center">
        <div class="inline-flex items-center px-4 py-2">
            <div
                class="animate-spin inline-block w-5 h-5 border-2 border-current border-t-transparent text-indigo-500 rounded-full mr-2">
            </div>
            <span class="text-sm text-zinc-600 dark:text-zinc-400">Cargando información del departamento...</span>
        </div>
    </div>-->
</div>