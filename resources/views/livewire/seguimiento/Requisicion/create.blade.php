<x-modal 
    wire:model="showModal" 
    maxWidth="lg"
    x-on:close="$wire.closeModal()"
>
    <div class="px-6 py-4 bg-white dark:bg-zinc-900">
        <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">
            {{ $isEditing ? __('Editar Requisición') : __('Crear Nueva Requisición') }}
        </h3>
        <form wire:submit.prevent="store">
            <div class="mb-4">
                <label for="correlativo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correlativo</label>
                <input type="text" id="correlativo" wire:model="correlativo" class="mt-1 block w-full border-gray-300 dark:border-zinc-700 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100">
                @error('correlativo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción</label>
                <textarea id="descripcion" wire:model="descripcion" class="mt-1 block w-full border-gray-300 dark:border-zinc-700 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100"></textarea>
                @error('descripcion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label for="observacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Observación</label>
                <textarea id="observacion" wire:model="observacion" class="mt-1 block w-full border-gray-300 dark:border-zinc-700 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100"></textarea>
                @error('observacion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label for="idPoa" class="block text-sm font-medium text-gray-700 dark:text-gray-300">POA</label>
                <select id="idPoa" wire:model="idPoa" class="mt-1 block w-full border-gray-300 dark:border-zinc-700 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100">
                    <option value="">Selecciona un POA</option>
                    @foreach($poas as $poa)
                        <option value="{{ $poa->id }}">{{ $poa->name }} ({{ $poa->anio }})</option>
                    @endforeach
                </select>
                @error('idPoa') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label for="fechaSolicitud" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha Solicitud</label>
                <input type="date" id="fechaSolicitud" wire:model="fechaSolicitud" class="mt-1 block w-full border-gray-300 dark:border-zinc-700 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100">
                @error('fechaSolicitud') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label for="fechaRequerido" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha Requerido</label>
                <input type="date" id="fechaRequerido" wire:model="fechaRequerido" class="mt-1 block w-full border-gray-300 dark:border-zinc-700 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100">
                @error('fechaRequerido') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="flex justify-end mt-6 space-x-3">
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
                    :loadingText="$isEditing ? __('Actualizando...') : __('Creando...')">
                    {{ $isEditing ? __('Actualizar Requisición') : __('Crear Requisición') }}
                </x-spinner-button>
            </div>
        </form>
    </div>
</x-modal>
