<div class="mx-auto rounded-lg mt-8 sm:mt-6 lg:mt-4 mb-6">
    <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow sm:rounded-lg p-4 sm:p-6">

        <!-- Encabezado con información del POA -->
        <div class="mb-6 pb-4 border-b border-zinc-200 dark:border-zinc-700">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                <div>
                    <a href="{{ route('techonacional', ['idPoa' => $poa->id]) }}" class="inline-flex items-center text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Volver a Gestión de Techos
                    </a>
                    <h2 class="text-xl font-semibold text-zinc-800 dark:text-zinc-200 mt-2">
                        Análisis Presupuestario: {{ $unidadEjecutora->name ?? 'N/A' }}
                    </h2>
                    <div class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                        <p>{{ $unidadEjecutora->descripcion ?? 'Sin descripción' }}</p>
                        <p class="mt-1">POA: <span class="font-semibold text-zinc-700 dark:text-zinc-300">{{ $poa->anio ?? 'N/A' }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjetas de Resumen Presupuestario -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Resumen Presupuestario</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                <!-- Presupuesto General -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-900/40 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-medium text-blue-700 dark:text-blue-300 uppercase tracking-wide">Presupuesto General</p>
                            <p class="text-lg sm:text-xl font-bold text-blue-900 dark:text-blue-100 mt-2">
                                L. {{ number_format($presupuestoGeneral, 2) }}
                            </p>
                        </div>
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M8 7V6a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1h-1M3 18v-7a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                        </svg>
                    </div>
                </div>

                <!-- Presupuesto Asignado -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-900/40 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-medium text-green-700 dark:text-green-300 uppercase tracking-wide">Asignado</p>
                            <p class="text-lg sm:text-xl font-bold text-green-900 dark:text-green-100 mt-2">
                                L. {{ number_format($presupuestoAsignado, 2) }}
                            </p>
                        </div>
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 dark:text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Presupuesto Planificado -->
                <div class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/20 dark:to-amber-900/40 border border-amber-200 dark:border-amber-800 rounded-lg p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-medium text-amber-700 dark:text-amber-300 uppercase tracking-wide">Planificado</p>
                            <p class="text-lg sm:text-xl font-bold text-amber-900 dark:text-amber-100 mt-2">
                                L. {{ number_format($presupuestoPlanificado, 2) }}
                            </p>
                        </div>
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-amber-600 dark:text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>

                <!-- Presupuesto Requerido -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-900/40 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-medium text-purple-700 dark:text-purple-300 uppercase tracking-wide">Requerido</p>
                            <p class="text-lg sm:text-xl font-bold text-purple-900 dark:text-purple-100 mt-2">
                                L. {{ number_format($presupuestoRequerido, 2) }}
                            </p>
                        </div>
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600 dark:text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>

                <!-- Presupuesto Ejecutado -->
                <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-900/40 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-medium text-red-700 dark:text-red-300 uppercase tracking-wide">Ejecutado</p>
                            <p class="text-lg sm:text-xl font-bold text-red-900 dark:text-red-100 mt-2">
                                L. {{ number_format($presupuestoEjecutado, 2) }}
                            </p>
                        </div>
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-600 dark:text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Distribución por Fuente -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Distribución por Fuente de Financiamiento</h3>
            
            <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-700">
                            <tr>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Fuente</th>
                                <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">General</th>
                                <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">%</th>
                                <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Barra</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @forelse($techos as $techo)
                                @php
                                    $percentage = $presupuestoGeneral > 0 ? ($techo['monto'] / $presupuestoGeneral) * 100 : 0;
                                    $fuente = $techo['fuente']['identificador'] . ' - ' . $techo['fuente']['nombre'] ?? 'Sin fuente';
                                @endphp
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50">
                                    <td class="px-4 sm:px-6 py-3 text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                        {{ $fuente }}
                                    </td>
                                    <td class="px-4 sm:px-6 py-3 text-sm text-right font-semibold text-zinc-900 dark:text-zinc-100">
                                        L. {{ number_format($techo['monto'], 2) }}
                                    </td>
                                    <td class="px-4 sm:px-6 py-3 text-sm text-right text-zinc-600 dark:text-zinc-400">
                                        {{ number_format($percentage, 1) }}%
                                    </td>
                                    <td class="px-4 sm:px-6 py-3 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <div class="w-16 sm:w-24 bg-zinc-200 dark:bg-zinc-600 rounded-full h-2">
                                                <div class="bg-indigo-600 dark:bg-indigo-500 h-2 rounded-full transition-all duration-300" 
                                                     style="width: {{ $percentage }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 sm:px-6 py-6 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                        No hay techos asignados para esta unidad ejecutora
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Indicadores de Ejecución -->
        <div>
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Indicadores de Ejecución</h3>
            
            <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg p-4 sm:p-6">
                <div class="space-y-5">
                    <!-- Indicador 1: General vs Ejecutado -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-sm font-medium text-zinc-600 dark:text-zinc-400">General vs Ejecutado</span>
                            </div>
                            @php
                                $porcentajeEjecucion = $presupuestoGeneral > 0 ? ($presupuestoEjecutado / $presupuestoGeneral) * 100 : 0;
                            @endphp
                            <span class="text-sm font-bold text-zinc-900 dark:text-zinc-100">{{ number_format($porcentajeEjecucion, 1) }}%</span>
                        </div>
                        <div class="w-full bg-zinc-200 dark:bg-zinc-600 rounded-full h-2.5">
                            <div class="bg-green-500 dark:bg-green-600 h-2.5 rounded-full transition-all duration-300" 
                                 style="width: {{ min($porcentajeEjecucion, 100) }}%"></div>
                        </div>
                    </div>

                    <!-- Indicador 2: Planificado vs Ejecutado -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                <span class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Planificado vs Ejecutado</span>
                            </div>
                            @php
                                $porcentajePlanificacion = $presupuestoPlanificado > 0 ? ($presupuestoEjecutado / $presupuestoPlanificado) * 100 : 0;
                            @endphp
                            <span class="text-sm font-bold text-zinc-900 dark:text-zinc-100">{{ number_format($porcentajePlanificacion, 1) }}%</span>
                        </div>
                        <div class="w-full bg-zinc-200 dark:bg-zinc-600 rounded-full h-2.5">
                            <div class="bg-blue-500 dark:bg-blue-600 h-2.5 rounded-full transition-all duration-300" 
                                 style="width: {{ min($porcentajePlanificacion, 100) }}%"></div>
                        </div>
                    </div>

                    <!-- Indicador 3: Requerido vs Ejecutado -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-amber-500 rounded-full"></div>
                                <span class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Requerido vs Ejecutado</span>
                            </div>
                            @php
                                $porcentajeRequerido = $presupuestoRequerido > 0 ? ($presupuestoEjecutado / $presupuestoRequerido) * 100 : 0;
                            @endphp
                            <span class="text-sm font-bold text-zinc-900 dark:text-zinc-100">{{ number_format($porcentajeRequerido, 1) }}%</span>
                        </div>
                        <div class="w-full bg-zinc-200 dark:bg-zinc-600 rounded-full h-2.5">
                            <div class="bg-amber-500 dark:bg-amber-600 h-2.5 rounded-full transition-all duration-300" 
                                 style="width: {{ min($porcentajeRequerido, 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Nota informativa -->
    <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
        <div class="flex gap-3">
            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm text-blue-800 dark:text-blue-300">
                <strong>Nota:</strong> Este análisis muestra el estado presupuestario consolidado de la unidad ejecutora. Los datos se actualizan en tiempo real con la información registrada en el sistema.
            </p>
        </div>
    </div>
    </div>
</div>
</div>
