<x-app-layout>
    <div>
        <div class="max-w-7xl mx-auto rounded-lg mt-8 sm:mt-6 lg:mt-4 mb-6">
            <!-- Tarjetas de Estadísticas -->
            <div class="grid grid-cols-1 gap-5 mb-6 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Total Actividades -->
                <div class="p-4 transition-shadow bg-white rounded-lg shadow-sm hover:shadow-lg dark:bg-zinc-900">
                    <div class="flex items-start">
                        <div class="p-3 text-indigo-500 dark:text-indigo-300 bg-indigo-100 dark:bg-indigo-900 rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
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
                        <div class="p-3 text-red-500 bg-red-100 dark:bg-red-900 dark:text-red-300 rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
                        <div class="p-3 text-green-500 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
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
                        <div class="p-3 text-purple-500 bg-purple-100 rounded-full dark:bg-purple-900 dark:text-purple-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                </path>
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
            <div class="relative p-6 mb-6 bg-white rounded-lg shadow-sm dark:bg-zinc-900 h-[30rem]">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        Actividad Diaria
                        <span class="text-sm text-gray-500">(Últimos 30 días)</span>
                    </h3>
                </div>
                <div id="dailyActivityChart" class="w-full h-max bg-gray-50 dark:bg-zinc-800 rounded-lg"></div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 h-[25rem]">
                <!-- Actividad por módulo -->
                <div class="relative p-6 bg-white rounded-lg shadow-sm dark:bg-zinc-900">
                    <h3 class="mb-4 text-lg font-medium text-gray-900 dark:text-white">Actividad por Módulo</h3>
                    <div id="moduleActivityChart" class="w-full h-max bg-gray-50 dark:bg-zinc-800 rounded-lg"></div>
                </div>

                <!-- Usuarios más activos -->
                <div class="relative p-6 bg-white rounded-lg shadow-sm dark:bg-zinc-900">
                    <h3 class="mb-4 text-lg font-medium text-gray-900 dark:text-white">Usuarios más Activos</h3>
                    <div id="activeUsersChart" class="w-full h-max bg-gray-50 dark:bg-zinc-800 rounded-lg"></div>
                </div>
            </div>

            <!-- Gráfico de errores -->
            <div class="relative p-6 mt-6 bg-white rounded-lg shadow-sm dark:bg-zinc-900 h-[30rem]">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        Registro de Errores
                        <span class="text-sm text-gray-500">(Últimos 30 días)</span>
                    </h3>
                </div>
                <div id="errorChart" class="w-full h-max bg-gray-50 dark:bg-zinc-800 rounded-lg"></div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Verificación inicial del modo oscuro
                let isDarkMode = localStorage.getItem('color-theme') === 'dark' ||
                    (!('color-theme' in localStorage) &&
                        window.matchMedia('(prefers-color-scheme: dark)').matches);

                // Referencias a los gráficos para actualizarlos cuando cambie el modo
                let dailyActivityChart, moduleActivityChart, activeUsersChart, errorChart;

                // Función para crear o actualizar los gráficos
                function setupCharts(darkMode) {
                    // Configuración de tema para modo oscuro/claro
                    const chartTheme = {
                        mode: darkMode ? 'dark' : 'light',
                        palette: 'palette1',
                        monochrome: {
                            enabled: false
                        },
                        // Configuración específica para modo oscuro
                        dark: {
                            background: 'transparent',
                            foreColor: '#e2e8f0', // Color de texto más claro
                            grid: {
                                borderColor: '#334155',
                                strokeDashArray: 4
                            }
                        }
                    };

                    // Colores para gráficos en modo oscuro
                    const darkColors = [
                        '#312e81', // Indigo más oscuro
                        '#065f46', // Verde oscuro
                        '#5b21b6', // Púrpura profundo
                        '#b45309', // Ámbar oscuro
                        '#9d174d', // Rosa oscuro
                        '#0e7490', // Cian oscuro
                        '#4d7c0f', // Lima oscuro
                        '#c2410c'  // Naranja oscuro
                    ];

                    // Configuraciones comunes
                    const textColor = darkMode ? '#cbd5e1' : undefined;
                    const dataLabelsColor = darkMode ? '#f8fafc' : undefined;
                    const gridBorderColor = darkMode ? '#334155' : '#f1f1f1';
                    const tooltipTheme = darkMode ? 'dark' : 'light';

                    // Destruir gráficos existentes si los hay
                    if (dailyActivityChart) dailyActivityChart.destroy();
                    if (moduleActivityChart) moduleActivityChart.destroy();
                    if (activeUsersChart) activeUsersChart.destroy();
                    if (errorChart) errorChart.destroy();

                    // Gráfico de actividad diaria
                    dailyActivityChart = new ApexCharts(document.getElementById('dailyActivityChart'), {
                        series: [{
                            name: 'Actividades',
                            data: @json($dailyActivity)
                        }],
                        chart: {
                            type: 'area',
                            height: '90%',
                            fontFamily: 'Inter, sans-serif',
                            toolbar: {
                                show: false
                            },
                            zoom: {
                                enabled: false
                            },
                            background: 'transparent'
                        },
                        colors: darkMode ? darkColors : undefined,
                        stroke: {
                            curve: 'smooth',
                            width: 5
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: darkMode ? 0.5 : 0.7,
                                opacityTo: 0.2,
                                stops: [0, 90, 100]
                            }
                        },
                        dataLabels: {
                            enabled: false,
                            formatter: function (val) {
                                return Math.round(val);
                            }
                        },
                        grid: {
                            borderColor: gridBorderColor,
                            strokeDashArray: 4,
                            xaxis: {
                                lines: {
                                    show: true
                                }
                            }
                        },
                        xaxis: {
                            type: 'datetime',
                            labels: {
                                style: {
                                    colors: textColor
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                formatter: function (val) {
                                    return Math.round(val);
                                },
                                style: {
                                    colors: textColor
                                }
                            }
                        },
                        theme: chartTheme,
                        tooltip: {
                            x: {
                                format: 'dd MMM yyyy'
                            },
                            theme: tooltipTheme
                        }
                    });
                    dailyActivityChart.render();

                    // Gráfico de módulos
                    moduleActivityChart = new ApexCharts(document.getElementById('moduleActivityChart'), {
                        series: @json($moduleSeries),
                        chart: {
                            type: 'donut',
                            height: '90%',
                            background: 'transparent'
                        },
                        colors: darkMode ? darkColors : undefined,
                        labels: @json($moduleLabels),
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '70%',
                                    labels: {
                                        show: true,
                                        total: {
                                            showAlways: false,
                                            show: true,
                                            label: 'Total',
                                            color: darkMode ? '#e2e8f0' : undefined
                                        }
                                    }
                                }
                            }
                        },
                        legend: {
                            position: 'bottom',
                            offsetY: 0,
                            labels: {
                                colors: textColor
                            }
                        },
                        dataLabels: {
                            formatter: function (val) {
                                return Math.round(val) + '%';
                            },
                            style: {
                                colors: [dataLabelsColor]
                            }
                        },
                        theme: chartTheme,
                        tooltip: {
                            theme: tooltipTheme
                        }
                    });
                    moduleActivityChart.render();

                    // Gráfico de usuarios activos
                    activeUsersChart = new ApexCharts(document.getElementById('activeUsersChart'), {
                        series: [{
                            name: 'Actividades',
                            data: @json($userSeries)
                        }],
                        chart: {
                            type: 'bar',
                            height: '90%',
                            toolbar: {
                                show: false
                            },
                            background: 'transparent'
                        },
                        colors: darkMode ? darkColors : undefined,
                        plotOptions: {
                            bar: {
                                horizontal: true,
                                borderRadius: 4,
                                distributed: true
                            }
                        },
                        dataLabels: {
                            formatter: function (val) {
                                return Math.round(val);
                            },
                            style: {
                                fontSize: '12px',
                                colors: [dataLabelsColor]
                            }
                        },
                        xaxis: {
                            categories: @json($userLabels),
                            labels: {
                                style: {
                                    colors: textColor
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    colors: textColor
                                }
                            }
                        },
                        theme: chartTheme,
                        tooltip: {
                            theme: tooltipTheme
                        }
                    });
                    activeUsersChart.render();

                    // Gráfico de errores
                    errorChart = new ApexCharts(document.getElementById('errorChart'), {
                        series: [{
                            name: 'Errores',
                            data: @json($dailyErrors)
                        }],
                        chart: {
                            type: 'bar',
                            height: '90%',
                            toolbar: {
                                show: false
                            },
                            background: 'transparent'
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 4,
                                columnWidth: '50%',
                            }
                        },
                        dataLabels: {
                            enabled: false,
                            style: {
                                colors: [dataLabelsColor]
                            }
                        },
                        colors: darkMode ? ['#b91c1c'] : ['#ef4444'],
                        xaxis: {
                            type: 'datetime',
                            labels: {
                                style: {
                                    colors: textColor
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                formatter: function (val) {
                                    return Math.round(val);
                                },
                                style: {
                                    colors: textColor
                                }
                            }
                        },
                        grid: {
                            borderColor: gridBorderColor,
                            strokeDashArray: 4
                        },
                        theme: chartTheme,
                        tooltip: {
                            theme: tooltipTheme
                        }
                    });
                    errorChart.render();
                }

                // Crear gráficos inicialmente
                setupCharts(isDarkMode);

                // Función para detectar cambios en el modo oscuro
                function checkDarkMode() {
                    const newDarkMode = localStorage.getItem('color-theme') === 'dark' ||
                        (!('color-theme' in localStorage) &&
                            window.matchMedia('(prefers-color-scheme: dark)').matches);

                    // Si cambió el estado, actualizar los gráficos
                    if (newDarkMode !== isDarkMode) {
                        isDarkMode = newDarkMode;
                        setupCharts(isDarkMode);
                    }
                }

                // Observador para detectar cambios en la clase 'dark' del documento
                const observer = new MutationObserver((mutations) => {
                    mutations.forEach((mutation) => {
                        if (mutation.attributeName === 'class') {
                            checkDarkMode();
                        }
                    });
                });

                // Iniciar observación
                observer.observe(document.documentElement, { attributes: true });

                // Escuchar cambios en las preferencias del sistema
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', checkDarkMode);

                // Escuchar eventos personalizados (si tienes un toggle)
                document.addEventListener('dark-mode-toggled', checkDarkMode);

                // Si tienes botones o elementos que cambien el tema, puedes agregar listeners aquí
                document.querySelectorAll('[data-toggle-theme]').forEach(button => {
                    button.addEventListener('click', checkDarkMode);
                });
            });
        </script>
    @endpush
</x-app-layout>