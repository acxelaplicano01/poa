<div class="container mx-auto py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-zinc-800 dark:text-zinc-100 mb-2">Entrega de Recursos</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white dark:bg-zinc-900 rounded-lg shadow p-4">
            <div>
                <div class="text-sm text-zinc-600 dark:text-zinc-300 mb-1"><b>Correlativo:</b> {{ $detalleRequisicion['correlativo'] ?? '-' }}</div>
                <div class="text-sm text-zinc-600 dark:text-zinc-300 mb-1"><b>Departamento:</b> {{ $detalleRequisicion['departamento'] ?? '-' }}</div>
                <div class="text-sm text-zinc-600 dark:text-zinc-300 mb-1"><b>Descripción:</b> {{ $detalleRequisicion['descripcion'] ?? '-' }}</div>
                <div class="text-sm text-zinc-600 dark:text-zinc-300 mb-1"><b>Observación:</b> {{ $detalleRequisicion['observacion'] ?? '-' }}</div>
                <div class="text-sm text-zinc-600 dark:text-zinc-300 mb-1"><b>Creado por:</b> {{ $detalleRequisicion['creador'] ?? '-' }}</div>
            </div>
            <div>
                <div class="text-sm text-zinc-600 dark:text-zinc-300 mb-1"><b>Estado:</b> {{ $detalleRequisicion['estado'] ?? '-' }}</div>
                <div class="text-sm text-zinc-600 dark:text-zinc-300 mb-1"><b>Fecha presentado:</b> {{ $detalleRequisicion['fecha_presentado'] ?? '-' }}</div>
                <div class="text-sm text-zinc-600 dark:text-zinc-300 mb-1"><b>Fecha requerido:</b> {{ $detalleRequisicion['fecha_requerido'] ?? '-' }}</div>
            </div>
        </div>
    </div>
    <div class="overflow-x-auto bg-white dark:bg-zinc-900 rounded-lg shadow border border-zinc-200 dark:border-zinc-700 p-4">
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
                </tr>
            </thead>
            <tbody>
                @foreach($recursosParaEntregar as $recurso)
                    <tr class="bg-white dark:bg-zinc-900">
                        <td class="px-4 py-2 align-top whitespace-nowrap text-zinc-700 dark:text-zinc-100">{{ $recurso['recurso'] }}</td>
                        <td class="px-4 py-2 align-top text-zinc-700 dark:text-zinc-100">{{ $recurso['detalle_tecnico'] }}</td>
                        <td class="px-4 py-2 align-top text-center text-zinc-700 dark:text-zinc-100">{{ $recurso['observacion'] ?? '-' }}</td>
                        <td class="px-4 py-2 align-top text-center text-zinc-700 dark:text-zinc-100">{{ $recurso['factura'] ?? '-' }}</td>
                        <td class="px-4 py-2 align-top text-center text-zinc-700 dark:text-zinc-100">{{ $recurso['fecha_ejecucion'] ?? '-' }}</td>
                        <td class="px-4 py-2 align-top text-center text-zinc-700 dark:text-zinc-100">
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
                        <td class="px-4 py-2 align-top text-center text-zinc-700 dark:text-zinc-100">
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
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

