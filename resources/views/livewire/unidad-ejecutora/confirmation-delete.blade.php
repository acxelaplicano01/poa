 <!-- Modal de Confirmación de Eliminación -->
    <x-confirmation-modal wire:model="showDeleteModal">
        <x-slot name="title">
            {{ __('Eliminar Unidad Ejecutora') }}
        </x-slot>

        <x-slot name="content">
            @if($unidadEjecutoraToDelete)
                {{ __('¿Estás seguro de que deseas eliminar la Unidad Ejecutora') }} "<strong>{{ $unidadEjecutoraToDelete->name }}</strong>"?
                <br><br>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">
                    Esta acción no se puede deshacer. Se verificará que no tenga empleados, departamentos o techos presupuestarios asociados.
                </p>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeDeleteModal">
                {{ __('Cancelar') }}
            </x-secondary-button>

            <x-danger-button class="ml-3" wire:click="delete">
                {{ __('Eliminar') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>