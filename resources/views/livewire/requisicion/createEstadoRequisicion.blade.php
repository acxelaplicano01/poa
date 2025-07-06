<x-dialog-modal maxWidth="md" wire:model="isModalOpen">
    <x-slot name="title">
        {{ $estadoId ? __('Editar Estado') : __('Nuevo Estado') }}
    </x-slot>

    <x-slot name="content">
        <div class="space-y-4">
            <div>
                <x-label for="estado" :value="__('Estado de Requisición')" />
                <x-input id="estado" type="text" class="mt-1 block w-full" wire:model="estado" placeholder="Ingrese el estado de requisición"/>
                <x-input-error for="estado" class="mt-2" />
            </div>
        </div>
    </x-slot>

    <x-slot name="footer">
        <div class="flex justify-end space-x-2">
            <x-secondary-button wire:click="closeModal" wire:loading.attr="disabled">
                {{ __('Cancelar') }}
            </x-secondary-button>

            <x-button wire:click="store" wire:loading.attr="disabled" class="ml-2">
                {{ $estadoId ? __('Actualizar') : __('Guardar') }}
            </x-button>

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
                {{ $estadoId ? 'Actualizar Unidad' : 'Crear Unidad' }}
            </x-spinner-button>
        </div>
    </x-slot>
</x-dialog-modal>