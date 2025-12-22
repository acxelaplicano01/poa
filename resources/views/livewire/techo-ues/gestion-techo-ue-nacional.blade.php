<div>
    <div class="mx-auto rounded-lg mt-8 sm:mt-6 lg:mt-4 mb-6">
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow sm:rounded-lg p-4 sm:p-6">

            <!-- Encabezado con información del POA -->
            <div class="mb-6 pb-4 border-b border-zinc-200 dark:border-zinc-700">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                    <div>
                        <a href="{{ route('asignacionnacionalpresupuestaria') }}" class="inline-flex items-center text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Volver a POAs Nacionales
                        </a>
                        <h2 class="text-xl font-semibold text-zinc-800 dark:text-zinc-200">
                            Techos Presupuestarios por Unidad Ejecutora
                        </h2>
                        <div class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                            <p>POA: <span class="font-semibold text-zinc-700 dark:text-zinc-300">{{ $poa->anio ?? 'N/A' }}</span></p>
                            <p>Institución: <span class="font-semibold text-zinc-700 dark:text-zinc-300">{{ $poa->institucion->nombre ?? 'N/A' }}</span></p>
                        </div>
                    </div>

                    <div class="flex justify-end mt-4 sm:mt-0">
                        @can('consola.asignacionnacionalpresupuestaria.crear')
                        <x-button wire:click="create()" class="w-full sm:w-auto justify-center {{ !$puedeAsignarPresupuesto ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}" :disabled="!$puedeAsignarPresupuesto">
                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            {{ __('Nuevo Techo UE') }}
                        </x-button>
                        @endcan
                    </div>
                </div>
            </div>

            {{-- Alerta de plazo --}}
            @if(!$puedeAsignarPresupuesto && $mensajePlazo)
                <div class="mb-4 bg-amber-100 dark:bg-amber-900/30 border border-amber-400 dark:border-amber-700 text-amber-800 dark:text-amber-300 px-4 py-3 rounded-lg" role="alert">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start flex-1">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="font-semibold">Asignación a UEs no disponible</p>
                                <p class="text-sm mt-1">{{ $mensajePlazo }}</p>
                            </div>
                        </div>
                        <a href="{{ route('plazos-poa', ['idPoa' => $idPoa]) }}" class="ml-4 flex-shrink-0 px-3 py-2 bg-amber-600 hover:bg-amber-700 dark:bg-amber-700 dark:hover:bg-amber-800 text-white text-sm font-medium rounded-md transition-colors flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Configurar Plazos
                        </a>
                    </div>
                </div>
            @endif

            {{-- Contador de días restantes --}}
            @if($puedeAsignarPresupuesto && $diasRestantes !== null)
                <div class="mb-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-300 px-4 py-3 rounded-lg flex items-center justify-between" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <div>
                            <p class="font-semibold text-sm">Plazo de asignación nacional activo</p>
                            <p class="text-xs mt-0.5">Puedes asignar presupuesto a las Unidades Ejecutoras</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="flex items-baseline">
                            <span class="text-3xl font-bold">{{ $diasRestantes }}</span>
                            <span class="text-sm ml-1">{{ $diasRestantes == 1 ? 'día' : 'días' }}</span>
                        </div>
                        <p class="text-xs mt-0.5">{{ $diasRestantes == 1 ? 'restante' : 'restantes' }}</p>
                    </div>
                </div>
            @endif

            @if (session()->has('message'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md" role="alert">
                    <p class="font-medium">{{ session('message') }}</p>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md" role="alert">
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Sistema de Tabs -->
            <div class="mt-6">
                <!-- Tabs Header -->
                <div class="border-b border-zinc-200 dark:border-zinc-700">
                    <nav class="-mb-px flex space-x-2 sm:space-x-8 overflow-x-auto scrollbar-hide pb-px" aria-label="Tabs">
                        <button 
                            wire:click="setActiveTab('resumen')"
                            class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-xs sm:text-sm transition-colors duration-200 flex-shrink-0 {{ $activeTab === 'resumen' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 hover:border-zinc-300 dark:text-zinc-400 dark:hover:text-zinc-300' }}">
                            <span class="flex items-center">
                                <span class="hidden sm:inline">Resumen Presupuestario</span>
                                <svg class="inline ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </span>
                        </button>
                        <button 
                            wire:click="setActiveTab('sin-asignar')"
                            class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-xs sm:text-sm transition-colors duration-200 flex-shrink-0 {{ $activeTab === 'sin-asignar' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 hover:border-zinc-300 dark:text-zinc-400 dark:hover:text-zinc-300' }}">
                            <span class="flex items-center">
                                <span class="hidden sm:inline">UEs sin Techo</span> 
                                <span class="ml-1 sm:ml-2 bg-yellow-100 dark:bg-yellow-800 text-yellow-900 dark:text-yellow-100 py-0.5 px-1.5 sm:px-2.5 rounded-full text-xs font-medium">
                                    {{ $unidadesSinTecho->count() }}
                                </span>
                            </span>
                        </button>
                        <button 
                            wire:click="setActiveTab('con-asignacion')"
                            class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-xs sm:text-sm transition-colors duration-200 flex-shrink-0 {{ $activeTab === 'con-asignacion' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 hover:border-zinc-300 dark:text-zinc-400 dark:hover:text-zinc-300' }}">
                            <span class="flex items-center">
                                <span class="hidden sm:inline">UEs con Techo</span> 
                                <span class="ml-1 sm:ml-2 bg-green-100 dark:bg-green-800 text-green-900 dark:text-green-100 py-0.5 px-1.5 sm:px-2.5 rounded-full text-xs font-medium">
                                    {{ $techoUesConTecho->groupBy('idUE')->count() }}
                                </span>
                            </span>
                        </button>
                        <button 
                            wire:click="setActiveTab('timeline')"
                            class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-xs sm:text-sm transition-colors duration-200 flex-shrink-0 {{ $activeTab === 'timeline' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 hover:border-zinc-300 dark:text-zinc-400 dark:hover:text-zinc-300' }}">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="hidden sm:inline">Línea de Tiempo</span>
                                <span class="sm:hidden">Timeline</span>
                            </span>
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="mt-6">
                    @if($activeTab === 'resumen')
                        <!-- Resumen Presupuestario -->
                        <div class="grid grid-cols-1 xl:grid-cols-[73.5%_25%] gap-6">
                            <div class="space-y-8">
                            <!-- Métricas Presupuestarias por Fuente -->
                            @if($fuentes && $fuentes->count() > 0)
                                <!-- Resumen General del Presupuesto -->
                                <div class="bg-gradient-to-r from-indigo-50 to-indigo-50 dark:from-indigo-900/20 dark:to-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg p-6">
                                    <h3 class="text-lg font-semibold text-indigo-900 dark:text-indigo-100 mb-4">
                                        Resumen General del Presupuesto
                                    </h3>
                                    
                                    @php
                                        $totalGeneral = 0;
                                        $asignadoGeneral = 0;
                                        $disponibleGeneral = 0;

                                        foreach ($fuentes as $fuente) {
                                            $techoGlobal = $poa->techoUes->where('fuente.id', $fuente->id)->whereNull('idUE')->sum('monto');
                                            $asignadoUE = $poa->techoUes->where('fuente.id', $fuente->id)->whereNotNull('idUE')->sum('monto');

                                            $totalGeneral += $techoGlobal;
                                            $asignadoGeneral += $asignadoUE;
                                        }

                                        $disponibleGeneral = $totalGeneral - $asignadoGeneral;
                                        $porcentajeGeneral = $totalGeneral > 0 ? ($asignadoGeneral / $totalGeneral) * 100 : 0;
                                    @endphp
                                    
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                                L. {{ number_format($totalGeneral, 2) }}
                                            </div>
                                            <div class="text-sm text-zinc-500 dark:text-zinc-400">Techo Total</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                                L. {{ number_format($asignadoGeneral, 2) }}
                                            </div>
                                            <div class="text-sm text-zinc-500 dark:text-zinc-400">Asignado a UEs</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-2xl font-bold {{ $disponibleGeneral > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                L. {{ number_format($disponibleGeneral, 2) }}
                                            </div>
                                            <div class="text-sm text-zinc-500 dark:text-zinc-400">Disponible</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">
                                                {{ number_format($porcentajeGeneral, 1) }}%
                                            </div>
                                            <div class="text-sm text-zinc-500 dark:text-zinc-400">% Ejecutado</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Detalle por Fuente de Financiamiento -->
                                <div>
                                    <h3 class="text-lg font-semibold text-zinc-800 dark:text-zinc-200 mb-4">
                                        Detalle por Fuente de Financiamiento
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                        @foreach($fuentes as $fuente)
                                            @php
                                                $techoGlobal = $poa->techoUes->where('fuente.id', $fuente->id)->whereNull('idUE')->sum('monto');
                                                $asignadoUE = $poa->techoUes->where('fuente.id', $fuente->id)->whereNotNull('idUE')->sum('monto');
                                                $disponible = $techoGlobal - $asignadoUE;
                                                $porcentajeUsado = $techoGlobal > 0 ? ($asignadoUE / $techoGlobal) * 100 : 0;

                                                // Determinar estado y color
                                                if ($porcentajeUsado >= 100) {
                                                    $estadoClase = 'bg-red-500';
                                                    $estadoTexto = 'Agotado';
                                                    $colorTexto = 'text-red-600 dark:text-red-400';
                                                } elseif ($porcentajeUsado >= 60) {
                                                    $estadoClase = 'bg-yellow-500';
                                                    $estadoTexto = 'Poco recurso';
                                                    $colorTexto = 'text-yellow-600 dark:text-yellow-400';
                                                } else {
                                                    $estadoClase = 'bg-green-500';
                                                    $estadoTexto = 'Disponible';
                                                    $colorTexto = 'text-green-600 dark:text-green-400';
                                        }
                                            @endphp
                                            
                                            <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg p-6 hover:shadow-lg transition-shadow duration-200">
                                                <div class="flex items-center justify-between mb-4">
                                                    <h4 class="text-md font-semibold text-zinc-900 dark:text-zinc-100 truncate" title="{{ $fuente->nombre }}">
                                                        {{ $fuente->identificador }} - {{ Str::limit($fuente->nombre, 25) }}
                                                    </h4>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $estadoClase }} text-white">
                                                        {{ $estadoTexto }}
                                                    </span>
                                                </div>
                                                
                                                <div class="space-y-3">
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-sm text-zinc-500 dark:text-zinc-400">Techo Global:</span>
                                                        <span class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                                            L. {{ number_format($techoGlobal, 2) }}
                                                        </span>
                                                    </div>
                                                    
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-sm text-zinc-500 dark:text-zinc-400">Asignado a UEs:</span>
                                                        <span class="text-sm font-semibold {{ $colorTexto }}">
                                                            L. {{ number_format($asignadoUE, 2) }}
                                                        </span>
                                                    </div>
                                                    
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-sm text-zinc-500 dark:text-zinc-400">Disponible:</span>
                                                        <span class="text-sm font-semibold {{ $disponible > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                            L. {{ number_format($disponible, 2) }}
                                                        </span>
                                                    </div>
                                                    
                                                    <!-- Barra de Progreso -->
                                                    <div class="mt-4">
                                                        <div class="flex justify-between text-xs text-zinc-500 dark:text-zinc-400 mb-1">
                                                            <span>Nivel de Asignación</span>
                                                            <span>{{ number_format($porcentajeUsado, 1) }}%</span>
                                                        </div>
                                                        <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-2.5">
                                                            <div class="{{ $estadoClase }} h-2.5 rounded-full transition-all duration-300" 
                                                                 style="width: {{ min($porcentajeUsado, 100) }}%">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @if($techoGlobal > 0)
                                                        <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-2">
                                                            {{ $poa->techoUes->where('fuente.id', $fuente->id)->whereNotNull('idUE')->count() }} UEs asignadas
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Resumen de Asignaciones Existente -->
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <!-- Tarjetas de Resumen -->
                                <div class="lg:col-span-2 space-y-4">
                                    <!-- Total Asignado -->
                                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-blue-100">Total Asignado</p>
                                                <p class="text-3xl font-bold">L. {{ number_format($totalAsignado, 2) }} </p>
                                                <p class="text-blue-100 text-sm mt-1">
                                                    {{ $techoUesConTecho->groupBy('idUE')->count() }} UEs con presupuesto
                                                </p>
                                            </div>
                                            <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                                                <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Estadísticas Rápidas -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-4">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="bg-green-100 dark:bg-green-900 rounded-md p-2">
                                                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Con Presupuesto</p>
                                                    <p class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $techoUesConTecho->groupBy('idUE')->count() }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-4">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="bg-yellow-100 dark:bg-yellow-900 rounded-md p-2">
                                                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Sin Presupuesto</p>
                                                    <p class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $unidadesSinTecho->count() }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Resumen por Fuente de Financiamiento -->
                                <div class="bg-white dark:bg-zinc-800 rounded-lg shadow">
                                    <div class="p-6">
                                        <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100 mb-4">Por Fuente de Financiamiento</h3>
                                        <div class="space-y-4">
                                            @forelse($resumenPorFuente as $fuente => $datos)
                                                <div class="flex items-center justify-between p-3 bg-zinc-50 dark:bg-zinc-700 rounded-lg">
                                                    <div>
                                                        <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ $fuente }}</p>
                                                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $datos['cantidad'] }} asignaciones</p>
                                                    </div>
                                                    <div class="text-right">
                                                         <p class="text-xs text-zinc-500 dark:text-zinc-400">L. </p>
                                                        <p class="font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($datos['monto'], 2) }}</p>
                                                       
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="text-zinc-500 dark:text-zinc-400 text-center py-4">No hay asignaciones registradas</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <!-- Línea de Tiempo de Plazos -->
                            @if($this->plazosTimeline && $this->plazosTimeline->count() > 0)
                                <div class="space-y-4">
                                    @php
                                        $hoy = \Carbon\Carbon::now();
                                        $plazosVigentes = $this->plazosTimeline->where('es_vigente', true);
                                        $plazosVencidos = $this->plazosTimeline->where('ha_vencido', true);
                                        $plazosProximos = $this->plazosTimeline->where('es_proximo', true);
                                    @endphp
                                    <!-- Timeline Visual Compacto -->
                                    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-lg p-3 sm:p-4">
                                        <!-- Encabezado de Timeline -->
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-3 sm:mb-4 gap-2">
                                        <div>
                                            <h3 class="text-base sm:text-lg font-semibold text-zinc-900 dark:text-zinc-200">
                                                <span class="hidden sm:inline">Línea de Tiempo de Plazos POA {{ $poa->anio }}</span>
                                                <span class="sm:hidden">Plazos POA {{ $poa->anio }}</span>
                                            </h3>
                                            <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                                                <span class="hidden sm:inline">Estado actual de los plazos</span>
                                                <span class="sm:hidden">{{ $plazosVigentes->count() }} vigente(s)</span>
                                            </p>
                                        </div>
                                    </div>
                                        <div class="relative">
                                            <!-- Línea de tiempo central -->
                                            <div class="absolute left-3 sm:left-6 top-0 bottom-0 w-0.5 bg-zinc-300 dark:bg-zinc-600"></div>
                                            
                                            <!-- Items del timeline -->
                                            <div class="space-y-3 sm:space-y-6">
                                                @foreach($this->plazosTimeline as $plazo)
                                                    @php
                                                        $fechaInicio = \Carbon\Carbon::parse($plazo['fecha_inicio']);
                                                        $fechaFin = \Carbon\Carbon::parse($plazo['fecha_fin']);
                                                        $duracionDias = $fechaInicio->diffInDays($fechaFin) + 1;
                                                        
                                                        // Determinar color según estado
                                                        if ($plazo['es_vigente']) {
                                                            $colorClass = 'bg-green-500';
                                                            $borderClass = 'border-green-500';
                                                            $bgClass = 'bg-green-50 dark:bg-green-900/20';
                                                            $textClass = 'text-green-700 dark:text-green-300';
                                                            $badgeClass = 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100';
                                                            $estadoTexto = 'EN CURSO';
                                                        } elseif ($plazo['ha_vencido']) {
                                                            $colorClass = 'bg-red-500';
                                                            $borderClass = 'border-red-500';
                                                            $bgClass = 'bg-red-50 dark:bg-red-900/20';
                                                            $textClass = 'text-red-700 dark:text-red-300';
                                                            $badgeClass = 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100';
                                                            $estadoTexto = 'VENCIDO';
                                                        } elseif ($plazo['es_proximo']) {
                                                            $colorClass = 'bg-purple-500';
                                                            $borderClass = 'border-purple-500';
                                                            $bgClass = 'bg-purple-50 dark:bg-purple-900/20';
                                                            $textClass = 'text-purple-700 dark:text-purple-300';
                                                            $badgeClass = 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100';
                                                            $estadoTexto = 'PRÓXIMO';
                                                        } else {
                                                            $colorClass = 'bg-zinc-400';
                                                            $borderClass = 'border-zinc-400';
                                                            $bgClass = 'bg-zinc-50 dark:bg-zinc-800';
                                                            $textClass = 'text-zinc-600 dark:text-zinc-400';
                                                            $badgeClass = 'bg-zinc-100 text-zinc-800 dark:bg-zinc-700 dark:text-zinc-300';
                                                            $estadoTexto = 'INACTIVO';
                                                        }
                                                        
                                                        // Calcular días hasta el inicio (si es próximo)
                                                        $diasHastaInicio = $plazo['es_proximo'] ? (int) $hoy->diffInDays($fechaInicio) : null;
                                                    @endphp
                                                    
                                                    <div class="relative flex items-start group">
                                                        <!-- Punto del timeline -->
                                                        <div class="absolute left-3 sm:left-6 -translate-x-1/2 flex items-center justify-center">
                                                            <div class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full {{ $colorClass }} ring-2 sm:ring-4 ring-white dark:ring-zinc-900 transition-transform group-hover:scale-125"></div>
                                                        </div>
                                                        
                                                        <!-- Contenido -->
                                                        <div class="ml-7 sm:ml-12 flex-1">
                                                            <div class="border-l-2 sm:border-l-4 {{ $borderClass }} {{ $bgClass }} rounded-lg p-2 sm:p-3 shadow-sm hover:shadow-md transition-shadow">
                                                                <div class="flex justify-between items-start mb-1 sm:mb-2">
                                                                    <div class="flex-1">
                                                                        <div class="flex items-center gap-1 sm:gap-2 flex-wrap">
                                                                            <h4 class="text-sm sm:text-base font-semibold {{ $textClass }}">
                                                                                {{ $plazo['nombre'] }}
                                                                            </h4>
                                                                            <span class="px-1.5 sm:px-2 py-0.5 text-xs font-semibold rounded-full {{ $badgeClass }}">
                                                                                {{ $estadoTexto }}
                                                                            </span>
                                                                            @if(!$plazo['activo'])
                                                                                <span class="px-1.5 sm:px-2 py-0.5 text-xs font-semibold rounded-full bg-zinc-100 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-400">
                                                                                    INACTIVO
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-2 text-xs sm:text-sm">
                                                                    <div class="flex items-center text-zinc-600 dark:text-zinc-400">
                                                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                        </svg>
                                                                        <span class="font-medium">{{ $fechaInicio->format('d/m/Y') }}</span>
                                                                    </div>
                                                                    
                                                                    <div class="flex items-center text-zinc-600 dark:text-zinc-400">
                                                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                                                        </svg>
                                                                        <span class="font-medium">{{ $fechaFin->format('d/m/Y') }}</span>
                                                                    </div>
                                                                    
                                                                    <div class="flex items-center text-zinc-600 dark:text-zinc-400">
                                                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                        </svg>
                                                                        <span class="font-medium">{{ $duracionDias }} días</span>
                                                                    </div>
                                                                </div>
                                                                
                                                                <!-- Información adicional según estado -->
                                                                @if($plazo['es_vigente'] && $plazo['dias_restantes'] !== null)
                                                                    <div class="mt-1.5 sm:mt-2 pt-1.5 sm:pt-2 border-t border-zinc-200 dark:border-zinc-700">
                                                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 sm:gap-0">
                                                                            <div class="flex items-center {{ $textClass }}">
                                                                                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                                                </svg>
                                                                                <span class="text-xs sm:text-sm font-semibold">
                                                                                    {{ $plazo['dias_restantes'] }} {{ $plazo['dias_restantes'] == 1 ? 'día restante' : 'días restantes' }}
                                                                                </span>
                                                                            </div>
                                                                            <!-- Barra de progreso -->
                                                                            @php
                                                                                $totalDias = $fechaInicio->diffInDays($fechaFin) + 1;
                                                                                $diasTranscurridos = $fechaInicio->diffInDays($hoy);
                                                                                $porcentajeProgreso = $totalDias > 0 ? ($diasTranscurridos / $totalDias) * 100 : 0;
                                                                            @endphp
                                                                            <div class="flex-1 sm:ml-3">
                                                                                <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-1.5 sm:h-2">
                                                                                    <div class="bg-green-500 h-1.5 sm:h-2 rounded-full transition-all" style="width: {{ min($porcentajeProgreso, 100) }}%"></div>
                                                                                </div>
                                                                            </div>
                                                                            <span class="text-xs text-zinc-500 dark:text-zinc-400 sm:ml-2">
                                                                                {{ number_format($porcentajeProgreso, 0) }}%
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                @elseif($plazo['es_proximo'] && $diasHastaInicio)
                                                                    <div class="mt-1.5 sm:mt-2 pt-1.5 sm:pt-2 border-t border-zinc-200 dark:border-zinc-700">
                                                                        <div class="flex items-center {{ $textClass }}">
                                                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                            </svg>
                                                                            <span class="text-xs sm:text-sm font-semibold">
                                                                                Comienza en {{ $diasHastaInicio }} {{ $diasHastaInicio == 1 ? 'día' : 'días' }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                @elseif($plazo['ha_vencido'])
                                                                    @php
                                                                        $diasVencido = (int) $fechaFin->diffInDays($hoy);
                                                                    @endphp
                                                                    <div class="mt-1.5 sm:mt-2 pt-1.5 sm:pt-2 border-t border-zinc-200 dark:border-zinc-700">
                                                                        <div class="flex items-center {{ $textClass }}">
                                                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                                            </svg>
                                                                            <span class="text-xs sm:text-sm font-semibold">
                                                                                Venció hace {{ $diasVencido }} {{ $diasVencido == 1 ? 'día' : 'días' }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">No hay plazos registrados</h3>
                                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">No se han definido plazos para el POA {{ $poa->anio }}.</p>
                                </div>
                            @endif
                        </div>
                    @elseif($activeTab === 'sin-asignar')
                        <!-- Unidades Ejecutoras sin Techo -->
                        <div class="space-y-4">
                            <!-- Buscador -->
                            <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                                <div class="relative flex-1 max-w-md">
                                    <input wire:model.live="searchSinTecho" type="text" placeholder="Buscar UEs sin techo..."
                                        class="w-full pl-10 pr-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-zinc-700 dark:text-zinc-100">
                                    <div class="absolute left-3 top-2.5">
                                        <svg class="h-5 w-5 text-zinc-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ $unidadesSinTecho->count() }} UEs sin techo presupuestario
                                </div>
                            </div>

                            @if($unidadesSinTecho->count() > 0)
                                <!-- Lista de UEs sin Techo -->
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($unidadesSinTecho as $ue)
                                        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700 p-4 hover:shadow-md transition-shadow">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1 min-w-0">
                                                    <h4 class="text-sm font-medium text-zinc-900 dark:text-zinc-100 truncate">
                                                        {{ $ue->name ?? 'N/A' }}
                                                    </h4>
                                                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                                                        Descripción: {{ $ue->descripcion }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="mt-3 flex justify-end">
                                                @can('consola.asignacionnacionalpresupuestaria.asignar')
                                                <x-button wire:click="crearTechoParaUe({{ $ue->id }})" :disabled="!$puedeAsignarPresupuesto"
                                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-indigo-700 bg-indigo-100 dark:bg-indigo-900 dark:text-indigo-300 transition-colors {{ !$puedeAsignarPresupuesto ? 'opacity-50 cursor-not-allowed pointer-events-none' : 'hover:bg-indigo-200 dark:hover:bg-indigo-800' }}">
                                                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                    Asignar
                                                </x-button>
                                                @endcan
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">¡Excelente!</h3>
                                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Todas las unidades ejecutoras tienen techo presupuestario asignado.</p>
                                </div>
                            @endif
                        </div>

                    @elseif($activeTab === 'con-asignacion')
                        <!-- Unidades Ejecutoras con Techo -->
                        <div class="space-y-4">
                            <!-- Buscador -->
                            <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                                <div class="relative flex-1 max-w-md">
                                    <input wire:model.live="searchConTecho" type="text" placeholder="Buscar UEs con techo..."
                                        class="w-full pl-10 pr-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-zinc-700 dark:text-zinc-100">
                                    <div class="absolute left-3 top-2.5">
                                        <svg class="h-5 w-5 text-zinc-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ $techoUesConTecho->groupBy('idUE')->count() }} UEs con techo presupuestario
                                </div>
                            </div>

                            @if($techoUesConTecho->count() > 0)
                                <!-- Tabla de UEs con Techo -->
                                <div class="bg-white dark:bg-zinc-800 shadow overflow-hidden sm:rounded-lg">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                                            <thead class="bg-zinc-50 dark:bg-zinc-700">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                                        Unidad Ejecutora
                                                    </th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                                        Fuentes de Financiamiento
                                                    </th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                                        Total Asignado
                                                    </th>
                                                    <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                                        Acciones
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                                                @foreach($techoUesConTecho->groupBy('idUE') as $idUe => $techos)
                                                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div>
                                                                <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                                    {{ $techos->first()->unidadEjecutora->name ?? 'N/A' }}
                                                                </div>
                                                                <div class="text-sm text-zinc-500 dark:text-zinc-400">
                                                                    Descripción: {{ $techos->first()->unidadEjecutora->descripcion ?? 'Sin descripción' }}
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4">
                                                            <div class="space-y-1">
                                                                @foreach($techos as $techo)
                                                                    <div class="flex items-center justify-between">
                                                                        <span class="text-xs px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full">
                                                                            {{ $techo->fuente->nombre ?? 'Sin fuente' }}
                                                                        </span>
                                                                        <span class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                                            L. {{ number_format($techo->monto, 2) }} 
                                                                        </span>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                                                L. {{ number_format($techos->sum('monto'), 2) }} 
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                                            <button wire:click="viewAnalysis({{ $idUe }})"
                                                                class="text-emerald-600 hover:text-emerald-900 dark:text-emerald-400 dark:hover:text-emerald-300 cursor-pointer" title="Ver análisis presupuestario">
                                                                <svg class="w-5 h-5 text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6.025A7.5 7.5 0 1 0 17.975 14H10V6.025Z"/>
                                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 3c-.169 0-.334.014-.5.025V11h7.975c.011-.166.025-.331.025-.5A7.5 7.5 0 0 0 13.5 3Z"/>
                                                                </svg>
                                                            </button>
                                                            @can('consola.asignacionnacionalpresupuestaria.editar')
                                                            <button wire:click="edit({{ $idUe }})"
                                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 cursor-pointer {{ !$puedeAsignarPresupuesto ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}" :disabled="!$puedeAsignarPresupuesto">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                                    <path fill-rule="evenodd"
                                                                        d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </button>
                                                            @endcan
                                                            @can('consola.asignacionnacionalpresupuestaria.eliminar')
                                                            <button wire:click="eliminarTodosLosTechos({{ $idUe }})"
                                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 cursor-pointer {{ !$puedeAsignarPresupuesto ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}" :disabled="!$puedeAsignarPresupuesto">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path fill-rule="evenodd"
                                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </button>
                                                            @endcan
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">No hay techos asignados</h3>
                                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Comienza asignando techos presupuestarios a las unidades ejecutoras.</p>
                                </div>
                            @endif
                        </div>
                    @elseif($activeTab === 'timeline')
                        <!-- Línea de Tiempo de Plazos -->
                        <div class="space-y-4 sm:space-y-6">
                            <!-- Encabezado -->
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
                                <div>
                                    <h3 class="text-base sm:text-lg font-semibold text-zinc-800 dark:text-zinc-200">
                                        Línea de Tiempo de Plazos POA {{ $poa->anio }}
                                    </h3>
                                    <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                                        Visualización de todos los plazos configurados para este POA
                                    </p>
                                </div>
                                <a href="{{ route('plazos-poa', ['idPoa' => $idPoa]) }}" class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-800 text-white text-xs sm:text-sm font-medium rounded-md transition-colors">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Gestionar Plazos
                                </a>
                            </div>

                            @if($this->plazosTimeline && $this->plazosTimeline->count() > 0)
                                @php
                                    $hoy = \Carbon\Carbon::now();
                                    $plazosVigentes = $this->plazosTimeline->where('es_vigente', true);
                                    $plazosVencidos = $this->plazosTimeline->where('ha_vencido', true);
                                    $plazosProximos = $this->plazosTimeline->where('es_proximo', true);
                                @endphp

                                <!-- Resumen de Estado -->
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                                    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-3 sm:p-4 border-l-4 border-blue-500">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400">Total Plazos</p>
                                                <p class="text-xl sm:text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ $this->plazosTimeline->count() }}</p>
                                            </div>
                                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-3 sm:p-4 border-l-4 border-green-500">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400">Vigentes</p>
                                                <p class="text-xl sm:text-2xl font-bold text-green-600 dark:text-green-400">{{ $plazosVigentes->count() }}</p>
                                            </div>
                                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-3 sm:p-4 border-l-4 border-red-500">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400">Vencidos</p>
                                                <p class="text-xl sm:text-2xl font-bold text-red-600 dark:text-red-400">{{ $plazosVencidos->count() }}</p>
                                            </div>
                                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-3 sm:p-4 border-l-4 border-purple-500">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400">Próximos</p>
                                                <p class="text-xl sm:text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $plazosProximos->count() }}</p>
                                            </div>
                                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Timeline Visual -->
                                <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-lg p-3 sm:p-4 md:p-6">
                                    <div class="relative">
                                        <!-- Línea de tiempo central -->
                                        <div class="absolute left-3 sm:left-6 md:left-8 top-0 bottom-0 w-0.5 bg-zinc-300 dark:bg-zinc-600"></div>
                                        
                                        <!-- Items del timeline -->
                                        <div class="space-y-3 sm:space-y-6 md:space-y-8">
                                            @foreach($this->plazosTimeline as $plazo)
                                                @php
                                                    $fechaInicio = \Carbon\Carbon::parse($plazo['fecha_inicio']);
                                                    $fechaFin = \Carbon\Carbon::parse($plazo['fecha_fin']);
                                                    $duracionDias = $fechaInicio->diffInDays($fechaFin) + 1;
                                                    
                                                    // Determinar color según estado
                                                    if ($plazo['es_vigente']) {
                                                        $colorClass = 'bg-green-500';
                                                        $borderClass = 'border-green-500';
                                                        $bgClass = 'bg-green-50 dark:bg-green-900/20';
                                                        $textClass = 'text-green-700 dark:text-green-300';
                                                        $badgeClass = 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100';
                                                        $estadoTexto = 'EN CURSO';
                                                    } elseif ($plazo['ha_vencido']) {
                                                        $colorClass = 'bg-red-500';
                                                        $borderClass = 'border-red-500';
                                                        $bgClass = 'bg-red-50 dark:bg-red-900/20';
                                                        $textClass = 'text-red-700 dark:text-red-300';
                                                        $badgeClass = 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100';
                                                        $estadoTexto = 'VENCIDO';
                                                    } elseif ($plazo['es_proximo']) {
                                                        $colorClass = 'bg-purple-500';
                                                        $borderClass = 'border-purple-500';
                                                        $bgClass = 'bg-purple-50 dark:bg-purple-900/20';
                                                        $textClass = 'text-purple-700 dark:text-purple-300';
                                                        $badgeClass = 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100';
                                                        $estadoTexto = 'PRÓXIMO';
                                                    } else {
                                                        $colorClass = 'bg-zinc-400';
                                                        $borderClass = 'border-zinc-400';
                                                        $bgClass = 'bg-zinc-50 dark:bg-zinc-800';
                                                        $textClass = 'text-zinc-600 dark:text-zinc-400';
                                                        $badgeClass = 'bg-zinc-100 text-zinc-800 dark:bg-zinc-700 dark:text-zinc-300';
                                                        $estadoTexto = 'INACTIVO';
                                                    }
                                                    
                                                    // Calcular días hasta el inicio (si es próximo)
                                                    $diasHastaInicio = $plazo['es_proximo'] ? (int) $hoy->diffInDays($fechaInicio) : null;
                                                @endphp
                                                
                                                <div class="relative flex items-start group">
                                                    <!-- Punto del timeline -->
                                                    <div class="absolute left-3 sm:left-6 md:left-8 -translate-x-1/2 flex items-center justify-center">
                                                        <div class="w-2.5 h-2.5 sm:w-3 sm:h-3 md:w-4 md:h-4 rounded-full {{ $colorClass }} ring-2 sm:ring-3 md:ring-4 ring-white dark:ring-zinc-900 transition-transform group-hover:scale-125"></div>
                                                    </div>
                                                    
                                                    <!-- Contenido -->
                                                    <div class="ml-7 sm:ml-12 md:ml-16 flex-1">
                                                        <div class="border-l-2 sm:border-l-3 md:border-l-4 {{ $borderClass }} {{ $bgClass }} rounded-lg p-2 sm:p-3 md:p-4 shadow-sm hover:shadow-md transition-shadow">
                                                            <div class="flex flex-col sm:flex-row justify-between items-start gap-2 sm:gap-0 mb-2 sm:mb-3">
                                                                <div class="flex-1">
                                                                    <div class="flex flex-wrap items-center gap-1 sm:gap-2 mb-1">
                                                                        <h4 class="text-sm sm:text-base md:text-lg font-semibold {{ $textClass }}">
                                                                            {{ $plazo['nombre'] }}
                                                                        </h4>
                                                                        <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 text-xs font-semibold rounded-full {{ $badgeClass }}">
                                                                            {{ $estadoTexto }}
                                                                        </span>
                                                                        @if(!$plazo['activo'])
                                                                            <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 text-xs font-semibold rounded-full bg-zinc-100 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-400">
                                                                                INACTIVO
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                    @if($plazo['descripcion'])
                                                                        <p class="text-xs sm:text-sm text-zinc-600 dark:text-zinc-400 mt-1">
                                                                            {{ $plazo['descripcion'] }}
                                                                        </p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 sm:gap-3 text-xs sm:text-sm">
                                                                <div class="flex items-center text-zinc-600 dark:text-zinc-400">
                                                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                    </svg>
                                                                    <span class="font-medium">Inicio:</span>
                                                                    <span class="ml-1">{{ $fechaInicio->format('d/m/Y') }}</span>
                                                                </div>
                                                                
                                                                <div class="flex items-center text-zinc-600 dark:text-zinc-400">
                                                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                    </svg>
                                                                    <span class="font-medium">Fin:</span>
                                                                    <span class="ml-1">{{ $fechaFin->format('d/m/Y') }}</span>
                                                                </div>
                                                                
                                                                <div class="flex items-center text-zinc-600 dark:text-zinc-400">
                                                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                    </svg>
                                                                    <span class="font-medium">Duración:</span>
                                                                    <span class="ml-1">{{ $duracionDias }} {{ $duracionDias == 1 ? 'día' : 'días' }}</span>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Información adicional según estado -->
                                                            @if($plazo['es_vigente'] && $plazo['dias_restantes'] !== null)
                                                                <div class="mt-2 sm:mt-3 pt-2 sm:pt-3 border-t border-zinc-200 dark:border-zinc-700">
                                                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-0 sm:justify-between">
                                                                        <div class="flex items-center {{ $textClass }}">
                                                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                                            </svg>
                                                                            <span class="text-xs sm:text-sm font-semibold">
                                                                                {{ $plazo['dias_restantes'] }} {{ $plazo['dias_restantes'] == 1 ? 'día restante' : 'días restantes' }}
                                                                            </span>
                                                                        </div>
                                                                        <!-- Barra de progreso -->
                                                                        @php
                                                                            $totalDias = $fechaInicio->diffInDays($fechaFin) + 1;
                                                                            $diasTranscurridos = $fechaInicio->diffInDays($hoy);
                                                                            $porcentajeProgreso = $totalDias > 0 ? ($diasTranscurridos / $totalDias) * 100 : 0;
                                                                        @endphp
                                                                        <div class="flex-1 sm:ml-4">
                                                                            <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-1.5 sm:h-2">
                                                                                <div class="bg-green-500 h-1.5 sm:h-2 rounded-full transition-all" style="width: {{ min($porcentajeProgreso, 100) }}%"></div>
                                                                            </div>
                                                                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 text-right">
                                                                                {{ number_format($porcentajeProgreso, 1) }}% transcurrido
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @elseif($plazo['es_proximo'] && $diasHastaInicio)
                                                                <div class="mt-2 sm:mt-3 pt-2 sm:pt-3 border-t border-zinc-200 dark:border-zinc-700">
                                                                    <div class="flex items-center {{ $textClass }}">
                                                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                        </svg>
                                                                        <span class="text-xs sm:text-sm font-semibold">
                                                                            Comienza en {{ $diasHastaInicio }} {{ $diasHastaInicio == 1 ? 'día' : 'días' }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            @elseif($plazo['ha_vencido'])
                                                                @php
                                                                    $diasVencido = (int) $fechaFin->diffInDays($hoy);
                                                                @endphp
                                                                <div class="mt-2 sm:mt-3 pt-2 sm:pt-3 border-t border-zinc-200 dark:border-zinc-700">
                                                                    <div class="flex items-center {{ $textClass }}">
                                                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                                        </svg>
                                                                        <span class="text-xs sm:text-sm font-semibold">
                                                                            Venció hace {{ $diasVencido }} {{ $diasVencido == 1 ? 'día' : 'días' }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <!-- Vista compacta en móvil/lista -->
                                <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-4">
                                    <h4 class="text-md font-semibold text-zinc-800 dark:text-zinc-200 mb-4">Vista Rápida</h4>
                                    <div class="space-y-2">
                                        @foreach($this->plazosTimeline as $plazo)
                                            @php
                                                $fechaInicio = \Carbon\Carbon::parse($plazo['fecha_inicio']);
                                                $fechaFin = \Carbon\Carbon::parse($plazo['fecha_fin']);
                                                
                                                if ($plazo['es_vigente']) {
                                                    $iconClass = 'text-green-500';
                                                    $icon = '●';
                                                } elseif ($plazo['ha_vencido']) {
                                                    $iconClass = 'text-red-500';
                                                    $icon = '✕';
                                                } elseif ($plazo['es_proximo']) {
                                                    $iconClass = 'text-purple-500';
                                                    $icon = '○';
                                                } else {
                                                    $iconClass = 'text-zinc-400';
                                                    $icon = '−';
                                                }
                                            @endphp
                                            <div class="flex items-center justify-between p-2 hover:bg-zinc-50 dark:hover:bg-zinc-700 rounded transition-colors">
                                                <div class="flex items-center flex-1">
                                                    <span class="text-2xl {{ $iconClass }} mr-3">{{ $icon }}</span>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100 truncate">
                                                            {{ $plazo['nombre'] }}
                                                        </p>
                                                        <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                                            {{ $fechaInicio->format('d/m/Y') }} - {{ $fechaFin->format('d/m/Y') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="text-right ml-2">
                                                    @if($plazo['es_vigente'] && $plazo['dias_restantes'] !== null)
                                                        <span class="text-xs font-semibold text-green-600 dark:text-green-400">
                                                            {{ $plazo['dias_restantes'] }}d
                                                        </span>
                                                    @elseif($plazo['es_proximo'])
                                                        <span class="text-xs font-semibold text-purple-600 dark:text-purple-400">
                                                            Próximo
                                                        </span>
                                                    @elseif($plazo['ha_vencido'])
                                                        <span class="text-xs font-semibold text-red-600 dark:text-red-400">
                                                            Vencido
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-12 bg-white dark:bg-zinc-800 rounded-lg shadow">
                                    <svg class="mx-auto h-16 w-16 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <h3 class="mt-4 text-lg font-medium text-zinc-900 dark:text-zinc-100">No hay plazos configurados</h3>
                                    <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                                        Aún no se han configurado plazos para este POA.
                                    </p>
                                    <div class="mt-6">
                                        <a href="{{ route('plazos-poa', ['idPoa' => $idPoa]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-800 text-white text-sm font-medium rounded-md transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Crear Primer Plazo
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear/editar Techo -->
    @include('livewire.techo-ues.create')

    <!-- Modal de confirmación para eliminar -->
    @include('livewire.techo-ues.deleteConfirmation')

</div>