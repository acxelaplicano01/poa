{{-- Modal Confirmar Eliminación --}}
<x-confirmation-modal wire:model="modalDelete">
    <x-slot name="title">
        Eliminar Actividad
    </x-slot>

    <x-slot name="content">
        @if($actividadToDelete)
            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                ¿Está seguro de que desea eliminar la actividad "<strong class="text-zinc-900 dark:text-zinc-100">{{ $actividadToDelete->nombre }}</strong>"?
            </p>
            
            <div class="mt-4 bg-zinc-50 dark:bg-zinc-800/50 rounded-lg p-4 space-y-2 text-sm">
                @if($actividadToDelete->tipoActividad)
                    <div>
                        <span class="font-medium text-zinc-700 dark:text-zinc-300">Tipo:</span>
                        <span class="text-zinc-600 dark:text-zinc-400">{{ $actividadToDelete->tipoActividad->tipo }}</span>
                    </div>
                @endif
                
                @if($actividadToDelete->resultado)
                    <div>
                        <span class="font-medium text-zinc-700 dark:text-zinc-300">Vinculado a:</span>
                        <span class="text-zinc-600 dark:text-zinc-400">{{ $actividadToDelete->resultado->nombre }}</span>
                    </div>
                @endif
            </div>

            <p class="text-sm text-red-600 dark:text-red-400 mt-4">
                Esta acción no se puede deshacer.
            </p>
        @endif
    </x-slot>

    <x-slot name="footer">
        <x-secondary-button wire:click="$set('modalDelete', false)">
            Cancelar
        </x-secondary-button>

        <x-danger-button wire:click="eliminar" class="ml-3">
            Eliminar Actividad
        </x-danger-button>
    </x-slot>
</x-confirmation-modal>
