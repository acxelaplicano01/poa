<div>
    <div class="mx-auto rounded-lg mt-8 sm:mt-6 lg:mt-4 mb-6">
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow sm:rounded-lg p-4 sm:p-6">

            <!-- Encabezado con información del POA -->
            <div class="mb-6 pb-4 border-b border-zinc-200 dark:border-zinc-700">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                    <div>
                        <a href="{{ route('asignacionpresupuestaria') }}" class="inline-flex items-center text-indigo-600 dark:text-indigo-400  mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Volver a POAs
                        </a>
                        <h2 class="text-xl font-semibold text-zinc-800 dark:text-zinc-200">
                            Techos Presupuestarios por Departamento
                        </h2>
                        <div class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                            <p>POA: <span class="font-semibold text-zinc-700 dark:text-zinc-300">{{ $poa->anio ?? 'N/A' }}</span></p>
                            <p>Unidad Ejecutora: <span class="font-semibold text-zinc-700 dark:text-zinc-300">{{ $unidadEjecutora->name ?? 'N/A' }}</span></p>
                        </div>
                    </div>

                    <div class="flex justify-end mt-4 sm:mt-0">
                        <x-button wire:click="create()" class="w-full sm:w-auto justify-center" :disabled="!$puedeAsignarPresupuesto">
                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            {{ __('Nuevo Techo Departamental') }}
                        </x-button>
                    </div>
                </div>
            </div>

            {{-- Alerta de plazo --}}
            @if(!$puedeAsignarPresupuesto && $mensajePlazo)
                <div class="mb-4 bg-amber-100 dark:bg-amber-900/30 border border-amber-400 dark:border-amber-700 text-amber-800 dark:text-amber-300 px-4 py-3 rounded relative" role="alert">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start flex-1">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="font-semibold">Asignación departamental no disponible</p>
                                <p class="text-sm mt-1">{{ $mensajePlazo }}</p>
                            </div>
                        </div>
                        <!-- <a href="{{ route('plazos') }}" class="ml-4 flex-shrink-0 px-3 py-2 bg-amber-600 hover:bg-amber-700 dark:bg-amber-700 dark:hover:bg-amber-800 text-white text-sm font-medium rounded-md transition-colors flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Configurar Plazos
                        </a> -->
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
                            <p class="font-semibold text-sm">Plazo de asignación departamental activo</p>
                            <p class="text-xs mt-0.5">Puedes asignar presupuesto a departamentos</p>
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
                                <span class="hidden sm:inline">Departamentos sin Techo</span> 
                                <span class="ml-1 sm:ml-2 bg-yellow-100 dark:bg-yellow-800 text-yellow-900 dark:text-yellow-100 py-0.5 px-1.5 sm:px-2.5 rounded-full text-xs font-medium">
                                    {{ $departamentosSinTecho->count() }}
                                </span>
                            </span>
                        </button>
                        <button 
                            wire:click="setActiveTab('con-asignacion')"
                            class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-xs sm:text-sm transition-colors duration-200 flex-shrink-0 {{ $activeTab === 'con-asignacion' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 hover:border-zinc-300 dark:text-zinc-400 dark:hover:text-zinc-300' }}">
                            <span class="flex items-center">
                                <span class="hidden sm:inline">Departamentos con Techo</span> 
                                <span class="ml-1 sm:ml-2 bg-green-100 dark:bg-green-800 text-green-900 dark:text-green-100 py-0.5 px-1.5 sm:px-2.5 rounded-full text-xs font-medium">
                                    {{ $departamentosConTecho->count() }}
                                </span>
                            </span>
                        </button>
                        <button 
                            wire:click="setActiveTab('por-estructura')"
                            class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-xs sm:text-sm transition-colors duration-200 flex-shrink-0 {{ $activeTab === 'por-estructura' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 hover:border-zinc-300 dark:text-zinc-400 dark:hover:text-zinc-300' }}">
                            <span class="flex items-center">
                                <span class="hidden sm:inline">Por Estructura</span>
                                <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                </svg>
                            </span>
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="mt-6">
                    @if($activeTab === 'resumen')
                        <!-- Resumen Presupuestario -->
                        @if($resumenPresupuesto->count() > 0)
                            <!-- Resumen General -->
                            <div class="mt-4 bg-gradient-to-r from-indigo-50 to-indigo-50 dark:from-indigo-900/20 dark:to-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-indigo-900 dark:text-indigo-100 mb-4">
                                    Resumen General
                                </h3>
                                
                                @php
                                    $totalGeneral = $resumenPresupuesto->sum('montoTotal');
                                    $asignadoGeneral = $resumenPresupuesto->sum('montoAsignado');
                                    $disponibleGeneral = $resumenPresupuesto->sum('montoDisponible');
                                    $porcentajeGeneral = $totalGeneral > 0 ? ($asignadoGeneral / $totalGeneral) * 100 : 0;
                                @endphp
                                
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                            {{ number_format($totalGeneral, 2) }}
                                        </div>
                                        <div class="text-sm text-zinc-500 dark:text-zinc-400">Techo</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                            {{ number_format($asignadoGeneral, 2) }}
                                        </div>
                                        <div class="text-sm text-zinc-500 dark:text-zinc-400">Asignado</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold {{ $disponibleGeneral > 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ number_format($disponibleGeneral, 2) }}
                                        </div>
                                        <div class="text-sm text-zinc-500 dark:text-zinc-400">Disponible</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">
                                            {{ number_format($porcentajeGeneral, 1) }}%
                                        </div>
                                        <div class="text-sm text-zinc-500 dark:text-zinc-400">% de uso</div>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($resumenPresupuesto as $fuente)
                                    <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg p-6 hover:shadow-lg transition-shadow duration-200">
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">
                                                {{ $fuente['identificador'] }} - {{ $fuente['fuente'] }}
                                            </h3>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $fuente['estado']['clase'] }} text-white">
                                                {{ $fuente['estado']['texto'] }}
                                            </span>
                                        </div>
                                        
                                        <div class="space-y-3">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-zinc-500 dark:text-zinc-400">Techo:</span>
                                                <span class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                                    {{ number_format($fuente['montoTotal'], 2) }}
                                                </span>
                                            </div>
                                            
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-zinc-500 dark:text-zinc-400">Asignado:</span>
                                                <span class="text-sm font-semibold {{ $fuente['estado']['color'] }}">
                                                    {{ number_format($fuente['montoAsignado'], 2) }}
                                                </span>
                                            </div>
                                            
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-zinc-500 dark:text-zinc-400">Disponible:</span>
                                                <span class="text-sm font-semibold {{ $fuente['montoDisponible'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ number_format($fuente['montoDisponible'], 2) }}
                                                </span>
                                            </div>
                                            
                                            <!-- Barra de Progreso -->
                                            <div class="mt-4">
                                                <div class="flex justify-between text-xs text-zinc-500 dark:text-zinc-400 mb-1">
                                                    <span>Uso del Presupuesto</span>
                                                    <span>{{ number_format($fuente['porcentajeUsado'], 1) }}%</span>
                                                </div>
                                                <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-2.5">
                                                    <div class="{{ $fuente['estado']['clase'] }} h-2.5 rounded-full transition-all duration-300" 
                                                         style="width: {{ min($fuente['porcentajeUsado'], 100) }}%">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="max-w-md mx-auto">
                                    <div class="mx-auto h-16 w-16 text-zinc-400 mb-6">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                            <path stroke-linecap="round" stroke-linejoin="round" 
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-3">
                                        No hay techos presupuestarios configurados
                                    </h3>
                                    <p class="text-zinc-500 dark:text-zinc-400">
                                        Configure primero los techos por unidad ejecutora para ver el resumen presupuestario.
                                    </p>
                                </div>
                            </div>
                        @endif

                    @elseif($activeTab === 'sin-asignar')
                        <!-- Lista de Departamentos sin Techo Asignado -->
                        <div class="mb-6">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-3 sm:space-y-0">
                                <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">
                                    Departamentos sin Techo Asignado
                                </h3>
                                <div class="flex items-center space-x-2">
                                    <div class="relative">
                                        <x-input wire:model.live="searchSinTecho" type="text" placeholder="Buscar departamento..."
                                            class="pl-10 pr-10 py-2 w-full sm:w-64" />
                                        <div class="absolute left-3 top-2.5">
                                            <svg class="h-5 w-5 text-zinc-500 dark:text-zinc-400" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        @if($searchSinTecho)
                                            <button wire:click="$set('searchSinTecho', '')" class="absolute right-3 top-2.5 text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if($departamentosSinTecho->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($departamentosSinTecho as $departamento)
                                    <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                                    {{ $departamento->name }}
                                                </h3>
                                                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                                                    {{ $departamento->siglas }}
                                                </p>
                                                <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                                    {{ $departamento->tipo }}
                                                </p>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <x-button 
                                                    wire:click="createForDepartment({{ $departamento->id }})" :disabled="!$puedeAsignarPresupuesto"
                                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm font-medium">
                                                     <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                    Asignar
                                                </x-button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                @if($searchSinTecho)
                                    <div class="mx-auto h-16 w-16 text-zinc-400 mb-6">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                            <path stroke-linecap="round" stroke-linejoin="round" 
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-3">
                                        No se encontraron departamentos
                                    </h3>
                                    <p class="text-zinc-500 dark:text-zinc-400">
                                        No hay departamentos sin techo que coincidan con la búsqueda "{{ $searchSinTecho }}".
                                    </p>
                                @else
                                    <div class="mx-auto h-16 w-16 text-green-400 mb-6">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                            <path stroke-linecap="round" stroke-linejoin="round" 
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-3">
                                        ¡Excelente! Todos los departamentos tienen techos asignados
                                    </h3>
                                    <p class="text-zinc-500 dark:text-zinc-400">
                                        Todos los departamentos de esta unidad ejecutora ya tienen techos presupuestarios asignados.
                                    </p>
                                @endif
                            </div>
                        @endif

                    @elseif($activeTab === 'con-asignacion')
                        <!-- Lista de Departamentos con Techo Asignado -->
                        <div class="mb-6">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-3 sm:space-y-0">
                                <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">
                                    Departamentos con Techo Asignado
                                </h3>
                                <div class="flex items-center space-x-2">
                                    <div class="relative">
                                        <x-input wire:model.live="searchConTecho" type="text" placeholder="Buscar departamento..."
                                            class="pl-10 pr-10 py-2 w-full sm:w-64" />
                                        <div class="absolute left-3 top-2.5">
                                            <svg class="h-5 w-5 text-zinc-500 dark:text-zinc-400" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        @if($searchConTecho)
                                            <button wire:click="$set('searchConTecho', '')" class="absolute right-3 top-2.5 text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if($techoDeptos->count() > 0)
                            <x-table
                                :show-mobile="true"
                                :columns="[
                                    ['key' => 'departamento', 'label' => 'Departamento'],
                                    ['key' => 'fuentes', 'label' => 'Fuentes de Financiamiento'],
                                    ['key' => 'monto_total', 'label' => 'Monto Total', 'class' => 'text-center'],
                                    ['key' => 'actions', 'label' => 'Acciones', 'class' => 'text-center'],
                                ]"
                                empty-message="No se encontraron techos departamentales"
                                class="mt-6"
                            >
                                <x-slot name="desktop">
                                    @foreach($techosAgrupadosPorDepto as $departamentoId => $techosDepartamento)
                                        @php
                                            $primerTecho = $techosDepartamento->first();
                                            $montoTotal = $techosDepartamento->sum('monto');
                                        @endphp
                                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/60 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                                                            <svg class="h-5 w-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h6m-6 4h6m-6 4h6" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                            {{ $primerTecho->departamento->name ?? 'N/A' }}
                                                        </div>
                                                        <div class="text-sm text-zinc-500 dark:text-zinc-400">
                                                            {{ $primerTecho->departamento->siglas ?? '' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="space-y-1">
                                                    @foreach($techosDepartamento as $techo)
                                                        <div class="flex items-center justify-between text-sm">
                                                            <span class="text-zinc-900 dark:text-zinc-100">
                                                                {{ $techo->techoUE->fuente->nombre ?? 'Sin fuente' }}
                                                            </span>
                                                            <span class="font-medium text-zinc-600 dark:text-zinc-400">
                                                                {{ number_format($techo->monto, 2) }}
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                                    {{ number_format($montoTotal, 2) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="flex justify-center space-x-2">
                                                    <button wire:click="editDepartment({{ $departamentoId }})"
                                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 cursor-pointer">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                            fill="currentColor">
                                                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                            <path fill-rule="evenodd"
                                                                d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                    <button wire:click="confirmDeleteDepartment({{ $departamentoId }})"
                                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 cursor-pointer">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                            fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </x-slot>

                                <x-slot name="mobile">
                                    @foreach($techosAgrupadosPorDepto as $departamentoId => $techosDepartamento)
                                        @php
                                            $primerTecho = $techosDepartamento->first();
                                            $montoTotal = $techosDepartamento->sum('monto');
                                        @endphp
                                        <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg p-4 shadow-sm">
                                            <div class="flex items-start justify-between mb-3">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center mr-3">
                                                        <svg class="h-4 w-4 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h6m-6 4h6m-6 4h6" />
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                                            {{ $primerTecho->departamento->name ?? 'N/A' }}
                                                        </h3>
                                                        <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                                            {{ $primerTecho->departamento->siglas ?? '' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="space-y-3 mb-4">
                                                <div class="bg-zinc-50 dark:bg-zinc-700/50 rounded-lg p-3">
                                                    <h4 class="text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-2">Fuentes de Financiamiento:</h4>
                                                    <div class="space-y-1">
                                                        @foreach($techosDepartamento as $techo)
                                                            <div class="flex justify-between text-xs">
                                                                <span class="text-zinc-600 dark:text-zinc-400">
                                                                    {{ $techo->techoUE->fuente->nombre ?? 'Sin fuente' }}
                                                                </span>
                                                                <span class="font-medium text-zinc-900 dark:text-zinc-100">
                                                                    {{ number_format($techo->monto, 2) }}
                                                                </span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                
                                                <div class="flex justify-between border-t border-zinc-200 dark:border-zinc-600 pt-2">
                                                    <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Total:</span>
                                                    <span class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                                        {{ number_format($montoTotal, 2) }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div class="flex space-x-2">
                                                <button wire:click="editDepartment({{ $departamentoId }})"
                                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-transparent text-xs font-medium rounded  bg-yellow-400 hover:bg-yellow-500  transition-colors duration-150">
                                                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Editar
                                                </button>
                                                 

                                                <button wire:click="confirmDeleteDepartment({{ $departamentoId }})"
                                                    class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-md transition-colors"
                                                    title="Eliminar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </x-slot>

                                <x-slot name="footer">
                                    {{ $techoDeptos->links() }}
                                </x-slot>
                            </x-table>
                        @else
                            <div class="text-center py-12">
                                <div class="max-w-md mx-auto">
                                    @if($searchConTecho)
                                        <div class="mx-auto h-16 w-16 text-zinc-400 mb-6">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                                <path stroke-linecap="round" stroke-linejoin="round" 
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-3">
                                            No se encontraron departamentos
                                        </h3>
                                        <p class="text-zinc-500 dark:text-zinc-400">
                                            No hay departamentos con techo que coincidan con la búsqueda "{{ $searchConTecho }}".
                                        </p>
                                    @else
                                        <div class="mx-auto h-16 w-16 text-indigo-400 mb-6">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                                <path stroke-linecap="round" stroke-linejoin="round" 
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-3">
                                            No hay techos departamentales asignados
                                        </h3>
                                        <p class="text-zinc-500 dark:text-zinc-400 mb-8">
                                            Empieza asignando techos presupuestarios a los departamentos para gestionar el presupuesto.
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @elseif($activeTab === 'por-estructura')
                        <!-- Métricas por Estructura en Tabla -->
                        @if($metricasPorEstructura->count() > 0)
                            <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                                        <thead class="bg-zinc-50 dark:bg-zinc-800">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                                    Estructura
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                                    Departamentos
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                                    Monto Total
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                                    Promedio
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                                    Acciones
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-800">
                                            @foreach($metricasPorEstructura as $index => $estructura)
                                                <!-- Fila principal de la estructura -->
                                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/60">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center mr-3">
                                                                <svg class="h-4 w-4 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                                                </svg>
                                                            </div>
                                                            <div>
                                                                <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                                    {{ $estructura['estructura'] }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                            {{ $estructura['cantidad_departamentos'] }} depto(s)
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                                            {{ number_format($estructura['monto_total_asignado'], 2) }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-zinc-500 dark:text-zinc-400">
                                                            {{ number_format($estructura['promedio_por_departamento'], 2) }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                                        <button  
                                                            wire:click="verDetalleEstructura('{{ $estructura['estructura']}}')"
                                                            class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 dark:bg-indigo-900 dark:text-indigo-200 dark:hover:bg-indigo-800 transition-colors duration-150">
                                                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                            Ver Detalles
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
                                <div class="max-w-md mx-auto">
                                    <div class="mx-auto h-16 w-16 text-zinc-400 mb-6">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                            <path stroke-linecap="round" stroke-linejoin="round" 
                                                d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-3">
                                        No hay asignaciones por estructura
                                    </h3>
                                    <p class="text-zinc-500 dark:text-zinc-400">
                                        Aún no se han realizado asignaciones presupuestarias a departamentos.
                                    </p>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear/editar techo departamental -->
    @include('livewire.techo-deptos.create')

    <!-- Modal de confirmación de eliminación -->
    @include('livewire.techo-deptos.delete-confirmation')
</div>
