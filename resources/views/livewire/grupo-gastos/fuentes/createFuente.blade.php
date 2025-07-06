<x-dialog-modal maxWidth="md" wire:model="isModalOpen">
    <x-slot name="title">
        {{ $fuenteId ? __('Editar Fuente') : __('Nueva Fuente') }}
    </x-slot>

    <x-slot name="content">
        <div class="space-y-4">
            <div>
                <x-label for="nombre" :value="__('Nombre')" />
                <x-input id="nombre" type="text" class="mt-1 block w-full" wire:model="nombre" placeholder="Ingrese el nombre de la fuente" />
                <x-input-error for="nombre" class="mt-2" />
            </div>

            <div>
                <x-label for="identificador" :value="__('Identificador')" />
                <x-input id="identificador" type="text" class="mt-1 block w-full" wire:model="identificador" placeholder="Ingrese el identificador de la fuente" />
                <x-input-error for="identificador" class="mt-2" />
                <div class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    {{ __('El identificador debe ser único y se utilizará como código de referencia.') }}
                </div>
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
                :loadingText="$fuenteId ? 'Actualizando...' : 'Creando...'">
                 {{ $fuenteId ? __('Actualizar') : __('Crear') }}
            </x-spinner-button>
        </div>
    </x-slot>
</x-dialog-modal>