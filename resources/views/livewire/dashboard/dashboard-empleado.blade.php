<div class="mx-auto rounded-lg mt-8 sm:mt-6 lg:mt-4 mb-6">
    <div class="space-y-6">
        <!-- Encabezado -->
        <div class="bg-white dark:bg-zinc-900 shadow sm:rounded-lg p-6">
            <div class="flex flex-col gap-4">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">
                            Dashboard de Presupuesto
                        </h1>
                        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                            Visualización de estadísticas presupuestarias
                            @if($poaActivo)
                                - POA {{ $poaActivo->anio }}
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @if(count($departamentos) > 0)
                        <div>
                            <label for="departamento" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                                Departamento
                            </label>
                            <select 
                                wire:model.live="departamentoSeleccionado" 
                                id="departamento"
                                class="block w-full rounded-md border-zinc-300 dark:border-zinc-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-zinc-800 dark:text-zinc-100 sm:text-sm"
                            >
                                @foreach($departamentos as $depto)
                                    <option value="{{ $depto['id'] }}">{{ $depto['nombre'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if(count($anios) > 0)
                        <div>
                            <label for="anio" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                                Año
                            </label>
                            <select 
                                wire:model.live="anioSeleccionado" 
                                id="anio"
                                class="block w-full rounded-md border-zinc-300 dark:border-zinc-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-zinc-800 dark:text-zinc-100 sm:text-sm"
                            >
                                <option value="">Todos los años</option>
                                @foreach($anios as $anio)
                                    <option value="{{ $anio }}">{{ $anio }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if(count($trimestres) > 0)
                        <div>
                            <label for="trimestre" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                                Trimestre
                            </label>
                            <select 
                                wire:model.live="trimestreSeleccionado" 
                                id="trimestre"
                                class="block w-full rounded-md border-zinc-300 dark:border-zinc-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-zinc-800 dark:text-zinc-100 sm:text-sm"
                            >
                                <option value="">Todos los trimestres</option>
                                @foreach($trimestres as $trimestre)
                                    <option value="{{ $trimestre['id'] }}">{{ $trimestre['nombre'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if(empty($departamentos))
            <!-- Sin departamentos asignados -->
            <div class="bg-white dark:bg-zinc-900 shadow sm:rounded-lg p-12">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">Sin departamentos asignados</h3>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                        No tienes departamentos asignados. Contacta con el administrador.
                    </p>
                </div>
            </div>
        @elseif(!$poaActivo)
            <!-- Sin POA activo -->
            <div class="bg-white dark:bg-zinc-900 shadow sm:rounded-lg p-12">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">Sin POA activo</h3>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                        No hay un POA activo en el sistema.
                    </p>
                </div>
            </div>
        @else
            <!-- Tarjetas de Estadísticas Generales -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Presupuesto Asignado -->
                <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400 truncate">
                                        Presupuesto Asignado
                                    </dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">
                                            L {{ number_format($estadisticasGenerales['presupuestoAsignado'] ?? 0, 2) }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Presupuesto Planificado -->
                <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400 truncate">
                                        Presupuesto Planificado
                                    </dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">
                                            L {{ number_format($estadisticasGenerales['presupuestoPlanificado'] ?? 0, 2) }}
                                        </div>
                                        <div class="ml-2 flex items-baseline text-sm font-semibold text-blue-600">
                                            {{ $estadisticasGenerales['porcentajePlanificado'] ?? 0 }}%
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Presupuesto Ejecutado -->
                <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400 truncate">
                                        Presupuesto Ejecutado
                                    </dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">
                                            L {{ number_format($estadisticasGenerales['presupuestoEjecutado'] ?? 0, 2) }}
                                        </div>
                                        <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                            {{ $estadisticasGenerales['porcentajeEjecutado'] ?? 0 }}%
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Presupuesto Disponible -->
                <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400 truncate">
                                        Presupuesto Disponible
                                    </dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">
                                            L {{ number_format($estadisticasGenerales['presupuestoDisponible'] ?? 0, 2) }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficos -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Gráfico de Presupuesto General -->
                <div class="bg-white dark:bg-zinc-900 shadow sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100 mb-4">
                        Distribución del Presupuesto
                    </h3>
                    <div class="h-80">
                        <canvas id="chartPresupuestoGeneral"></canvas>
                    </div>
                </div>

                <!-- Gráfico de Actividades -->
                <div class="bg-white dark:bg-zinc-900 shadow sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100 mb-4">
                        Estado de Actividades
                    </h3>
                    <div class="h-80">
                        <canvas id="chartActividades"></canvas>
                    </div>
                </div>
            </div>

            <!-- Gráfico de Presupuesto por Fuente -->
            @if(count($estadisticasPorFuente) > 0)
            <div class="bg-white dark:bg-zinc-900 shadow sm:rounded-lg p-6" wire:loading.class="opacity-50">
                <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100 mb-4">
                    Presupuesto por Fuente de Financiamiento
                </h3>
                <div class="h-96 relative" wire:ignore>
                    <canvas id="chartPresupuestoPorFuente"></canvas>
                    <div wire:loading class="absolute inset-0 flex items-center justify-center bg-white/80 dark:bg-zinc-900/80">
                        <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            @endif

            <!-- Gráfico de Presupuesto por Grupo de Gasto -->
            @if(count($estadisticasPorGrupoGasto) > 0)
            <div class="bg-white dark:bg-zinc-900 shadow sm:rounded-lg p-6" wire:loading.class="opacity-50">
                <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100 mb-4">
                    Top 10 - Presupuesto por Grupo de Gasto
                </h3>
                <div class="h-96 relative" wire:ignore>
                    <canvas id="chartPresupuestoPorGrupoGasto"></canvas>
                    <div wire:loading class="absolute inset-0 flex items-center justify-center bg-white/80 dark:bg-zinc-900/80">
                        <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-zinc-900 shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100 mb-4">
                    Presupuesto Planificado vs Ejecutado por Mes
                </h3>
                <div class="h-96" wire:ignore>
                    <canvas id="chartPresupuestoMensual"></canvas>
                </div>
            </div>
            @endif

            <!-- Actividades Recientes -->
            @if(count($actividadesRecientes) > 0)
            <div class="bg-white dark:bg-zinc-900 shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100 mb-4">
                        Actividades Recientes
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                            <thead>
                                <tr>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                        Actividad
                                    </th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                        Tipo
                                    </th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                        Categoría
                                    </th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th class="px-3 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                        Presupuesto
                                    </th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                        Última Actualización
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                                @foreach($actividadesRecientes as $actividad)
                                <tr>
                                    <td class="px-3 py-4 text-sm text-zinc-900 dark:text-zinc-100">
                                        {{ $actividad['nombre'] }}
                                    </td>
                                    <td class="px-3 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                                        {{ $actividad['tipo'] }}
                                    </td>
                                    <td class="px-3 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                                        {{ $actividad['categoria'] }}
                                    </td>
                                    <td class="px-3 py-4 text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $actividad['estado'] === 'APROBADO' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                            {{ $actividad['estado'] === 'REVISION' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                            {{ $actividad['estado'] === 'RECHAZADO' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                        ">
                                            {{ $actividad['estado'] }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-4 text-sm text-right text-zinc-900 dark:text-zinc-100">
                                        L {{ number_format($actividad['presupuesto'], 2) }}
                                    </td>
                                    <td class="px-3 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                                        {{ $actividad['updated_at'] }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        @endif
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        let charts = {};
        
        function initializeCharts() {
            // Destruir charts existentes
            Object.values(charts).forEach(chart => {
                if (chart) chart.destroy();
            });
            charts = {};
        
        const isDarkMode = document.documentElement.classList.contains('dark');
        
        const chartColors = {
            primary: 'rgb(79, 70, 229)',
            secondary: 'rgb(99, 102, 241)',
            success: 'rgb(34, 197, 94)',
            warning: 'rgb(251, 146, 60)',
            danger: 'rgb(239, 68, 68)',
            info: 'rgb(59, 130, 246)',
        };

        const textColor = isDarkMode ? 'rgb(212, 212, 216)' : 'rgb(63, 63, 70)';
        const gridColor = isDarkMode ? 'rgba(82, 82, 91, 0.2)' : 'rgba(228, 228, 231, 0.2)';

        const defaultOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: textColor,
                        font: {
                            family: 'Inter, system-ui, sans-serif'
                        }
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        color: textColor,
                        callback: function(value) {
                            return 'L ' + value.toLocaleString('es-HN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                        }
                    },
                    grid: {
                        color: gridColor
                    }
                },
                x: {
                    ticks: {
                        color: textColor
                    },
                    grid: {
                        color: gridColor
                    }
                }
            }
        };

        // Gráfico de Presupuesto General (Doughnut)
        const ctxGeneral = document.getElementById('chartPresupuestoGeneral');
        if (ctxGeneral) {
            const planificadoSolo = {{ $estadisticasGenerales['presupuestoPlanificado'] ?? 0 }} - {{ $estadisticasGenerales['presupuestoEjecutado'] ?? 0 }};
            charts.general = new Chart(ctxGeneral, {
                type: 'doughnut',
                data: {
                    labels: ['Planificado (No Ejecutado)', 'Ejecutado', 'Disponible'],
                    datasets: [{
                        data: [
                            planificadoSolo > 0 ? planificadoSolo : 0,
                            {{ $estadisticasGenerales['presupuestoEjecutado'] ?? 0 }},
                            {{ $estadisticasGenerales['presupuestoDisponible'] ?? 0 }}
                        ],
                        backgroundColor: [
                            chartColors.info,
                            chartColors.success,
                            chartColors.warning,
                        ],
                        borderWidth: 2,
                        borderColor: isDarkMode ? 'rgb(24, 24, 27)' : 'rgb(255, 255, 255)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: textColor,
                                font: {
                                    family: 'Inter, system-ui, sans-serif'
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': L ' + context.parsed.toLocaleString('es-HN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                }
                            }
                        }
                    }
                }
            });
        }

        // Gráfico de Actividades (Pie)
        const ctxActividades = document.getElementById('chartActividades');
        if (ctxActividades) {
            charts.actividades = new Chart(ctxActividades, {
                type: 'pie',
                data: {
                    labels: ['Aprobadas', 'En Revisión', 'Otras'],
                    datasets: [{
                        data: [
                            {{ $estadisticasGenerales['actividadesAprobadas'] ?? 0 }},
                            {{ $estadisticasGenerales['actividadesEnRevision'] ?? 0 }},
                            {{ ($estadisticasGenerales['totalActividades'] ?? 0) - ($estadisticasGenerales['actividadesAprobadas'] ?? 0) - ($estadisticasGenerales['actividadesEnRevision'] ?? 0) }}
                        ],
                        backgroundColor: [
                            chartColors.success,
                            chartColors.warning,
                            chartColors.danger,
                        ],
                        borderWidth: 2,
                        borderColor: isDarkMode ? 'rgb(24, 24, 27)' : 'rgb(255, 255, 255)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: textColor,
                                font: {
                                    family: 'Inter, system-ui, sans-serif'
                                }
                            }
                        }
                    }
                }
            });
        }

        // Gráfico de Presupuesto por Fuente (Bar)
        const ctxPorFuente = document.getElementById('chartPresupuestoPorFuente');
        if (ctxPorFuente) {
            const datosFuentes = @json($estadisticasPorFuente);
            
            charts.porFuente = new Chart(ctxPorFuente, {
                type: 'bar',
                data: {
                    labels: datosFuentes.map(f => f.fuente),
                    datasets: [
                        {
                            label: 'Asignado',
                            data: datosFuentes.map(f => f.asignado),
                            backgroundColor: chartColors.primary,
                        },
                        {
                            label: 'Planificado',
                            data: datosFuentes.map(f => f.planificado),
                            backgroundColor: chartColors.info,
                        },
                        {
                            label: 'Ejecutado',
                            data: datosFuentes.map(f => f.ejecutado),
                            backgroundColor: chartColors.success,
                        }
                    ]
                },
                options: defaultOptions
            });
        }

        // Gráfico de Presupuesto por Grupo de Gasto (Horizontal Bar)
        const ctxPorGrupoGasto = document.getElementById('chartPresupuestoPorGrupoGasto');
        if (ctxPorGrupoGasto) {
            const datosGrupos = @json($estadisticasPorGrupoGasto);
            
            charts.porGrupoGasto = new Chart(ctxPorGrupoGasto, {
                type: 'bar',
                data: {
                    labels: datosGrupos.map(g => g.grupo),
                    datasets: [
                        {
                            label: 'Planificado',
                            data: datosGrupos.map(g => g.planificado),
                            backgroundColor: chartColors.info,
                        },
                        {
                            label: 'Ejecutado',
                            data: datosGrupos.map(g => g.ejecutado),
                            backgroundColor: chartColors.success,
                        }
                    ]
                },
                options: {
                    ...defaultOptions,
                    indexAxis: 'y',
                }
            });
        }

        // Gráfico de Presupuesto Mensual (Line)
        const ctxMensual = document.getElementById('chartPresupuestoMensual');
        if (ctxMensual) {
            const datosMensuales = @json($estadisticasMensuales);
            
            charts.mensual = new Chart(ctxMensual, {
                type: 'line',
                data: {
                    labels: datosMensuales.map(m => m.mes),
                    datasets: [
                        {
                            label: 'Planificado',
                            data: datosMensuales.map(m => m.planificado),
                            borderColor: chartColors.info,
                            backgroundColor: chartColors.info + '33',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Ejecutado',
                            data: datosMensuales.map(m => m.ejecutado),
                            borderColor: chartColors.success,
                            backgroundColor: chartColors.success + '33',
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: defaultOptions
            });
        }
    }
    
    // Función para actualizar charts con datos de Livewire
    function updateChartsWithLivewireData() {
        // Destruir charts existentes
        Object.values(charts).forEach(chart => {
            if (chart) chart.destroy();
        });
        charts = {};
        
        // Obtener datos actualizados desde las propiedades de Livewire
        const estadisticasGenerales = @this.estadisticasGenerales || {};
        const estadisticasPorFuente = @this.estadisticasPorFuente || [];
        const estadisticasPorGrupoGasto = @this.estadisticasPorGrupoGasto || [];
        const estadisticasMensuales = @this.estadisticasMensuales || [];
        
        const isDarkMode = document.documentElement.classList.contains('dark');
        
        const chartColors = {
            primary: 'rgb(79, 70, 229)',
            secondary: 'rgb(99, 102, 241)',
            success: 'rgb(34, 197, 94)',
            warning: 'rgb(251, 146, 60)',
            danger: 'rgb(239, 68, 68)',
            info: 'rgb(59, 130, 246)',
        };

        const textColor = isDarkMode ? 'rgb(212, 212, 216)' : 'rgb(63, 63, 70)';
        const gridColor = isDarkMode ? 'rgba(82, 82, 91, 0.2)' : 'rgba(228, 228, 231, 0.2)';

        const defaultOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: textColor,
                        font: {
                            family: 'Inter, system-ui, sans-serif'
                        }
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        color: textColor,
                        callback: function(value) {
                            return 'L ' + value.toLocaleString('es-HN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                        }
                    },
                    grid: {
                        color: gridColor
                    }
                },
                x: {
                    ticks: {
                        color: textColor
                    },
                    grid: {
                        color: gridColor
                    }
                }
            }
        };

        // Gráfico de Presupuesto General (Doughnut)
        const ctxGeneral = document.getElementById('chartPresupuestoGeneral');
        if (ctxGeneral) {
            const planificado = parseFloat(estadisticasGenerales.presupuestoPlanificado || 0);
            const ejecutado = parseFloat(estadisticasGenerales.presupuestoEjecutado || 0);
            const disponible = parseFloat(estadisticasGenerales.presupuestoDisponible || 0);
            const planificadoSolo = planificado - ejecutado;
            
            charts.general = new Chart(ctxGeneral, {
                type: 'doughnut',
                data: {
                    labels: ['Planificado (No Ejecutado)', 'Ejecutado', 'Disponible'],
                    datasets: [{
                        data: [
                            planificadoSolo > 0 ? planificadoSolo : 0,
                            ejecutado,
                            disponible
                        ],
                        backgroundColor: [
                            chartColors.info,
                            chartColors.success,
                            chartColors.warning,
                        ],
                        borderWidth: 2,
                        borderColor: isDarkMode ? 'rgb(24, 24, 27)' : 'rgb(255, 255, 255)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: textColor,
                                font: {
                                    family: 'Inter, system-ui, sans-serif'
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': L ' + context.parsed.toLocaleString('es-HN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                }
                            }
                        }
                    }
                }
            });
        }

        // Gráfico de Actividades (Pie)
        const ctxActividades = document.getElementById('chartActividades');
        if (ctxActividades) {
            const total = parseInt(estadisticasGenerales.totalActividades || 0);
            const aprobadas = parseInt(estadisticasGenerales.actividadesAprobadas || 0);
            const revision = parseInt(estadisticasGenerales.actividadesEnRevision || 0);
            const otras = total - aprobadas - revision;
            
            charts.actividades = new Chart(ctxActividades, {
                type: 'pie',
                data: {
                    labels: ['Aprobadas', 'En Revisión', 'Otras'],
                    datasets: [{
                        data: [aprobadas, revision, otras],
                        backgroundColor: [
                            chartColors.success,
                            chartColors.warning,
                            chartColors.danger,
                        ],
                        borderWidth: 2,
                        borderColor: isDarkMode ? 'rgb(24, 24, 27)' : 'rgb(255, 255, 255)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: textColor,
                                font: {
                                    family: 'Inter, system-ui, sans-serif'
                                }
                            }
                        }
                    }
                }
            });
        }

        // Gráfico de Presupuesto por Fuente (Bar)
        const ctxPorFuente = document.getElementById('chartPresupuestoPorFuente');
        if (ctxPorFuente && estadisticasPorFuente.length > 0) {
            charts.porFuente = new Chart(ctxPorFuente, {
                type: 'bar',
                data: {
                    labels: estadisticasPorFuente.map(f => f.fuente),
                    datasets: [
                        {
                            label: 'Asignado',
                            data: estadisticasPorFuente.map(f => parseFloat(f.asignado)),
                            backgroundColor: chartColors.primary,
                        },
                        {
                            label: 'Planificado',
                            data: estadisticasPorFuente.map(f => parseFloat(f.planificado)),
                            backgroundColor: chartColors.info,
                        },
                        {
                            label: 'Ejecutado',
                            data: estadisticasPorFuente.map(f => parseFloat(f.ejecutado)),
                            backgroundColor: chartColors.success,
                        }
                    ]
                },
                options: defaultOptions
            });
        }

        // Gráfico de Presupuesto por Grupo de Gasto (Horizontal Bar)
        const ctxPorGrupoGasto = document.getElementById('chartPresupuestoPorGrupoGasto');
        if (ctxPorGrupoGasto && estadisticasPorGrupoGasto.length > 0) {
            console.log('Datos de grupos de gasto:', estadisticasPorGrupoGasto);
            
            charts.porGrupoGasto = new Chart(ctxPorGrupoGasto, {
                type: 'bar',
                data: {
                    labels: estadisticasPorGrupoGasto.map(g => g.grupo),
                    datasets: [
                        {
                            label: 'Planificado',
                            data: estadisticasPorGrupoGasto.map(g => parseFloat(g.planificado)),
                            backgroundColor: chartColors.info,
                        },
                        {
                            label: 'Ejecutado',
                            data: estadisticasPorGrupoGasto.map(g => parseFloat(g.ejecutado)),
                            backgroundColor: chartColors.success,
                        }
                    ]
                },
                options: {
                    ...defaultOptions,
                    indexAxis: 'y',
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                color: textColor,
                                callback: function(value) {
                                    return 'L ' + value.toLocaleString('es-HN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                }
                            },
                            grid: {
                                color: gridColor
                            }
                        },
                        y: {
                            ticks: {
                                color: textColor
                            },
                            grid: {
                                color: gridColor
                            }
                        }
                    }
                }
            });
        }

        // Gráfico de Presupuesto Mensual (Line)
        const ctxMensual = document.getElementById('chartPresupuestoMensual');
        if (ctxMensual && estadisticasMensuales.length > 0) {
            charts.mensual = new Chart(ctxMensual, {
                type: 'line',
                data: {
                    labels: estadisticasMensuales.map(m => m.mes),
                    datasets: [
                        {
                            label: 'Planificado',
                            data: estadisticasMensuales.map(m => parseFloat(m.planificado)),
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.08)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            pointBackgroundColor: 'rgb(59, 130, 246)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointHoverBackgroundColor: 'rgb(59, 130, 246)',
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 3
                        },
                        {
                            label: 'Ejecutado',
                            data: estadisticasMensuales.map(m => parseFloat(m.ejecutado)),
                            borderColor: 'rgb(34, 197, 94)',
                            backgroundColor: 'rgba(34, 197, 94, 0.08)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            pointBackgroundColor: 'rgb(34, 197, 94)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointHoverBackgroundColor: 'rgb(34, 197, 94)',
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 3
                        }
                    ]
                },
                options: {
                    ...defaultOptions,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        ...defaultOptions.plugins,
                        tooltip: {
                            backgroundColor: isDarkMode ? 'rgba(24, 24, 27, 0.95)' : 'rgba(255, 255, 255, 0.95)',
                            titleColor: textColor,
                            bodyColor: textColor,
                            borderColor: isDarkMode ? 'rgba(82, 82, 91, 0.5)' : 'rgba(228, 228, 231, 0.5)',
                            borderWidth: 1,
                            padding: 12,
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': L ' + 
                                           context.parsed.y.toLocaleString('es-HN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                }
                            }
                        }
                    }
                }
            });
        }
    }
    
    // Inicializar charts cuando el documento esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeCharts);
    } else {
        initializeCharts();
    }
    </script>

    @script
    <script>
        // Inicializar charts cuando el componente Livewire se monta
        setTimeout(() => {
            if (typeof updateChartsWithLivewireData === 'function') {
                updateChartsWithLivewireData();
            }
        }, 100);
        
        // Escuchar cambios en los filtros y reinicializar charts con datos actualizados
        $wire.on('charts-update', () => {
            setTimeout(() => {
                if (typeof updateChartsWithLivewireData === 'function') {
                    updateChartsWithLivewireData();
                }
            }, 150);
        });
    </script>
    @endscript

    <style>
        /* Evitar parpadeo durante las actualizaciones de Livewire */
        [wire\:loading] {
            opacity: 0.6;
            pointer-events: none;
        }
    </style>
</div>
