<x-confirmation-modal wire:model="showDeleteModal">
    <x-slot name="title">
        Eliminar Techo Departamental
    </x-slot>

    <x-slot name="content">
        ¿Está seguro que desea eliminar este techo departamental? Esta acción no se puede deshacer.
    </x-slot>

    <x-slot name="footer">
        <x-secondary-button wire:click="closeDeleteModal">
            {{ __('Cancelar') }}
        </x-secondary-button>

        <x-danger-button class="ml-3" wire:click="delete" wire:loading.attr="disabled">
            {{ __('Eliminar') }}
        </x-danger-button>
    </x-slot>
</x-confirmation-modal>
