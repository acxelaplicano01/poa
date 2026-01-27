<x-confirmation-modal wire:model="showDeleteModal">
    <x-slot name="title">
        {{ __('Eliminar Tipo de Proceso') }}
    </x-slot>

    <x-slot name="content">
        <p class="text-sm text-zinc-600 dark:text-zinc-400">
            ¿Estás seguro de que deseas eliminar este tipo de proceso de compra? 
            Esta acción no se puede deshacer.
        </p>
    </x-slot>

    <x-slot name="footer">
        <x-secondary-button wire:click="$set('showDeleteModal', false)" wire:loading.attr="disabled">
            {{ __('Cancelar') }}
        </x-secondary-button>

        <x-danger-button class="ml-3" wire:click="delete" wire:loading.attr="disabled">
            {{ __('Eliminar') }}
        </x-danger-button>
    </x-slot>
</x-confirmation-modal>
