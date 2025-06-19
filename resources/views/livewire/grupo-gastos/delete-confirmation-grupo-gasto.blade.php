<x-confirmation-modal maxWidth="md" wire:model="isDeleteModalOpen">
    <x-slot name="title">
        {{ __('Eliminar Grupo de Gastos') }}
    </x-slot>

    <x-slot name="content">
        <div class="py-4">
            <div class="text-zinc-700 dark:text-zinc-300">
                {{ __('¿Estás seguro de que deseas eliminar este grupo de gastos? Esta acción no se puede deshacer.') }}
            </div>
        </div>
    </x-slot>

    <x-slot name="footer">
        <div class="flex justify-end space-x-2">
            <x-secondary-button wire:click="closeDeleteModal" wire:loading.attr="disabled">
                {{ __('Cancelar') }}
            </x-secondary-button>

            <x-danger-button wire:click="delete" wire:loading.attr="disabled">
                {{ __('Eliminar') }}
            </x-danger-button>
        </div>
    </x-slot>
</x-confirmation-modal>