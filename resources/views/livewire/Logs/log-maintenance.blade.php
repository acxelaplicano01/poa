<div>
    <div class="mt-6 p-4 bg-white rounded-lg shadow sm:p-6 dark:bg-zinc-900">
        <h3 class="text-lg font-medium text-zinc-900 dark:text-white">Mantenimiento de logs</h3>
        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
            Elimina logs antiguos para mantener la base de datos optimizada.
        </p>
        <div class="mt-5">
            <div class="flex items-end gap-4">
                <div>
                    <label for="days" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                        Eliminar logs más antiguos que
                    </label>
                    <div class="mt-1">
                        <x-input 
                            type="number" 
                            min="1" 
                            step="1" 
                            wire:model="days" 
                            id="days" 
                            class="block w-24 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-zinc-700 dark:border-zinc-600"
                        />
                    </div>
                    @error('days') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="pb-px">
                    <span class="text-zinc-700 dark:text-zinc-300">días</span>
                </div>
                <div>
                    <x-spinner-danger-button type="button" wire:click="confirmCleanup" loadingTarget="confirmCleanup" loadingText="Abriendo...">
                        Limpiar logs
                    </x-spinner-danger-button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación -->
    <x-elegant-delete-modal 
        wire:model="showDeleteModal"
        title="Confirmar Eliminación"
        message="¿Estás seguro de que deseas eliminar los logs antiguos?"
        entity-name="Logs más antiguos que {{ $days }} días"
        entity-details="Esta acción eliminará todos los registros de actividad anteriores a la fecha especificada"
        confirm-method="confirmDelete"
        cancel-method="closeDeleteModal"
        confirm-text="Limpiar Logs"
        cancel-text="Cancelar"
    />
</div>
