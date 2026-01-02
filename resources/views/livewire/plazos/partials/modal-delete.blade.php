<!-- Modal de confirmación de eliminación -->
<x-dialog-modal wire:model="modalDelete" maxWidth="md">
    <x-slot name="title">
        Eliminar Plazo
    </x-slot>

    <x-slot name="content">
        @if($plazoToDelete)
            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                ¿Está seguro que desea eliminar este plazo? Esta acción no se puede deshacer.
            </p>
            <div class="mt-4 p-4 bg-zinc-50 dark:bg-zinc-900 rounded-lg">
                <dl class="space-y-2 text-sm">
                    <div>
                        <dt class="font-medium text-zinc-700 dark:text-zinc-300">POA:</dt>
                        <dd class="text-zinc-600 dark:text-zinc-400">{{ $plazoToDelete->poa->anio }} - {{ $plazoToDelete->poa->name }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-zinc-700 dark:text-zinc-300">Tipo:</dt>
                        <dd class="text-zinc-600 dark:text-zinc-400">{{ $plazoToDelete->tipo_plazo_label }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-zinc-700 dark:text-zinc-300">Período:</dt>
                        <dd class="text-zinc-600 dark:text-zinc-400">
                            {{ $plazoToDelete->fecha_inicio->format('d/m/Y') }} - {{ $plazoToDelete->fecha_fin->format('d/m/Y') }}
                        </dd>
                    </div>
                </dl>
            </div>
        @endif
    </x-slot>

    <x-slot name="footer">
        <x-spinner-secondary-button wire:click="closeDelete" type="button" loadingTarget="closeDelete" loadingText="Cerrando...">
                {{ __('Cancelar') }}
        </x-spinner-secondary-button>

        <x-spinner-danger-button class="ml-3" wire:click="eliminar" loadingTarget="eliminar" loadingText="Eliminando...">
            Eliminar
        </x-spinner-danger-button>
    </x-slot>
</x-dialog-modal>
