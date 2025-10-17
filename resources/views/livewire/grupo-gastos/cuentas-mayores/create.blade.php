<!-- Modal para crear/editar cuenta mayor -->
<x-dialog-modal wire:model="isModalOpen">
    <x-slot name="title">
        {{ $cuentaMayorId ? __('Editar Cuenta Mayor') : __('Nueva Cuenta Mayor') }}
    </x-slot>

    <x-slot name="content">
        <div class="space-y-6">
            <!-- Nombre -->
            <div>
                <x-label for="nombre" value="{{ __('Nombre') }}" />
                <x-input 
                    id="nombre" 
                    type="text" 
                    class="mt-1 block w-full" 
                    wire:model="nombre" 
                    placeholder="Ingrese el nombre de la cuenta mayor"
                />
                <x-input-error for="nombre" class="mt-2" />
            </div>

            <!-- Identificador -->
            <div>
                <x-label for="identificador" value="{{ __('Identificador') }}" />
                <x-input 
                    id="identificador" 
                    type="text" 
                    class="mt-1 block w-full" 
                    wire:model="identificador" 
                    placeholder="Ej: 001, A1, etc."
                />
                <x-input-error for="identificador" class="mt-2" />
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                    Identificador único para la cuenta mayor.
                </p>
            </div>

            <!-- Grupo de Gastos -->
            <div>
                <x-label for="idGrupo" value="{{ __('Grupo de Gastos') }}" />
                <select 
                    id="idGrupo" 
                    wire:model="idGrupo"
                    class="border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full"
                >
                    <option value="">Seleccione un grupo de gastos</option>
                    @foreach($gruposGastos as $grupo)
                        <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                    @endforeach
                </select>
                <x-input-error for="idGrupo" class="mt-2" />
            </div>

            <!-- Descripción -->
            <div>
                <x-label for="descripcion" value="{{ __('Descripción') }}" />
                <textarea 
                    id="descripcion" 
                    wire:model="descripcion"
                    class="border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full"
                    rows="3"
                    placeholder="Descripción de la cuenta mayor (opcional)"
                ></textarea>
                <x-input-error for="descripcion" class="mt-2" />
            </div>
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-spinner-secondary-button 
                wire:click="closeModal" 
                type="button"
                loadingTarget="closeModal"
                loadingText="Cerrando...">
                {{ __('Cancelar') }}
            </x-spinner-secondary-button>

            <x-spinner-button class="ml-3"
                type="submit" 
                wire:click="store"
                loadingTarget="store" 
                :loadingText="$isEditing ? 'Actualizando...' : 'Creando...'">
                {{ $cuentaMayorId ? __('Actualizar') : __('Crear') }}
            </x-spinner-button>
    </x-slot>
</x-dialog-modal>
