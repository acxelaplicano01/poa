<x-dialog-modal wire:model="showModal" maxWidth="lg">
    <x-slot name="title">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">
                {{ $isEditing ? __('Editar Recurso') : __('Crear Recurso') }}
            </h3>
            <button wire:click="closeModal" type="button"
                class="text-zinc-400 bg-transparent hover:bg-zinc-200 hover:text-zinc-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-zinc-600 dark:hover:text-white">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg>
                <span class="sr-only">Cerrar modal</span>
            </button>
        </div>
    </x-slot>

    <x-slot name="content">
        <form wire:submit.prevent="store" id="recurso-form">
            <div class="mb-4">
                <x-label for="nombre" value="{{ __('Nombre del Recurso') }}" />
                <x-input wire:model="nombre" id="nombre" class="block mt-1 w-full" type="text" placeholder="Ingrese el nombre del recurso" />
                <x-input-error for="nombre" class="mt-2" />
            </div>
            <div class="mb-4">
                <x-label for="idobjeto" value="{{ __('Objeto de Gasto') }}" />
                <x-select wire:model="idobjeto" id="idobjeto" :options="$objetosGasto" class="block mt-1 w-full" />
                <x-input-error for="idobjeto" class="mt-2" />
            </div>
            <div class="mb-4">
                <x-label for="idunidad" value="{{ __('Unidad de Medida') }}" />
                <x-select wire:model="idunidad" id="idunidad" :options="$unidadesMedida" class="block mt-1 w-full" />
                <x-input-error for="idunidad" class="mt-2" />
            </div>
            <div class="mb-4">
                <x-label for="idProcesoCompra" value="{{ __('Proceso de Compra') }}" />
                <x-select wire:model="idProcesoCompra" id="idProcesoCompra" :options="$procesosCompra" class="block mt-1 w-full" />
                <x-input-error for="idProcesoCompra" class="mt-2" />
            </div>
            <div class="mb-4">
                <x-label for="idCubs" value="{{ __('CUBS') }}" />
                <x-select wire:model="idCubs" id="idCubs" :options="$cubs" class="block mt-1 w-full" />
                <x-input-error for="idCubs" class="mt-2" />
            </div>
        </form>
    </x-slot>

    <x-slot name="footer">
        <x-spinner-secondary-button wire:click="closeModal" type="button" loadingTarget="closeModal" loadingText="Cerrando...">
            {{ __('Cancelar') }}
        </x-spinner-secondary-button>
        
        <x-spinner-button class="ml-3" type="submit" wire:click="store" loadingTarget="store" :loadingText="$isEditing ? 'Actualizando...' : 'Creando...'">
            {{ $isEditing ? __('Actualizar Recurso') : __('Crear Recurso') }}
        </x-spinner-button>
    </x-slot>
</x-dialog-modal>
