<x-dialog-modal maxWidth="md" wire:model="isModalOpen">
    <x-slot name="title">
        {{ $institucionId ? __('Editar Institución') : __('Nueva Institución') }}
    </x-slot>

    <x-slot name="content">
        <div class="space-y-4">
            <div>
                <x-label for="nombre" :value="__('Nombre')" />
                <x-input id="nombre" type="text" class="mt-1 block w-full" wire:model="nombre" placeholder="Ingrese el nombre de la institución"/>
                <x-input-error for="nombre" class="mt-2" />
            </div>

            <div>
                <x-label for="descripcion" :value="__('Descripción')" />
                <x-textarea id="descripcion" name="descripcion" rows="4" wire:model="descripcion"
                    :error="$errors->has('descripcion')" placeholder="Escribe una descripción detallada..."></x-textarea>
                <x-input-error for="descripcion" class="mt-2" />
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
                :loadingText="$institucionId ? 'Actualizando...' : 'Creando...'">
                {{ $institucionId ? 'Actualizar' : 'Crear' }}
            </x-spinner-button>
        </div>
    </x-slot>
</x-dialog-modal>