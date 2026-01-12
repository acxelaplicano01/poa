
<x-dialog-modal wire:model="showEntregaModal" maxWidth="4xl">
    <x-slot name="title">
        <div class="flex flex-col gap-1">
            <span class="font-bold text-lg">Entrega de Recursos</span>
            <span class="text-sm text-zinc-700 dark:text-zinc-200">Correlativo: <b>{{ $detalleRequisicion['correlativo'] ?? '-' }}</b></span>
            <span class="text-sm text-zinc-700 dark:text-zinc-200">Departamento: <b>{{ $detalleRequisicion['departamento'] ?? '-' }}</b></span>
            <span class="text-sm text-zinc-700 dark:text-zinc-200">Descripción: <b>{{ $detalleRequisicion['descripcion'] ?? '-' }}</b></span>
            <span class="text-sm text-zinc-700 dark:text-zinc-200">Observación: <b>{{ $detalleRequisicion['observacion'] ?? '-' }}</b></span>
        </div>
    </x-slot>
    <x-slot name="content">
        <div class="mb-2">
            <span class="font-semibold text-green-600">Ejecutado | Se ha ejecutado el 100% de esta requisición</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700 mb-4">
                <thead class="bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold">Recurso requerido</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold">Detalle Técnico</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold">Observación</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold">Factura</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold">Fecha de ejecución</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold">Requerido</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold">Ejecutado</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold">Editar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recursosParaEntregar as $i => $recurso)
                        <tr class="bg-white dark:bg-zinc-900">
                            <td class="px-4 py-2 align-top whitespace-nowrap text-zinc-900 dark:text-zinc-100">
                                {{ $recurso['recurso'] }}
                            </td>
                            <td class="px-4 py-2 align-top text-zinc-900 dark:text-zinc-100">
                                {{ $recurso['detalle_tecnico'] }}
                            </td>
                            <td class="px-4 py-2 align-top text-center text-zinc-900 dark:text-zinc-100">
                                {{ $recurso['observacion'] ?? '-' }}
                            </td>
                            <td class="px-4 py-2 align-top text-center text-zinc-900 dark:text-zinc-100">
                                {{ $recurso['factura'] ?? '-' }}
                            </td>
                            <td class="px-4 py-2 align-top text-center text-zinc-900 dark:text-zinc-100">
                                {{ $recurso['fecha_ejecucion'] ?? '-' }}
                            </td>
                            <td class="px-4 py-2 align-top text-center text-zinc-600 dark:text-zinc-300">
                                <div class="flex flex-col items-center gap-2">
                                    <div class="flex flex-col items-center">
                                        <span class="bg-blue-100 text-zinc-600 px-2 py-0.5 rounded-full text-xs mb-1">Cant.</span>
                                        <span class="text-zinc-700 text-lg font-semibold">{{ $recurso['cantidad'] }}</span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <span class="bg-blue-100 text-zinc-600 px-2 py-0.5 rounded-full text-xs mb-1">Costo</span>
                                        <span class="text-zinc-700 text-lg font-semibold">L {{ number_format($recurso['monto_requerido'] / max($recurso['cantidad'],1), 2) }}</span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <span class="bg-blue-100 text-zinc-600 px-2 py-0.5 rounded-full text-xs mb-1">Total</span>
                                        <span class="text-zinc-700 text-lg font-semibold">L {{ number_format($recurso['monto_requerido'] ?? 0, 2) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-2 align-top text-center text-zinc-600 dark:text-zinc-300">
                                <div class="flex flex-col items-center gap-2">
                                    <div class="flex flex-col items-center">
                                        <span class="bg-blue-100 text-zinc-600 px-2 py-0.5 rounded-full text-xs mb-1">Cant.</span>
                                        <span class="text-zinc-700 text-lg font-semibold">{{ $recurso['entregado'] ?? 0 }}</span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <span class="bg-blue-100 text-zinc-600 px-2 py-0.5 rounded-full text-xs mb-1">Costo</span>
                                        <span class="text-zinc-700 text-lg font-semibold">L {{ number_format($recurso['monto_ejecutado'] / max($recurso['entregado'],1), 2) }}</span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <span class="bg-blue-100 text-zinc-600 px-2 py-0.5 rounded-full text-xs mb-1">Total</span>
                                        <span class="text-zinc-700 text-lg font-semibold">L {{ number_format($recurso['monto_ejecutado'] ?? 0, 2) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-2 align-top text-center text-zinc-900 dark:text-zinc-100">
                                <button title="Editar" class="p-2 rounded-full hover:bg-blue-100 text-blue-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.1 2.1 0 0 1 2.97 2.97l-9.193 9.193a2.1 2.1 0 0 1-.88.53l-3.07.922a.525.525 0 0 1-.65-.65l.922-3.07a2.1 2.1 0 0 1 .53-.88l9.193-9.193zm0 0L19.5 6.125" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="flex justify-end items-center mt-2">
            <span class="text-green-600 font-bold text-2xl">100% Ejecutado</span>
        </div>
    </x-slot>
    <x-slot name="footer">
        <button wire:click="guardarEntregaRecursos" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded transition">Guardar Entrega</button>
        <button wire:click="cerrarEntregaModal" class="bg-zinc-400 hover:bg-zinc-500 text-white font-semibold px-6 py-2 rounded transition ml-2">Cancelar</button>
    </x-slot>
</x-dialog-modal>
