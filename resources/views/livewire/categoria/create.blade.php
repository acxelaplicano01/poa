<x-dialog-modal maxWidth="md" wire:model="isModalOpen">
    <x-slot name="title">
        {{ $categoriaId ? __('Editar Categoría') : __('Nueva Categoría') }}
    </x-slot>

    <x-slot name="content">
        <div class="space-y-4">
            <div>
                <x-label for="categoria" :value="__('Nombre de la categoría')" />
                <x-input id="categoria" type="text" class="mt-1 block w-full" wire:model="categoria" placeholder="Ingrese el nombre de la categoría" />
                <x-input-error for="categoria" class="mt-2" />
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
                :loadingText="$categoriaId ? 'Actualizando...' : 'Creando...'">
                {{ $categoriaId ? __('Actualizar') : __('Guardar') }}
            </x-spinner-button>
        </div>
    </x-slot>
</x-dialog-modal>