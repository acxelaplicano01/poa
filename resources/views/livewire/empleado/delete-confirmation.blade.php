<!-- Modal de confirmación de eliminación -->
    <x-confirmation-modal maxWidth="md" wire:model="confirmingDelete">
        <x-slot name="title">
            <div class="flex items-center">
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
    </x-confirmation-modal>