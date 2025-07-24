<x-guest-layout>
    <!-- Header -->
    <header
        class="relative z-10 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-sm border-b border-gray-200 dark:border-zinc-700">
        <div class="max-w-7xl mx-auto px-6 py-4">
            @if (Route::has('login'))
                <nav class="flex items-center justify-between">
                    <div class="flex items-center">
                        <img src="{{ asset('Logo/poav2_grey.png') }}" alt="POA Logo" class="h-10 w-auto dark:hidden" />
                        <img src="{{ asset('Logo/poav2.webp') }}" alt="POA Logo" class="h-10 w-auto hidden dark:block" />
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
    <div class="min-h-screen md:flex md:space-x-6 p-6 bg-white dark:bg-zinc-900 " x-data="{ tab: 'inicio' }">
        <ul
            class="flex-column space-y space-y-4 text-sm font-medium text-gray-500 dark:text-gray-400 md:me-4 mb-4 md:mb-0">
            <li>
                <a href="#" @click.prevent="tab = 'inicio'"
                    :class="tab === 'inicio'
                        ?
                        'inline-flex items-center gap-3 px-4 py-3 rounded-lg w-full text-white bg-indigo-600 dark:bg-indigo-600' :
                        'inline-flex items-center gap-3 px-4 py-3 rounded-lg w-full bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white'">
                    <svg :class="tab === 'inicio' ? 'w-6 h-6 text-white' : 'w-6 h-6 text-gray-500 dark:text-gray-400'"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5" />
                    </svg>
                    Inicio
                </a>
            </li>
            <li>
                <a href="#" @click.prevent="tab = 'planificacion'"
                    :class="tab === 'planificacion'
                        ?
                        'inline-flex items-center gap-3 px-4 py-3 rounded-lg w-full text-white bg-indigo-600 dark:bg-indigo-600' :
                        'inline-flex items-center gap-3 px-4 py-3 rounded-lg w-full bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white'">
                    <svg :class="tab === 'planificacion' ? 'w-6 h-6 text-white' : 'w-6 h-6 text-gray-500 dark:text-gray-400'"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 3v4a1 1 0 0 1-1 1H5m4 10v-2m3 2v-6m3 6v-3m4-11v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Z" />
                    </svg>
                    Planificación
                </a>
            </li>
            <li>
                <a href="#" @click.prevent="tab = 'consola'"
                    :class="tab === 'consola'
                        ?
                        'inline-flex items-center gap-3 px-4 py-3 rounded-lg w-full text-white bg-indigo-600 dark:bg-indigo-600' :
                        'inline-flex items-center gap-3 px-4 py-3 rounded-lg w-full bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white'">
                    <svg :class="tab === 'consola' ? 'w-6 h-6 text-white' : 'w-6 h-6 text-gray-500 dark:text-gray-400'"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 15v4m6-6v6m6-4v4m6-6v6M3 11l6-5 6 5 5.5-5.5" />
                    </svg>
                    Consola
                </a>
            </li>
        </ul>
        <div class="p-6 bg-gray-50 dark:bg-gray-800 text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-lg w-full">
            <div x-show="tab === 'inicio'">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Inicio</h3>
                <p class="text-justify">Bienvenido a la sección de inicio. Aquí puedes ver información general y accesos rápidos.</p>
            </div>
            <div x-show="tab === 'planificacion'">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Planificación</h3>
                <p class="text-justify">En la vista Mis Planificaciones, el usuario puede consultar y gestionar los POA (Planes Operativos Anuales) que tiene asignados por departamento. Aquí puede filtrar los POA por departamento, buscar un POA específico, y crear nuevos POA según las necesidades de su área. Además, se muestra un resumen por año, donde el usuario puede ver la cantidad total de proyectos, el presupuesto asignado y el avance porcentual de cada POA. Desde esta sección también puede acceder a la gestión detallada de cada POA, facilitando el seguimiento y la administración de los proyectos y recursos institucionales.
            </div>
            <div x-show="tab === 'consola'">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Planificación Estratégica Institucional
                </h3>
                <p class="text-justify">En el módulo de Consola, puede gestionar los Planes Estratégicos Institucionales (PEIs) de la organización. Aquí es posible crear nuevos PEIs, buscar y filtrar los existentes, consultar información relevante como el nombre, institución asociada, período y dimensiones estratégicas, así como acceder a las acciones disponibles para cada PEI. Este espacio está diseñado para facilitar la administración centralizada de la estrategia institucional, permitiendo al usuario mantener actualizados los planes, realizar ajustes y asegurar la alineación de los objetivos estratégicos con la operación institucional.
                </div>
            <br>
           

        </div>
    </div>
</x-guest-layout>
