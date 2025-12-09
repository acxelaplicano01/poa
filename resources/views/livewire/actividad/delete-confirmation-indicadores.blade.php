<!-- Modal de Confirmación de Eliminación de Indicador -->
<x-dialog-modal wire:model.live="showDeleteIndicadorModal" maxWidth="md">
    <x-slot name="title">
        <div class="flex items-center">
            <div
                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">
                    Eliminar Indicador
                </h3>
            </div>
        </div>
    </x-slot>

    <x-slot name="content">
        <div class="mt-2">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                ¿Estás seguro de que deseas eliminar este indicador?
            </p>

            @if($indicadorToDelete)
                <div class="mt-4 p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $indicadorToDelete->nombre }}
                            </h4>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                Meta: {{ $indicadorToDelete->cantidadPlanificada }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div
                class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700 dark:text-yellow-200">
                            Esta acción no se puede deshacer. Se eliminarán permanentemente el indicador y sus datos
                            asociados.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-secondary-button wire:click="closeDeleteIndicadorModal">
            Cancelar
        </x-secondary-button>

        <x-danger-button class="ml-3" wire:click="confirmDeleteIndicador">
            Eliminar
        </x-danger-button>
    </x-slot>
</x-dialog-modal>