<!-- Modal de confirmación de eliminación -->
    <x-dialog-modal wire:model="confirmingDelete">
        <x-slot name="title">
            <div class="flex items-center">
                <svg class="h-6 w-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Confirmar eliminación
                </h3>
            </div>
        </x-slot>

        <x-slot name="content">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                ¿Estás seguro de que deseas eliminar este empleado? Esta acción no se puede deshacer.
            </p>
        </x-slot>

        <x-slot name="footer">
            <div class="flex justify-end gap-x-4">
                <x-secondary-button wire:click="$set('confirmingDelete', false)">
                    Cancelar
                </x-secondary-button>

                <x-danger-button wire:click="delete">
                    Eliminar
                </x-danger-button>
            </div>
        </x-slot>
    </x-dialog-modal>