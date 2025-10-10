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
                        <x-button wire:click="create()" class="w-full sm:w-auto justify-center">
                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            {{ __('Nuevo Techo UE') }}
                        </x-button>
                    </div>
                </div>
            </div>

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
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="mt-6">
                    @if($activeTab === 'resumen')
                        <!-- Resumen Presupuestario -->
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
                                        
                                        foreach($fuentes as $fuente) {
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
                                                if ($porcentajeUsado >= 95) {
                                                    $estadoClase = 'bg-red-500';
                                                    $estadoTexto = 'Crítico';
                                                    $colorTexto = 'text-red-600 dark:text-red-400';
                                                } elseif ($porcentajeUsado >= 80) {
                                                    $estadoClase = 'bg-yellow-500';
                                                    $estadoTexto = 'Alerta';
                                                    $colorTexto = 'text-yellow-600 dark:text-yellow-400';
                                                } elseif ($porcentajeUsado >= 50) {
                                                    $estadoClase = 'bg-blue-500';
                                                    $estadoTexto = 'En Uso';
                                                    $colorTexto = 'text-blue-600 dark:text-blue-400';
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
                                                <button wire:click="crearTechoParaUe({{ $ue->id }})"
                                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 dark:bg-indigo-900 dark:text-indigo-300 dark:hover:bg-indigo-800 transition-colors">
                                                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                    Asignar Techo
                                                </button>
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
                                                            <button wire:click="edit({{ $idUe }})"
                                                                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                                                Editar
                                                            </button>
                                                            <button wire:click="eliminarTodosLosTechos({{ $idUe }})"
                                                                class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                                                Eliminar
                                                            </button>
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