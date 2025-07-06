<x-dialog-modal maxWidth="md" wire:model="isModalOpen">
    <x-slot name="title">
        {{ $estadoId ? __('Editar Estado') : __('Nuevo Estado') }}
    </x-slot>

    <x-slot name="content">
        <div class="space-y-4">
            <div>
                <x-label for="estado" :value="__('Estado de Ejecución Presupuestaria')" />
                <x-input id="estado" type="text" class="mt-1 block w-full" wire:model="estado" placeholder="Ingrese el estado de ejecución presupuestaria" />
                <x-input-error for="estado" class="mt-2" />
            </div>
        </div>
    </x-slot>

    <x-slot name="footer">
        <div class="flex justify-end space-x-2">
            <x-spinner-secondary-button 
                wire:click="closeModal" 
                type="button"
                loadingTarget="closeModal"
                loadingText="Cerrando...">
                {{ __('Cancelar') }}
            </x-spinner-secondary-button>

            <x-spinner-button 
                type="submit" 
                wire:click="store"
                loadingTarget="store" 
                :loadingText="$estadoId ? 'Actualizando...' : 'Creando...'">
                {{ $estadoId ? __('Actualizar') : __('Crear') }}
            </x-spinner-button>
        </div>
    </x-slot>
</x-dialog-modal>