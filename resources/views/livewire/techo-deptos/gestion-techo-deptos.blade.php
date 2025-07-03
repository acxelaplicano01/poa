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
                            <p>POA: <span class="font-semibold text-zinc-700 dark:text-zinc-300">{{ $poa->anio }}</span></p>
                            <p>Unidad Ejecutora: <span class="font-semibold text-zinc-700 dark:text-zinc-300">{{ $unidadEjecutora->name }}</span></p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row w-full sm:w-auto space-y-2 sm:space-y-0 sm:space-x-3 mt-4 sm:mt-0">
                        <div class="relative w-full sm:w-auto">
                            <x-input wire:model.live="search" type="text" placeholder="Buscar por departamento..."
                                class="w-full pl-10 pr-4 py-2" />
                            <div class="absolute left-3 top-2.5">
                                <svg class="h-5 w-5 text-zinc-500 dark:text-zinc-400" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        <x-button wire:click="create()" class="w-full sm:w-auto justify-center">
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
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button 
                            wire:click="setActiveTab('resumen')"
                            class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab === 'resumen' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 hover:border-zinc-300 dark:text-zinc-400 dark:hover:text-zinc-300' }}">
                            Resumen Presupuestario
                            <svg class="inline ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </button>
                        <button 
                            wire:click="setActiveTab('sin-asignar')"
                            class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab === 'sin-asignar' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 hover:border-zinc-300 dark:text-zinc-400 dark:hover:text-zinc-300' }}">
                            Departamentos sin Techo
                            <span class="ml-2 bg-yellow-100 dark:bg-yellow-800 text-yellow-900 dark:text-yellow-100 py-0.5 px-2.5 rounded-full text-xs font-medium">
                                {{ $departamentosSinTecho->count() }}
                            </span>
                        </button>
                        <button 
                            wire:click="setActiveTab('con-asignacion')"
                            class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab === 'con-asignacion' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 hover:border-zinc-300 dark:text-zinc-400 dark:hover:text-zinc-300' }}">
                            Departamentos con Techo
                            <span class="ml-2 bg-green-100 dark:bg-green-800 text-green-900 dark:text-green-100 py-0.5 px-2.5 rounded-full text-xs font-medium">
                                {{ $departamentosConTecho->count() }}
                            </span>
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="mt-6">
                    @if($activeTab === 'resumen')
                        <!-- Resumen Presupuestario -->
                        @if($resumenPresupuesto->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($resumenPresupuesto as $fuente)
                                    <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg p-6 hover:shadow-lg transition-shadow duration-200">
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">
                                                {{ $fuente['fuente'] }}
                                            </h3>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $fuente['estado']['clase'] }} text-white">
                                                {{ $fuente['estado']['texto'] }}
                                            </span>
                                        </div>
                                        
                                        <div class="space-y-3">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-zinc-500 dark:text-zinc-400">Total Asignado:</span>
                                                <span class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                                    {{ number_format($fuente['montoTotal'], 2) }}
                                                </span>
                                            </div>
                                            
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-zinc-500 dark:text-zinc-400">Distribuido:</span>
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
                            
                            <!-- Resumen General -->
                            <div class="mt-8 bg-gradient-to-r from-indigo-50 to-indigo-50 dark:from-indigo-900/20 dark:to-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg p-6">
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
                                            {{ number_format($totalGeneral, 0) }}
                                        </div>
                                        <div class="text-sm text-zinc-500 dark:text-zinc-400">Total</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                            {{ number_format($asignadoGeneral, 0) }}
                                        </div>
                                        <div class="text-sm text-zinc-500 dark:text-zinc-400">Distribuido</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold {{ $disponibleGeneral > 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ number_format($disponibleGeneral, 0) }}
                                        </div>
                                        <div class="text-sm text-zinc-500 dark:text-zinc-400">Disponible</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">
                                            {{ number_format($porcentajeGeneral, 1) }}%
                                        </div>
                                        <div class="text-sm text-zinc-500 dark:text-zinc-400">Ejecutado</div>
                                    </div>
                                </div>
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
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                    Sin Techo
                                                </span>
                                                <button 
                                                    wire:click="createForDepartment({{ $departamento->id }})"
                                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm font-medium">
                                                    Asignar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
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
                            </div>
                        @endif

                    @elseif($activeTab === 'con-asignacion')
                        <!-- Lista de Departamentos con Techo Asignado -->
                        @if($techoDeptos->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                                Departamento
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                                Techo UE / Fuente
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                                Monto
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                                Acciones
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-800">
                                        @foreach($techoDeptos as $techoDepto)
                                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/60">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                    {{ $techoDepto->departamento->name ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                                    @if ($techoDepto->techoUE && $techoDepto->techoUE->fuente)
                                                        {{ $techoDepto->techoUE->fuente->nombre }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                                    {{ number_format($techoDepto->monto, 2) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <div class="flex space-x-2">
                                                        <button wire:click="edit({{ $techoDepto->id }})"
                                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                                fill="currentColor">
                                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                                <path fill-rule="evenodd"
                                                                    d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                        <button wire:click="confirmDelete({{ $techoDepto->id }})"
                                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
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
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Paginación -->
                            <div class="mt-4">
                                {{ $techoDeptos->links() }}
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="max-w-md mx-auto">
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
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear/editar techo departamental -->
    <x-modal wire:model="showModal" maxWidth="2xl">
        <div class="px-6 py-4">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">
                {{ $isEditing ? 'Editar Techo Departamental' : 'Crear Nuevo Techo Departamental' }}
            </h3>
            
            <form wire:submit.prevent="save">            
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Departamento -->
                    @if($idDepartamento && !$isEditing)
                        <!-- Departamento preseleccionado (solo lectura) -->
                        <div class="md:col-span-2">
                            <x-label value="{{ __('Departamento Seleccionado') }}" class="mb-2" />
                            <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-md">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h6m-6 4h6m-6 4h6" />
                                    </svg>
                                    <span class="text-sm font-medium text-indigo-900 dark:text-indigo-100">
                                        {{ collect($departamentos)->firstWhere('id', $idDepartamento)?->name ?? 'Departamento seleccionado' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Select de departamento (para modo edición o creación libre) -->
                        <div>
                            <x-label for="idDepartamento" value="{{ __('Departamento') }}" class="mb-2" />
                            <x-select 
                                id="idDepartamento" 
                                wire:model.live="idDepartamento"
                                :options="collect($departamentos)->map(fn($depto) => ['value' => $depto->id, 'text' => $depto->name])->prepend(['value' => '', 'text' => 'Seleccione un departamento'])->toArray()"
                                class="mt-1 block w-full"
                            />
                            <x-input-error for="idDepartamento" class="mt-2" />
                        </div>
                    @endif

                    <!-- Techo UE / Fuente -->
                    <div class="{{ ($idDepartamento && !$isEditing) ? 'md:col-span-2' : '' }}">
                        <x-label for="idTechoUE" value="{{ __('Techo UE / Fuente') }}" class="mb-2" />
                        <x-select 
                            id="idTechoUE" 
                            wire:model="idTechoUE"
                            :options="collect($techoUes)->map(fn($techoUe) => ['value' => $techoUe->id, 'text' => $techoUe->fuente->nombre . ' - ' . number_format($techoUe->monto, 2)])->prepend(['value' => '', 'text' => 'Seleccione un Techo UE'])->toArray()"
                            class="mt-1 block w-full"
                        />
                        <x-input-error for="idTechoUE" class="mt-2" />
                    </div>

                    <!-- Monto -->
                    <div class="md:col-span-2">
                        <x-label for="monto" value="{{ __('Monto') }}" class="mb-2" />
                        <x-input 
                            id="monto" 
                            type="number"
                            step="0.01"
                            min="0"
                            wire:model="monto"
                            class="mt-1 block w-full"
                            placeholder="0.00"
                        />
                        <x-input-error for="monto" class="mt-2" />
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end mt-6 space-x-3">
                    <x-secondary-button wire:click="closeModal" type="button">
                        {{ __('Cancelar') }}
                    </x-secondary-button>
                    
                    <x-button type="submit" wire:loading.attr="disabled" class="flex items-center">
                        <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="save">
                            {{ $isEditing ? __('Actualizar') : __('Crear') }}
                        </span>
                        <span wire:loading wire:target="save">
                            {{ $isEditing ? __('Actualizando...') : __('Creando...') }}
                        </span>
                    </x-button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Modal de confirmación de eliminación -->
    @include('livewire.techo-deptos.delete-confirmation')
</div>
