{{-- Modal: Detalles de Tarea --}}
<x-dialog-modal wire:model="showTareaModal" maxWidth="4xl">
    <x-slot name="title">
        @if($tareaSeleccionada)
            Detalles de Presupuesto - {{ $tareaSeleccionada->nombre }}
        @endif
    </x-slot>

    <x-slot name="content">
        @if($tareaSeleccionada)
            <!-- Información básica -->
            <div class="mb-6 pb-4 border-b border-zinc-200 dark:border-zinc-700">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Correlativo</p>
                        <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mt-1">{{ $tareaSeleccionada->correlativo ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Tarea</p>
                        <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mt-1">{{ $tareaSeleccionada->nombre }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Descripción</p>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">{{ $tareaSeleccionada->descripcion }}</p>
                    </div>
                </div>
            </div>

            <!-- Presupuestos -->
            @if($tareaSeleccionada->presupuestos->count() > 0)
                <div>
                    <h4 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Detalles de Presupuesto</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                            <thead class="bg-zinc-50 dark:bg-zinc-700">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Recurso</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Detalle Técnico</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Objeto de Gasto</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Mes</th>
                                    <th class="px-3 py-2 text-center text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Cantidad</th>
                                    <th class="px-3 py-2 text-center text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">U.M.</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Unitario</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Total</th>
                                    <th class="px-3 py-2 text-center text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Fuente</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                                @foreach($tareaSeleccionada->presupuestos as $presupuesto)
                                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50">
                                        <td class="px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100">{{ $presupuesto->recurso ?? 'N/A' }}</td>
                                        <td class="px-3 py-2 text-sm text-zinc-600 dark:text-zinc-400 max-w-xs truncate">{{ $presupuesto->detalle_tecnico ?? '-' }}</td>
                                        <td class="px-3 py-2 text-sm text-zinc-600 dark:text-zinc-400">{{ $presupuesto->objetoGasto->nombre ?? 'N/A' }}</td>
                                        <td class="px-3 py-2 text-sm text-zinc-600 dark:text-zinc-400">{{ $presupuesto->mes->mes ?? 'N/A' }}</td>
                                        <td class="px-3 py-2 text-sm text-center text-zinc-600 dark:text-zinc-400">{{ $presupuesto->cantidad }}</td>
                                        <td class="px-3 py-2 text-sm text-center text-zinc-600 dark:text-zinc-400">{{ $presupuesto->unidadMedida->nombre ?? 'N/A' }}</td>
                                        <td class="px-3 py-2 text-sm text-right text-zinc-900 dark:text-zinc-100 font-semibold">L. {{ number_format($presupuesto->unitario ?? 0, 2) }}</td>
                                        <td class="px-3 py-2 text-sm text-right text-green-700 dark:text-green-400 font-semibold">L. {{ number_format($presupuesto->total ?? 0, 2) }}</td>
                                        <td class="px-3 py-2 text-sm text-center text-zinc-600 dark:text-zinc-400">{{ $presupuesto->fuente->identificador . ' - ' . $presupuesto->fuente->nombre ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-zinc-50 dark:bg-zinc-700">
                                <tr>
                                    <td colspan="7" class="px-3 py-3 text-right text-sm font-bold text-zinc-900 dark:text-zinc-100">Total:</td>
                                    <td class="px-3 py-3 text-right text-sm font-bold text-green-700 dark:text-green-400">
                                        L. {{ number_format($tareaSeleccionada->presupuestos->sum('total') ?? 0, 2) }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @else
                <div class="text-center py-6">
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">No hay presupuestos registrados para esta tarea</p>
                </div>
            @endif
        @endif
    </x-slot>

    <x-slot name="footer">
        <x-secondary-button wire:click="closeTareaModal">
            {{ __('Cerrar') }}
        </x-secondary-button>
    </x-slot>
</x-dialog-modal>
