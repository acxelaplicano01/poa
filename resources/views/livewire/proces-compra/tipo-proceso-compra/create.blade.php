<x-dialog-modal wire:model="isModalOpen" maxWidth="lg">
    <x-slot name="title">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">
                {{ $tipoId ? __('Editar Tipo de Proceso') : __('Nuevo Tipo de Proceso') }}
            </h3>
            <button wire:click="closeModal" type="button"
                class="text-zinc-400 bg-transparent hover:bg-zinc-200 hover:text-zinc-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-zinc-600 dark:hover:text-white">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    </x-slot>

    <x-slot name="content">
        <form wire:submit.prevent="store">
            <div class="space-y-6">
                {{-- Nombre --}}
                <div>
                    <x-label for="nombre" value="{{ __('Nombre *') }}" />
                    <x-input 
                        id="nombre" 
                        type="text" 
                        class="mt-1 block w-full" 
                        wire:model="nombre" 
                        placeholder="Ej: Licitación Pública"
                    />
                    <x-input-error for="nombre" class="mt-2" />
                </div>

                {{-- Descripción --}}
                <div>
                    <x-label for="descripcion" value="{{ __('Descripción') }}" />
                    <textarea 
                        id="descripcion" 
                        wire:model="descripcion"
                        rows="3"
                        class="border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full"
                        placeholder="Descripción del tipo de proceso"></textarea>
                    <x-input-error for="descripcion" class="mt-2" />
                </div>

                {{-- Montos --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-label for="monto_minimo" value="{{ __('Monto Mínimo (L) *') }}" />
                        <x-input 
                            id="monto_minimo" 
                            type="number" 
                            step="0.01"
                            min="0"
                            class="mt-1 block w-full" 
                            wire:model="monto_minimo" 
                            placeholder="0.00"
                        />
                        <x-input-error for="monto_minimo" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="monto_maximo" value="{{ __('Monto Máximo (L)') }}" />
                        <x-input 
                            id="monto_maximo" 
                            type="number" 
                            step="0.01"
                            min="0"
                            class="mt-1 block w-full" 
                            wire:model="monto_maximo" 
                            placeholder="Sin límite"
                        />
                        <x-input-error for="monto_maximo" class="mt-2" />
                        <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                            Dejar vacío para sin límite máximo
                        </p>
                    </div>
                </div>

                {{-- Estado --}}
                <div>
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            wire:model="activo"
                            class="rounded border-zinc-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:border-zinc-700 dark:bg-zinc-900 dark:focus:ring-indigo-600 dark:focus:ring-offset-zinc-800"
                        >
                        <span class="ml-2 text-sm text-zinc-600 dark:text-zinc-400">Activo</span>
                    </label>
                </div>
            </div>
        </form>
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
                :loadingText="$tipoId ? 'Actualizando...' : 'Creando...'">
                {{ $tipoId ? __('Actualizar') : __('Crear') }}
            </x-spinner-button>
        </div>
    </x-slot>
</x-dialog-modal>
