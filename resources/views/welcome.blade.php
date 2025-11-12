<x-guest-layout>

    <style>
        .professional-bg {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }

        .dark .professional-bg {
            background: linear-gradient(135deg, #18181b 0%, #27272a 100%);
        }

        .card-professional {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .dark .card-professional {
            background: rgba(39, 39, 42, 0.95);
            border: 1px solid rgba(63, 63, 70, 0.8);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.25), 0 2px 4px -1px rgba(0, 0, 0, 0.15);
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #4c1dd8 100%);
            box-shadow: 0 4px 14px 0 rgba(59, 130, 246, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #6a25eb 0%, #471eaf 100%);
            box-shadow: 0 8px 25px 0 rgba(59, 130, 246, 0.4);
        }
    </style>
    </head>

    <body class="antialiased professional-bg min-h-screen font-sans text-gray-900 dark:text-zinc-100">
        <div class="relative min-h-screen">
            <!-- Header -->
            <header
                class="relative z-10 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-sm border-b border-gray-200 dark:border-zinc-700">
                <div class="max-w-7xl mx-auto px-6 py-4">
                    @if (Route::has('login'))
                        <nav class="flex items-center justify-between">
                            <div class="flex items-center">
                                <img src="{{ asset('Logo/poav2_grey.png') }}" alt="POA Logo"
                                    class="h-10 w-auto dark:hidden" />
                                <img src="{{ asset('Logo/poav2.webp') }}" alt="POA Logo"
                                    class="h-10 w-auto hidden dark:block" />
                                <span class="ml-3 text-xl font-semibold text-gray-900 dark:text-zinc-100"></span>
                            </div>
                            <div class="flex items-center gap-4">
                                @auth
                                    <a href="{{ url('/dashboard') }}"
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                                        Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}"
                                        class="text-gray-700 dark:text-zinc-300 hover:text-gray-900 dark:hover:text-zinc-100 font-medium transition-colors duration-200">
                                        Iniciar Sesión
                                    </a>

                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}"
                                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                                            Registrarse
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        </nav>
                    @endif
                </div>
            </header>

            <!-- Main Content -->
            <main class="relative z-10 py-20">
                <div class="max-w-7xl mx-auto px-6">
                    <!-- Hero Section -->
                    <div class="text-center mb-20">
                        <div class="mb-8">
                            <img src="{{ asset('Logo/poav2_grey.png') }}" alt="POA Logo"
                                class="h-24 w-auto mx-auto dark:hidden" />
                            <img src="{{ asset('Logo/poav2.webp') }}" alt="POA Logo"
                                class="h-24 w-auto mx-auto hidden dark:block" />
                        </div>

                        <h1 class="text-4xl md:text-6xl font-bold text-gray-900 dark:text-zinc-100 mb-6 leading-tight">
                            Sistema de Planificación
                            <span class="text-indigo-600 dark:text-indigo-400">Operativa Anual</span>
                        </h1>

                        <p class="text-xl text-gray-600 dark:text-zinc-300 max-w-3xl mx-auto mb-12 leading-relaxed">
                            Plataforma integral para la gestión estratégica organizacional. Planificación eficiente,
                            control presupuestario y seguimiento de objetivos en tiempo real.
                        </p>

                        <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                    class="btn-primary text-white font-semibold py-4 px-8 rounded-lg transition-all duration-300 transform hover:scale-105">
                                    Acceder al Sistema
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="btn-primary text-white font-semibold py-4 px-8 rounded-lg transition-all duration-300 transform hover:scale-105">
                                    Comenzar ahora
                                </a>
                            @endauth

                            <a href="{{ route('login') }}"
                                class="border border-gray-300 dark:border-zinc-600 text-gray-700 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-800 font-semibold py-4 px-8 rounded-lg transition-all duration-300">
                                Conocer más
                            </a>
                        </div>
                    </div>

                    <!-- Features Section -->
                    <div id="features" class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20">
                        <div class="card-professional rounded-xl p-8 hover:shadow-lg transition-all duration-300">
                            <div
                                class="w-16 h-16 bg-indigo-100 dark:bg-indigo-900 rounded-xl flex items-center justify-center mb-6">
                                <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-zinc-100 mb-4">Planificación
                                Estratégica</h3>
                            <p class="text-gray-600 dark:text-zinc-400 leading-relaxed">Defina objetivos claros y
                                estructure planes operativos con metodologías probadas y herramientas especializadas.
                            </p>
                        </div>

                        <div class="card-professional rounded-xl p-8 hover:shadow-lg transition-all duration-300">
                            <div
                                class="w-16 h-16 bg-green-100 dark:bg-green-900 rounded-xl flex items-center justify-center mb-6">
                                <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-zinc-100 mb-4">Control
                                Presupuestario</h3>
                            <p class="text-gray-600 dark:text-zinc-400 leading-relaxed">Gestione recursos financieros
                                con precisión. Asignación por departamentos y seguimiento detallado de ejecución.</p>
                        </div>

                        <div class="card-professional rounded-xl p-8 hover:shadow-lg transition-all duration-300">
                            <div
                                class="w-16 h-16 bg-purple-100 dark:bg-purple-900 rounded-xl flex items-center justify-center mb-6">
                                <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-zinc-100 mb-4">Reportes y Análisis
                            </h3>
                            <p class="text-gray-600 dark:text-zinc-400 leading-relaxed">Monitoree el progreso con
                                dashboards ejecutivos y reportes detallados para la toma de decisiones estratégicas.</p>
                        </div>
                    </div>

                    <!-- Statistics Section -->
                    <div class="card-professional rounded-xl p-8 text-center">
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-zinc-100 mb-8">Optimice su gestión
                            organizacional</h2>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                            <div>
                                <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 mb-2">100%</div>
                                <div class="text-gray-600 dark:text-zinc-400">Control Presupuestario</div>
                            </div>
                            <div>
                                <div class="text-3xl font-bold text-green-600 dark:text-green-400 mb-2">360°</div>
                                <div class="text-gray-600 dark:text-zinc-400">Visibilidad de Proyectos</div>
                            </div>
                            <div>
                                <div class="text-3xl font-bold text-purple-600 dark:text-purple-400 mb-2">24/7</div>
                                <div class="text-gray-600 dark:text-zinc-400">Monitoreo en Tiempo Real</div>
                            </div>
                            <div>
                                <div class="text-3xl font-bold text-orange-600 dark:text-orange-400 mb-2">∞</div>
                                <div class="text-gray-600 dark:text-zinc-400">Escalabilidad</div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="relative z-10 bg-gray-50 dark:bg-zinc-900 border-t border-gray-200 dark:border-zinc-700">
                <div class="max-w-7xl mx-auto px-6 py-8">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div class="flex items-center mb-4 md:mb-0">
                            <img src="{{ asset('Logo/poav2_grey.png') }}" alt="POA Logo"
                                class="h-8 w-auto mr-3 dark:hidden" />
                            <img src="{{ asset('Logo/poav2.webp') }}" alt="POA Logo"
                                class="h-8 w-auto mr-3 hidden dark:block" />
                            <span class="text-gray-600 dark:text-zinc-400">&copy; {{ date('Y') }} Sistema POA.
                                Todos los derechos reservados.</span>
                        </div>
                        <div class="text-sm text-gray-500 dark:text-zinc-500">
                            Planificación Operativa Anual
                        </div>
                    </div>
                </div>
            </footer>
        </div>
</x-guest-layout>
