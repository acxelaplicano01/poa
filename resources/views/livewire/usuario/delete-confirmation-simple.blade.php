<x-dialog-modal wire:model="showDeleteModal" maxWidth="md">
    <x-slot name="title">
        Confirmar Eliminación
    </x-slot>

    <x-slot name="content">
        <p>¿Estás seguro de que deseas eliminar este usuario?</p>
        @if($nombreAEliminar)
            <p><strong>Usuario:</strong> {{ $nombreAEliminar }}</p>
        @endif
        <p class="text-red-600 text-sm mt-2">Esta acción no se puede deshacer.</p>
    </x-slot>

    <x-slot name="footer">
        <x-secondary-button wire:click="closeDeleteModal">
            Cancelar
        </x-secondary-button>

        <x-danger-button class="ml-3" wire:click="delete">
            Eliminar Usuario
        </x-danger-button>
    </x-slot>
</x-dialog-modal>
