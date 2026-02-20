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
                        <textarea id="descripcionRequisicion" rows="3" wire:model.defer="descripcion"
                            class="w-full p-3 border border-zinc-300 dark:border-zinc-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 dark:text-zinc-100"
                            placeholder="Descripción detallada"></textarea>
                    </div>
                    <div>
                        <x-label for="observacionRequisicion" value="Observación" />
                        <textarea id="observacionRequisicion" rows="3" wire:model.defer="observacion"
                            class="w-full p-3 border border-zinc-300 dark:border-zinc-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 dark:text-zinc-100"
                            placeholder="Observaciones adicionales"></textarea>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 items-end">
                    <div class="relative col-span-1 w-48">
                        <x-label for="fechaRequerido" value="Fecha a requerir" />
                        <div class="relative">
                            <x-input id="fechaRequerido" type="date" wire:model.defer="fechaRequerido"
                                class="w-full p-2 pl-8 border border-zinc-300 dark:border-zinc-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" />
                            <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-zinc-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <span class="font-semibold text-zinc-900 dark:text-zinc-100 text-lg">Monto total a requerir: L {{ number_format(collect($recursosSeleccionados)->sum('total'), 2) }}</span>
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
