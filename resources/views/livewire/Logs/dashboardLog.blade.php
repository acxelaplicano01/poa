<x-app-layout>
<div>
    <div class="max-w-7xl mx-auto rounded-lg mt-8 sm:mt-6 lg:mt-4 mb-6">
            <!-- Tarjetas de Estadísticas -->
            <div class="grid grid-cols-1 gap-5 mb-6 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Total Actividades -->
                <div class="p-4 transition-shadow bg-white rounded-lg shadow-sm hover:shadow-lg dark:bg-zinc-900">
                    <div class="flex items-start">
                        <div class="p-3 bg-indigo-600 rounded-full">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Total Actividades</h2>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-200">{{ $totalActivities }}</p>
                            <p class="text-sm text-gray-500">Últimos 30 días</p>
                        </div>
                    </div>
                </div>

                <!-- Total Errores -->
                <div class="p-4 transition-shadow bg-white rounded-lg shadow-sm hover:shadow-lg dark:bg-zinc-900">
                    <div class="flex items-start">
                        <div class="p-3 bg-red-600 rounded-full">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Total Errores</h2>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-200">{{ $totalErrors }}</p>
                            <p class="text-sm text-gray-500">Últimos 30 días</p>
                        </div>
                    </div>
                </div>

                <!-- Usuarios Activos -->
                <div class="p-4 transition-shadow bg-white rounded-lg shadow-sm hover:shadow-lg dark:bg-zinc-900">
                    <div class="flex items-start">
                        <div class="p-3 bg-green-600 rounded-full">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Usuarios Activos</h2>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-200">{{ $activeUsersCount }}</p>
                            <p class="text-sm text-gray-500">En el sistema</p>
                        </div>
                    </div>
                </div>

                <!-- Módulos Activos -->
                <div class="p-4 transition-shadow bg-white rounded-lg shadow-sm hover:shadow-lg dark:bg-zinc-900">
                    <div class="flex items-start">
                        <div class="p-3 bg-purple-600 rounded-full">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Módulos Activos</h2>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-200">{{ $moduleCount }}</p>
                            <p class="text-sm text-gray-500">En uso</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfico de actividad diaria -->
            <div class="relative p-6 mb-6 overflow-hidden bg-white rounded-lg shadow-sm dark:bg-zinc-900">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        Actividad Diaria
                        <span class="text-sm text-gray-500">(Últimos 30 días)</span>
                    </h3>
                </div>
                <div id="dailyActivityChart" class="w-full h-72"></div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Actividad por módulo -->
                <div class="relative p-6 overflow-hidden bg-white rounded-lg shadow-sm dark:bg-zinc-900">
                    <h3 class="mb-4 text-lg font-medium text-gray-900 dark:text-white">Actividad por Módulo</h3>
                    <div id="moduleActivityChart" class="w-full h-64"></div>
                </div>
                
                <!-- Usuarios más activos -->
                <div class="relative p-6 overflow-hidden bg-white rounded-lg shadow-sm dark:bg-zinc-900">
                    <h3 class="mb-4 text-lg font-medium text-gray-900 dark:text-white">Usuarios más Activos</h3>
                    <div id="activeUsersChart" class="w-full h-64"></div>
                </div>
            </div>

            <!-- Gráfico de errores -->
            <div class="relative overflow-hidden p-6 mt-6 bg-white rounded-lg shadow-sm dark:bg-zinc-900">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        Registro de Errores
                        <span class="text-sm text-gray-500">(Últimos 30 días)</span>
                    </h3>
                </div>
                <div id="errorChart" class="w-full h-72"></div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Configuración de tema para modo oscuro/claro
                const chartTheme = {
                    mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
                    palette: 'palette1',
                    monochrome: {
                        enabled: false
                    },
                    // Configuración específica para modo oscuro
                    dark: {
                        background: 'transparent',
                        foreColor: '#fff',
                        grid: {
                            borderColor: '#334155', // Color más oscuro para la cuadrícula
                            strokeDashArray: 4
                        }
                    }
                };

                // Gráfico de actividad diaria
                new ApexCharts(document.getElementById('dailyActivityChart'), {
                    series: [{
                        name: 'Actividades',
                        data: @json($dailyActivity)
                    }],
                    chart: {
                        type: 'area',
                        height: '100%',
                        fontFamily: 'Inter, sans-serif',
                        toolbar: {
                            show: false
                        },
                        zoom: {
                            enabled: false
                        }
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 3
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.7,
                            opacityTo: 0.2,
                            stops: [0, 90, 100]
                        }
                    },
                    dataLabels: {
                        enabled: false,
                        formatter: function(val) {
                            return Math.round(val);
                        }
                    },
                    grid: {
                        borderColor: '#f1f1f1',
                        strokeDashArray: 4,
                        xaxis: {
                            lines: {
                                show: true
                            }
                        }
                    },
                    xaxis: {
                        type: 'datetime'
                    },
                    yaxis: {
                        labels: {
                            formatter: function(val) {
                                return Math.round(val);
                            }
                        }
                    },
                    dataLabels: {
                        formatter: function (val) {
                            return Math.round(val);
                        }
                    },
                    theme: chartTheme,
                    tooltip: {
                        x: {
                            format: 'dd MMM yyyy'
                        }
                    }
                }).render();

                // Gráfico de módulos
                new ApexCharts(document.getElementById('moduleActivityChart'), {
                    series: @json($moduleSeries),
                    chart: {
                        type: 'donut',
                        height: '100%'
                    },
                    labels: @json($moduleLabels),
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '70%'
                            }
                        }
                    },
                    legend: {
                        position: 'bottom',
                        offsetY: 0
                    },
                    dataLabels: {
                        formatter: function(val) {
                            return Math.round(val);
                        }
                    },
                    theme: chartTheme
                }).render();

                // Gráfico de usuarios activos
                new ApexCharts(document.getElementById('activeUsersChart'), {
                    series: [{
                        name: 'Actividades',
                        data: @json($userSeries)
                    }],
                    chart: {
                        type: 'bar',
                        height: '100%',
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            borderRadius: 4,
                            distributed: true
                        }
                    },
                    dataLabels: {
                        formatter: function(val) {
                            return Math.round(val);
                        },
                        style: {
                            fontSize: '12px'
                        }
                    },
                    xaxis: {
                        categories: @json($userLabels)
                    },
                    theme: chartTheme
                }).render();

                // Gráfico de errores
                new ApexCharts(document.getElementById('errorChart'), {
                    series: [{
                        name: 'Errores',
                        data: @json($dailyErrors)
                    }],
                    chart: {
                        type: 'bar',
                        height: '100%',
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            columnWidth: '50%',
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    colors: ['#ef4444'],
                    xaxis: {
                        type: 'datetime'
                    },
                     yaxis: {
                        labels: {
                            formatter: function(val) {
                                return Math.round(val);
                            }
                        }
                    },
                    dataLabels: {
                        formatter: function(val) {
                            return Math.round(val);
                        }
                    },
                    theme: chartTheme
                }).render();
            });
        </script>
    @endpush
</x-app-layout>