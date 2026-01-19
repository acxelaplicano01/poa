<!-- Modal de sumario de recursos seleccionados usando wire:model -->
<x-dialog-modal wire:model="showSumarioModal" maxWidth="4xl">
    <x-slot name="title">
        {{ $isEditing ? __('Editar Requisición') : __('Sumario de Recursos Seleccionados') }}
    </x-slot>
    <x-slot name="content">
        <div class="space-y-6">
            <!-- Tabla de recursos seleccionados -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700 mb-4">
                    <thead class="bg-zinc-50 dark:bg-zinc-700">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Recurso</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Actividad</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Proceso Compra</th>
                            <th class="px-3 py-2 text-center text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Cantidad Seleccionada</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Unidad de Medida</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Precio Unitario</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Total</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recursosSeleccionados as $recurso)
                            <tr>
                                <td class="px-3 py-2 text-zinc-900 dark:text-zinc-100">{{ $recurso['nombre'] ?? '-' }}</td>
                                <td class="px-3 py-2 text-zinc-600 dark:text-zinc-400">{{ $recurso['actividad'] ?? '-' }}</td>
                                <td class="px-3 py-2 text-zinc-600 dark:text-zinc-400">{{ $recurso['proceso_compra'] ?? '-' }}</td>
                                <td class="px-3 py-2 text-center text-zinc-600 dark:text-zinc-400">{{ $recurso['cantidad_seleccionada'] ?? '-' }}</td>
                                <td class="px-3 py-2 text-zinc-600 dark:text-zinc-400">{{ $recurso['unidad_medida'] ?? '-' }}</td>
                                <td class="px-3 py-2 text-zinc-600 dark:text-zinc-400">L {{ number_format($recurso['precio_unitario'] ?? 0, 2) }}</td>
                                <td class="px-3 py-2 text-zinc-600 dark:text-zinc-400 font-bold">L {{ number_format($recurso['total'] ?? 0, 2) }}</td>
                                <td class="px-3 py-2">
                                    @if($isEditing)
                                    <button wire:click="quitarRecursoDelSumario({{ $recurso['id'] }})" class="text-red-600 hover:text-red-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-3 py-2 text-center text-zinc-500 dark:text-zinc-400">
                                    {{ __('No hay recursos seleccionados.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Formulario para editar requisición -->
            <div class="bg-zinc-50 dark:bg-zinc-700 p-4 rounded-lg space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-label for="descripcionRequisicion" value="Descripción" />
                        <x-input id="descripcionRequisicion" type="text" wire:model.defer="descripcion" placeholder="Descripción" />
                    </div>
                    <div>
                        <x-label for="observacionRequisicion" value="Observación" />
                        <x-input id="observacionRequisicion" type="text" wire:model.defer="observacion" placeholder="Observación" />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 items-end">
                    <div>
                        <x-label for="fechaRequerido" value="Fecha a requerir" />
                        <x-input id="fechaRequerido" type="date" wire:model.defer="fechaRequerido" placeholder="dd/mm/aaaa" />
                    </div>
                    <div class="flex justify-end">
                        <span class="font-semibold text-zinc-900 dark:text-zinc-100">Monto total a requerir: L {{ number_format(collect($recursosSeleccionados)->sum('total'), 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>
    <x-slot name="footer">
        <div class="flex justify-end gap-2">
            <button wire:click="cerrarSumario" class="px-4 py-2 bg-zinc-400 text-white rounded hover:bg-zinc-500">Cerrar</button>
            @if($isEditing)
            <button wire:click="guardarEdicionRequisicion" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 font-semibold">Guardar Cambios</button>
            @endif
        </div>
    </x-slot>
</x-dialog-modal>
