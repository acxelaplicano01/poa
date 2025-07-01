<div>
    <div class=" mx-auto rounded-lg mt-8 sm:mt-6 lg:mt-4 mb-6">
        <!-- Encabezado con búsqueda -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">
                    Mis Planificaciones
                </h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    Esta sección muestra los POAs que usted tiene asignados por departamento
                </p>
            </div>
            
            <!-- Buscador de POA -->
            <div class="mt-4 md:mt-0 flex-shrink-0">
                <div class="relative">
                    <x-input type="text" wire:model.debounce.300ms="search" 
                        placeholder="Buscar POA" 
                        class="w-full md:w-64 pl-10 pr-4 py-2 shadow-sm" />
                    <div class="absolute left-3 top-2.5">
                        <svg class="h-5 w-5 text-zinc-400 dark:text-zinc-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Selector de Departamento -->
        <div class="mb-6 bg-white text-zinc-700 dark:text-zinc-300 dark:bg-zinc-900 p-4 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
            <h2 class="text-sm font-medium mb-3">Filtrar por departamento</h2>
            <div class="flex flex-wrap items-center gap-2">
                <div class="w-full md:w-64">
                    <select wire:model="departamento" class="block w-full bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Todos los departamentos</option>
                        <option value="COORDINACION REGIONAL DE INVESTIGACION">COORDINACIÓN REGIONAL DE INVESTIGACIÓN</option>
                        <option value="DEPARTAMENTO DE PLANIFICACION">DEPARTAMENTO DE PLANIFICACIÓN</option>
                        <option value="RECURSOS HUMANOS">RECURSOS HUMANOS</option>
                    </select>
                </div>
                <div class="flex-shrink-0">
                    <x-button type="button" wire:click="crearPoa">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Crear Nuevo POA
                    </x-button>
                </div>
            </div>
        </div>
        
        <!-- Planificaciones POA - Vista de tarjetas por año -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">POAs por Año</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-4 sm:gap-6">
                <!-- Tarjeta de POA 2023 -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg overflow-hidden text-white hover:shadow-xl transition-all duration-200">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <h3 class="text-3xl font-extrabold">2023</h3>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                Finalizado
                            </span>
                        </div>
                        
                        <div class="mt-4 flex flex-col space-y-2 text-sm text-blue-50">
                            <div class="flex items-center justify-between">
                                <span>Total proyectos:</span>
                                <span class="font-semibold">162</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Presupuesto:</span>
                                <span class="font-semibold">$45,253.00</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Avance:</span>
                                <span class="font-semibold">100%</span>
                            </div>
                        </div>
                        
                        <div class="mt-3 w-full bg-blue-200 bg-opacity-30 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full" style="width: 100%"></div>
                        </div>
                        
                        <div class="mt-5">
                            <button wire:click="gestionarPoa(2023)" class="w-full flex items-center justify-center px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-zinc-900 font-medium rounded-md transition-colors">
                                <span>Gestionar</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg overflow-hidden text-white hover:shadow-xl transition-all duration-200">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <h3 class="text-3xl font-extrabold">2023</h3>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                Finalizado
                            </span>
                        </div>
                        
                        <div class="mt-4 flex flex-col space-y-2 text-sm text-blue-50">
                            <div class="flex items-center justify-between">
                                <span>Total proyectos:</span>
                                <span class="font-semibold">162</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Presupuesto:</span>
                                <span class="font-semibold">$45,253.00</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Avance:</span>
                                <span class="font-semibold">100%</span>
                            </div>
                        </div>
                        
                        <div class="mt-3 w-full bg-blue-200 bg-opacity-30 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full" style="width: 100%"></div>
                        </div>
                        
                        <div class="mt-5">
                            <button wire:click="gestionarPoa(2023)" class="w-full flex items-center justify-center px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-zinc-900 font-medium rounded-md transition-colors">
                                <span>Gestionar</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg overflow-hidden text-white hover:shadow-xl transition-all duration-200">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <h3 class="text-3xl font-extrabold">2023</h3>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                Finalizado
                            </span>
                        </div>
                        
                        <div class="mt-4 flex flex-col space-y-2 text-sm text-blue-50">
                            <div class="flex items-center justify-between">
                                <span>Total proyectos:</span>
                                <span class="font-semibold">162</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Presupuesto:</span>
                                <span class="font-semibold">$45,253.00</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Avance:</span>
                                <span class="font-semibold">100%</span>
                            </div>
                        </div>
                        
                        <div class="mt-3 w-full bg-blue-200 bg-opacity-30 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full" style="width: 100%"></div>
                        </div>
                        
                        <div class="mt-5">
                            <button wire:click="gestionarPoa(2023)" class="w-full flex items-center justify-center px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-zinc-900 font-medium rounded-md transition-colors">
                                <span>Gestionar</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Tarjeta de POA 2024 -->
                <div class="bg-gradient-to-br from-blue-600 to-indigo-600 rounded-lg shadow-lg overflow-hidden text-white hover:shadow-xl transition-all duration-200">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <h3 class="text-3xl font-extrabold">2024</h3>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Activo
                            </span>
                        </div>
                        
                        <div class="mt-4 flex flex-col space-y-2 text-sm text-blue-50">
                            <div class="flex items-center justify-between">
                                <span>Total proyectos:</span>
                                <span class="font-semibold">205</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Presupuesto:</span>
                                <span class="font-semibold">$75,000.00</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Avance:</span>
                                <span class="font-semibold">80%</span>
                            </div>
                        </div>
                        
                        <div class="mt-3 w-full bg-blue-200 bg-opacity-30 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full" style="width: 80%"></div>
                        </div>
                        
                        <div class="mt-5">
                            <button wire:click="gestionarPoa(2024)" class="w-full flex items-center justify-center px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-zinc-900 font-medium rounded-md transition-colors">
                                <span>Gestionar</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Tarjeta de POA 2025 -->
                <div class="bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg shadow-lg overflow-hidden text-white hover:shadow-xl transition-all duration-200">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <h3 class="text-3xl font-extrabold">2025</h3>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                En preparación
                            </span>
                        </div>
                        
                        <div class="mt-4 flex flex-col space-y-2 text-sm text-blue-50">
                            <div class="flex items-center justify-between">
                                <span>Total proyectos:</span>
                                <span class="font-semibold">43</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Presupuesto:</span>
                                <span class="font-semibold">$30,000.00</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Avance:</span>
                                <span class="font-semibold">15%</span>
                            </div>
                        </div>
                        
                        <div class="mt-3 w-full bg-blue-200 bg-opacity-30 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full" style="width: 15%"></div>
                        </div>
                        
                        <div class="mt-5">
                            <button wire:click="gestionarPoa(2025)" class="w-full flex items-center justify-center px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-zinc-900 font-medium rounded-md transition-colors">
                                <span>Gestionar</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Lista detallada de proyectos del año seleccionado -->
        <div class="bg-white dark:bg-zinc-900 shadow-sm rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-medium leading-6 text-zinc-900 dark:text-white">Proyectos Activos 2024</h3>
                    <p class="mt-1 max-w-2xl text-sm text-zinc-500 dark:text-zinc-400">Listado de proyectos en ejecución</p>
                </div>
                <button type="button" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Ver todos
                </button>
            </div>
            
            <div class="border-t border-zinc-200 dark:border-zinc-700">
                <ul role="list" class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    <li class="px-4 py-4 sm:px-6 hover:bg-zinc-50 dark:hover:bg-zinc-700">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400 truncate">
                                Mejoramiento de la infraestructura tecnológica
                            </p>
                            <div class="ml-2 flex-shrink-0 flex">
                                <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                    En ejecución
                                </p>
                            </div>
                        </div>
                        <div class="flex justify-between mt-2">
                            <div class="sm:flex">
                                <div class="flex items-center text-sm text-zinc-500 dark:text-zinc-400">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-zinc-400 dark:text-zinc-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Febrero - Noviembre</span>
                                </div>
                            </div>
                            <div class="flex items-center text-sm text-zinc-500 dark:text-zinc-400">
                                <span class="font-medium">$12,500.00</span>
                            </div>
                        </div>
                        <div class="mt-2 w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-1.5">
                            <div class="bg-blue-600 h-1.5 rounded-full" style="width: 75%"></div>
                        </div>
                    </li>
                    
                    <li class="px-4 py-4 sm:px-6 hover:bg-zinc-50 dark:hover:bg-zinc-700">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400 truncate">
                                Programa de capacitación al personal
                            </p>
                            <div class="ml-2 flex-shrink-0 flex">
                                <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                    Planificado
                                </p>
                            </div>
                        </div>
                        <div class="flex justify-between mt-2">
                            <div class="sm:flex">
                                <div class="flex items-center text-sm text-zinc-500 dark:text-zinc-400">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-zinc-400 dark:text-zinc-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Abril - Septiembre</span>
                                </div>
                            </div>
                            <div class="flex items-center text-sm text-zinc-500 dark:text-zinc-400">
                                <span class="font-medium">$8,200.00</span>
                            </div>
                        </div>
                        <div class="mt-2 w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-1.5">
                            <div class="bg-blue-600 h-1.5 rounded-full" style="width: 30%"></div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>