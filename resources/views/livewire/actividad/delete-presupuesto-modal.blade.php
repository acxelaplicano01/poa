<!-- Modal Elegante para Eliminar Presupuesto -->
<x-dialog-modal wire:model="showDeletePresupuestoModal" max-width="md">
    <x-slot name="title">
        Eliminar Recurso Presupuestario
    </x-slot>

    <x-slot name="content">
        <div class="space-y-4">
            <!-- Icono de advertencia -->
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 dark:bg-red-900/20 rounded-full">
                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 0v2m0-6v-2m0 0V7m0 0a4 4 0 110 8 4 4 0 010-8z" />
                </svg>
            </div>
            
            <!-- Contenido del modal -->
            <div class="text-center">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">
                    ¿Eliminar este recurso?
                </h3>
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                    @if($presupuestoToDelete)
                        Se eliminará <span class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $presupuestoToDelete['recurso'] ?? 'el recurso' }}</span> 
                        con un monto de <span class="font-semibold text-red-600 dark:text-red-400">L {{ number_format($presupuestoToDelete['total'] ?? 0, 2) }}</span>.
                    @else
                        Esta acción no se puede deshacer.
                    @endif
                </p>
                
                <!-- Información adicional -->
                <div class="mt-4 p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                    <p class="text-xs text-amber-800 dark:text-amber-300">
                        <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        El presupuesto será eliminado y el techo del departamento se actualizará automáticamente.
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-secondary-button wire:click="$set('showDeletePresupuestoModal', false)">
            Cancelar
        </x-secondary-button>
        <x-button wire:click="confirmDeletePresupuesto" class="ml-2 bg-red-600 hover:bg-red-700 focus:ring-red-500">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            Eliminar Presupuesto
        </x-button>
    </x-slot>
</x-dialog-modal>