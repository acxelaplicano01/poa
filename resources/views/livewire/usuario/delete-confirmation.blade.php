<x-confirmation-modal wire:model="confirmingDelete" maxWidth="md">
    <x-slot name="title">
        <div class="flex items-center">
            <h3 class="text-lg font-medium text-zinc-900 dark:text-white">Confirmación de Eliminación</h3>
        </div>
    </x-slot>

    <x-slot name="content">
        <p class="text-sm text-zinc-600 dark:text-zinc-400">
            ¿Estás seguro de que deseas eliminar el usuario: <span class="font-semibold">{{ $nombreAEliminar }}</span>?
            Esta acción no se puede deshacer.
        </p>
    </x-slot>

    <x-slot name="footer">
        <div class="flex justify-end gap-x-4">
            <x-secondary-button wire:click="$set('confirmingDelete', false)" wire:loading.attr="disabled">
                Cancelar
            </x-secondary-button>

            <x-danger-button wire:click="delete" wire:loading.attr="disabled">
                Eliminar
            </x-danger-button>
        </div>
    </x-slot>
</x-confirmation-modal>