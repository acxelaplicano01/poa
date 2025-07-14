
<x-dialog-modal maxWidth="md" wire:model="isModalOpen">
    <x-slot name="title">
        {{ $unidadId ? __('Editar Unidad Ejecutora') : __('Nueva Unidad Ejecutora') }}
    </x-slot>

    <x-slot name="content">
        <div class="space-y-4">
            <!-- Nombre -->
            <div>
                <x-label for="name" :value="__('Nombre')" />
                <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" placeholder="Ingrese el nombre de la unidad ejecutora"/>
                <x-input-error for="name" class="mt-2" />
            </div>
            <!-- Descripción -->
            <div>
                <x-label for="descripcion" :value="__('Descripción')" />
                <x-textarea id="descripcion" name="descripcion" rows="3" wire:model="descripcion"
                    :error="$errors->has('descripcion')" placeholder="Escribe una descripción..."></x-textarea>
                <x-input-error for="descripcion" class="mt-2" />
            </div>
            <!-- Estructura -->
            <div>
                <x-label for="estructura" :value="__('Estructura')" />
                <x-input id="estructura" type="text" class="mt-1 block w-full" wire:model="estructura" placeholder="Estructura"/>
                <x-input-error for="estructura" class="mt-2" />
            </div>
            <!-- Institución -->
            <div>
                <x-label for="idInstitucion" :value="__('Institución')" />
                <select id="idInstitucion" wire:model="idInstitucion" class="mt-1 block w-full border-gray-300 rounded-md">
                    <option value="">{{ __('Seleccione una institución') }}</option>
                    @foreach($instituciones as $institucion)
                        <option value="{{ $institucion->id }}">{{ $institucion->nombre }}</option>
                    @endforeach
                </select>
                <x-input-error for="idInstitucion" class="mt-2" />
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
                :loadingText="$unidadId ? 'Actualizando...' : 'Creando...'">
                {{ $unidadId ? 'Actualizar' : 'Crear' }}
            </x-spinner-button>
        </div>
    </x-slot>
</x-dialog->