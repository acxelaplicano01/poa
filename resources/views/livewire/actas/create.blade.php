<!-- Modal para crear/editar tipo de acta de entrega -->
<x-dialog-modal maxWidth="md" wire:model="isOpen">
    <x-slot name="title">
        <h3 class="text-lg font-medium text-zinc-900 dark:text-white">
            {{ $isEditing ? 'Editar Tipo de Acta de Entrega' : 'Nuevo Tipo de Acta de Entrega' }}
        </h3>
    </x-slot>

    <x-slot name="content">
        <div class="space-y-4">
            <div>
                <x-label for="tipo" value="Tipo de Acta" />
                <x-input id="tipo" class="block mt-1 w-full" wire:model="tipo" placeholder="Ej: Acta de Recepción" />
                @error('tipo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>
    </x-slot>

    <x-slot name="footer">
        <div class="flex justify-end space-x-3">
            <x-spinner-secondary-button wire:click="closeModal" type="button" loadingTarget="closeModal"
                loadingText="Cerrando...">
                {{ __('Cancelar') }}
            </x-spinner-secondary-button>

            <x-spinner-button type="submit" wire:click="store" loadingTarget="store" :loadingText="$isEditing ? 'Actualizando...' : 'Creando...'">
                {{ $isEditing ? __('Actualizar') : __('Crear') }}
            </x-spinner-button>
        </div>
    </x-slot>
</x-dialog-modal>