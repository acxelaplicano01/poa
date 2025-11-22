{{-- Modal Confirmar Eliminación --}}
<x-confirmation-modal wire:model="modalDelete" maxWidth="lg">
    <x-slot name="title">
        Eliminar Actividad
    </x-slot>

    <x-slot name="content">
        @if($actividadToDelete)
            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                ¿Está seguro de que desea eliminar la actividad "<strong class="text-zinc-900 dark:text-zinc-100">{{ $actividadToDelete->nombre }}</strong>"?
            </p>
            
            <div class="mt-4 bg-zinc-50 dark:bg-zinc-800/50 rounded-lg p-4 space-y-2 text-sm">
                @if($actividadToDelete->tipo)
                    <div>
                        <span class="font-medium text-zinc-700 dark:text-zinc-300">Tipo:</span>
                        <span class="text-zinc-600 dark:text-zinc-400">{{ $actividadToDelete->tipo->tipo }}</span>
                    </div>
                @endif
                
                @if($actividadToDelete->resultado)
                    <div>
                        <span class="font-medium text-zinc-700 dark:text-zinc-300">Vinculado a:</span>
                        <span class="text-zinc-600 dark:text-zinc-400">{{ $actividadToDelete->resultado->nombre }}</span>
                    </div>
                @endif
            </div>

            <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700 dark:text-yellow-200">
                            {{ __('Esta acción no se puede deshacer. Se eliminarán permanentemente todos los datos asociados.') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </x-slot>

    <x-slot name="footer">
        <x-spinner-secondary-button wire:click="$set('modalDelete', false)" loadingTarget="$set('modalDelete', false)" loadingText="Cerrando...">
            Cancelar
        </x-spinner-secondary-button>

        <x-spinner-danger-button wire:click="eliminar" loadingTarget="eliminar" loadingText="Eliminando..." class="ml-3">
            Eliminar Actividad
        </x-spinner-danger-button>
    </x-slot>
</x-confirmation-modal>
