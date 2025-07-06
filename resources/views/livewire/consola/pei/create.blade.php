    <x-dialog-modal wire:model="showModal" maxWidth="lg">
        <x-slot name="title">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">
                    {{ $isEditing ? __('Editar PEI') : __('Crear Nuevo PEI') }}
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
            <form wire:submit="save" id="pei-form">
                <div class="mb-4">
                    <x-label for="name" value="{{ __('Nombre del PEI') }}" />
                    <x-input wire:model="name" id="name" class="block mt-1 w-full" type="text" placeholder="Ingrese el nombre del PEI" />
                    <x-input-error for="name" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-label for="idInstitucion" value="{{ __('Institución') }}" />
                    <x-select 
                        id="idInstitucion" 
                        wire:model="idInstitucion"
                        :options="array_merge(
        [['value' => '', 'text' => 'Seleccione una institución']],
        $instituciones->map(fn($inst) => ['value' => $inst->id, 'text' => $inst->nombre])->toArray()
    )"
                        class="block mt-1 w-full"
                    />
                    <x-input-error for="idInstitucion" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-label for="initialYear" value="{{ __('Año Inicial') }}" />
                        <x-year-picker 
                            wire:model="initialYear"
                            id="initialYear"
                            name="initialYear"
                            placeholder="Seleccionar año inicial"
                            min-year="2000"
                            max-year="2050"
                            class="block mt-1 w-full"
                        />
                        <x-input-error for="initialYear" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="finalYear" value="{{ __('Año Final') }}" />
                        <x-year-picker 
                            wire:model="finalYear"
                            id="finalYear"
                            name="finalYear"
                            placeholder="Seleccionar año final"
                            min-year="2000"
                            max-year="2050"
                            class="block mt-1 w-full"
                        />
                        <x-input-error for="finalYear" class="mt-2" />
                    </div>
                </div>
            </form>
        </x-slot>

        <x-slot name="footer">
            <x-spinner-secondary-button wire:click="closeModal" type="button" loadingTarget="closeModal" loadingText="Cerrando...">
                {{ __('Cancelar') }}
            </x-spinner-secondary-button>
            
            <x-spinner-button class="ml-3" type="submit" wire:click="save" loadingTarget="save" :loadingText="$isEditing ? 'Actualizando...' : 'Creando...'">
                {{ $isEditing ? __('Actualizar PEI') : __('Crear PEI') }}
            </x-spinner-button>
        </x-slot>
    </x-dialog-modal>