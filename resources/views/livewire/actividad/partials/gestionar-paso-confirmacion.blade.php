<div class="space-y-6">
    <!-- Encabezado del paso -->
    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 p-6 rounded-lg border border-indigo-200 dark:border-indigo-800">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-zinc-800 dark:text-zinc-200 mb-2">
                    Confirmación de Actividad
                </h3>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">
                    Revisa el consolidado de toda la información antes de enviar a revisión
                </p>
            </div>
            <div class="flex items-center justify-center w-16 h-16 bg-indigo-600 rounded-full">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Información de la Actividad -->
    <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg border border-zinc-200 dark:border-zinc-700">
        <h4 class="text-lg font-semibold text-zinc-800 dark:text-zinc-200 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Información General
        </h4>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Nombre de la Actividad</p>
                <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ $actividad->nombre }}</p>
            </div>
            <div>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Descripción</p>
                <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ $actividad->descripcion ?? 'Sin descripción' }}</p>
            </div>
        </div>
    </div>

    <!-- Indicadores -->
    <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg border border-zinc-200 dark:border-zinc-700">
        <h4 class="text-lg font-semibold text-zinc-800 dark:text-zinc-200 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Indicadores ({{ is_array($indicadores) ? count($indicadores) : 0 }})
        </h4>
        @if(!empty($indicadores) && is_array($indicadores))
            <div class="space-y-3">
                @foreach($indicadores as $indicador)
                    <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <p class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $indicador['nombre'] }}</p>
                                <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">{{ $indicador['descripcion'] }}</p>
                                <div class="mt-2 flex items-center gap-4">
                                    <span class="text-xs px-2 py-1 bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-200 rounded">
                                        Meta: {{ $indicador['cantidadPlanificada'] }} {{ $indicador['isCantidad'] ? 'unidades' : '%' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-zinc-500 dark:text-zinc-400 italic">No se han agregado indicadores</p>
        @endif
    </div>

    <!-- Planificaciones -->
    <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg border border-zinc-200 dark:border-zinc-700">
        <h4 class="text-lg font-semibold text-zinc-800 dark:text-zinc-200 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Planificaciones ({{ is_array($planificaciones) ? count($planificaciones) : 0 }})
        </h4>
        @if(!empty($planificaciones) && is_array($planificaciones))
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-700">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Indicador</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Trimestre</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Cantidad</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Período</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @foreach($planificaciones as $plan)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50">
                                <td class="px-4 py-2 text-sm text-zinc-900 dark:text-zinc-100">
                                    {{ $plan['indicador']['nombre'] ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-2 text-sm text-center text-zinc-900 dark:text-zinc-100">
                                    {{ $plan['mes']['trimestre']['trimestre'] ?? ($plan['trimestre']['trimestre'] ?? 'N/A') }}
                                </td>
                                <td class="px-4 py-2 text-sm text-center font-semibold text-green-600 dark:text-green-400">
                                    {{ $plan['cantidad'] }}
                                </td>
                                <td class="px-4 py-2 text-sm text-center text-zinc-600 dark:text-zinc-400">
                                    @if(isset($plan['fechaInicio']) && isset($plan['fechaFin']))
                                        {{ date('d/m/Y', strtotime($plan['fechaInicio'])) }} - {{ date('d/m/Y', strtotime($plan['fechaFin'])) }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-sm text-zinc-500 dark:text-zinc-400 italic">No se han agregado planificaciones</p>
        @endif
    </div>

    <!-- Empleados -->
    <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg border border-zinc-200 dark:border-zinc-700">
        <h4 class="text-lg font-semibold text-zinc-800 dark:text-zinc-200 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Empleados Asignados ({{ is_array($empleadosAsignados) ? count($empleadosAsignados) : 0 }})
        </h4>
        @if(!empty($empleadosAsignados) && is_array($empleadosAsignados))
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($empleadosAsignados as $empleado)
                    <div class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-200 dark:border-purple-800">
                        <p class="font-medium text-sm text-zinc-900 dark:text-zinc-100">
                            {{ $empleado['nombre'] . ' ' . $empleado['apellido'] ?? $empleado['user']['name'] ?? 'Sin nombre' }}
                        </p>
                        <p class="text-xs text-zinc-600 dark:text-zinc-400 mt-1">
                            {{ $empleado['num_empleado'] ?? 'Empleado' }}
                        </p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-zinc-500 dark:text-zinc-400 italic">No se han asignado empleados</p>
        @endif
    </div>

    <!-- Tareas -->
    <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg border border-zinc-200 dark:border-zinc-700">
        <h4 class="text-lg font-semibold text-zinc-800 dark:text-zinc-200 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            Tareas ({{ is_array($tareas) ? count($tareas) : 0 }})
        </h4>
        @if(!empty($tareas) && is_array($tareas))
            <div class="space-y-3">
                @foreach($tareas as $tarea)
                    <div class="p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg border border-orange-200 dark:border-orange-800">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <p class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $tarea['nombre'] ?? 'Sin nombre' }}</p>
                                    @if(isset($tarea['isPresupuesto']) && $tarea['isPresupuesto'])
                                        <span class="text-xs px-2 py-1 bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200 rounded">
                                            Con Presupuesto
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">{{ $tarea['descripcion'] ?? '' }}</p>
                                <div class="mt-2 flex items-center gap-4 text-xs text-zinc-500 dark:text-zinc-400">
                                    <span>Estado: <strong>{{ $tarea['estado'] ?? 'N/A' }}</strong></span>
                                    @if(isset($tarea['fechaInicio']) && isset($tarea['fechaFin']))
                                        <span>Período: {{ date('d/m/Y', strtotime($tarea['fechaInicio'])) }} - {{ date('d/m/Y', strtotime($tarea['fechaFin'])) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-zinc-500 dark:text-zinc-400 italic">No se han agregado tareas</p>
        @endif
    </div>

    <!-- Advertencia final -->
    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
        <div class="flex">
            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <h5 class="font-semibold text-yellow-800 dark:text-yellow-200 mb-1">Importante</h5>
                <p class="text-sm text-yellow-700 dark:text-yellow-300">
                    Al enviar a revisión, esta actividad será revisada por el supervisor correspondiente. 
                    Asegúrate de que toda la información esté completa y correcta antes de continuar.
                </p>
            </div>
        </div>
    </div>
</div>
