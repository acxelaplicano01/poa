<div>
    <div class="mx-auto rounded-lg mt-8 sm:mt-6 lg:mt-4 mb-6">
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow sm:rounded-lg p-4 sm:p-6">
            
            {{-- Encabezado --}}
            <div class="mb-6 pb-4 border-b border-zinc-200 dark:border-zinc-700 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-zinc-800 dark:text-zinc-200">
                        Gestión de Actividades
                    </h2>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-2">
                        Administre las actividades del departamento vinculadas al PEI
                    </p>
                </div>
                <x-spinner-button wire:click="crear" loadingTarget="crear" :loadingText="__('Abriendo...')" class="{{ !$puedeCrearActividades ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}" :disabled="!$puedeCrearActividades">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nueva Actividad
                </x-spinner-button>
            </div>

            {{-- Alerta de plazo --}}
            @if(!$puedeCrearActividades && $mensajePlazo)
                <div class="mb-4 bg-amber-100 dark:bg-amber-900/30 border border-amber-400 dark:border-amber-700 text-amber-800 dark:text-amber-300 px-4 py-3 rounded relative" role="alert">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start flex-1">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="font-semibold">Planificación no disponible</p>
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
            @if($puedeCrearActividades && $diasRestantes !== null)
                <div class="mb-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-300 px-4 py-3 rounded-lg flex items-center justify-between" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <div>
                            <p class="font-semibold text-sm">Plazo de planificación activo</p>
                            <p class="text-xs mt-0.5">Puedes crear y editar actividades</p>
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

            {{-- Mensajes --}}
            @if (session()->has('message'))
                <div class="mb-4 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('message') }}</span>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Contexto del Usuario --}}
            @if(!empty($this->userContext))
            <div class="mb-6 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg p-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 text-xs">
                    <div>
                        <span class="text-indigo-700 dark:text-indigo-400 font-medium">POA:</span>
                        <span class="text-indigo-900 dark:text-indigo-200">{{ $this->userContext['poa']->anio . ' - ' . $this->userContext['poa']->name ?? 'POA ' . $this->userContext['poa']->anio }}</span>
                    </div>
                    <div>
                        <span class="text-indigo-700 dark:text-indigo-400 font-medium">Departamento:</span>
                        <span class="text-indigo-900 dark:text-indigo-200">{{ $this->userContext['departamento']->name }}</span>
                    </div>
                    <div>
                        <span class="text-indigo-700 dark:text-indigo-400 font-medium">Unidad Ejecutora:</span>
                        <span class="text-indigo-900 dark:text-indigo-200">{{ $this->userContext['unidadEjecutora']->name ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-indigo-700 dark:text-indigo-400 font-medium">Empleado:</span>
                        <span class="text-indigo-900 dark:text-indigo-200">{{ $this->userContext['empleado']->nombre }} {{ $this->userContext['empleado']->apellido }}</span>
                    </div>
                </div>
            </div>
            @endif

            {{-- Sistema de Tabs --}}
            <div class="mt-6">
                <!-- Tabs Header -->
                <div class="border-b border-zinc-200 dark:border-zinc-700">
                    <nav class="-mb-px flex space-x-2 sm:space-x-8 overflow-x-auto scrollbar-hide pb-px" aria-label="Tabs">
                        <button 
                            wire:click="setActiveTab('actividades')"
                            class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-xs sm:text-sm transition-colors duration-200 flex-shrink-0 {{ $activeTab === 'actividades' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 hover:border-zinc-300 dark:text-zinc-400 dark:hover:text-zinc-300' }}">
                            <span class="flex items-center">
                                <svg class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Actividades
                            </span>
                        </button>
                        <button 
                            wire:click="setActiveTab('resumen')"
                            class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-xs sm:text-sm transition-colors duration-200 flex-shrink-0 {{ $activeTab === 'resumen' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 hover:border-zinc-300 dark:text-zinc-400 dark:hover:text-zinc-300' }}">
                            <span class="flex items-center">
                                <svg class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Resumen Presupuestario
                            </span>
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="mt-6">
                    @if($activeTab === 'actividades')
                        {{-- Contenido del Tab Actividades --}}
                        {{-- Filtros --}}
            <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Buscar</label>
                    <x-input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar actividades..." class="w-full" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Estado</label>
                    <select wire:model.live="filtroEstado" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Todos los estados</option>
                        <option value="FORMULACION">Formulación</option>
                        <option value="REFORMULACION">Reformulación</option>
                        <option value="REVISION">Revisión</option>
                        <option value="APROBADO">Aprobado</option>
                        <option value="RECHAZADO">Rechazado</option>
                    </select>
                </div>
            </div>

            {{-- Tabla --}}
            <x-table 
                sort-field="{{ $sortField }}" 
                sort-direction="{{ $sortDirection }}" 
                :columns="[
                    ['key' => 'nombre', 'label' => 'Actividad', 'sortable' => true],
                    ['key' => 'tipo', 'label' => 'Tipo'],
                    ['key' => 'pei', 'label' => 'Vinculación PEI'],
                    ['key' => 'estado', 'label' => 'Estado'],
                    ['key' => 'actions', 'label' => 'Acciones', 'class' => 'text-right'],
                ]" 
                empty-message="No se encontraron actividades"
                class="mt-6">
                
                <x-slot name="desktop">
                    @forelse($actividades as $actividad)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $actividad->nombre }}
                                </div>
                                @if($actividad->descripcion)
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                                        {{ Str::limit($actividad->descripcion, 60) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-zinc-900 dark:text-zinc-100">
                                    {{ $actividad->tipo->tipo ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($actividad->resultado)
                                    <div class="text-sm text-zinc-900 dark:text-zinc-100">
                                        <span class="font-medium">Dimensión:</span> {{ $actividad->resultado->area->objetivo->dimension->nombre ?? 'N/A' }}
                                    </div>
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                                        <span class="font-medium">Resultado:</span> {{ Str::limit($actividad->resultado->nombre, 40) }}
                                    </div>
                                @else
                                    <span class="text-sm text-zinc-500 dark:text-zinc-400">Sin vincular</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($actividad->estado === 'FORMULACION')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                        Formulación
                                    </span>
                                @elseif($actividad->estado === 'REFORMULACION')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300">
                                        Reformulación
                                    </span>
                                @elseif($actividad->estado === 'REVISION')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300">
                                        Revisión
                                    </span>
                                @elseif($actividad->estado === 'APROBADO')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                        Aprobado
                                    </span>
                                @elseif($actividad->estado === 'RECHAZADO')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                        Rechazado
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-300">
                                        {{ $actividad->estado }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <a href="{{ route('gestionar-actividad', ['idActividad' => $actividad->id]) }}"
                                   class="inline-flex items-center text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-300 cursor-pointer"
                                   title="Gestionar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <button wire:click="editar({{ $actividad->id }})" 
                                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 cursor-pointer"
                                        title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                        <path fill-rule="evenodd"
                                            d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <button wire:click="confirmDelete({{ $actividad->id }})" 
                                        class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 cursor-pointer"
                                        title="Eliminar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-zinc-500 dark:text-zinc-400">
                                No se encontraron actividades
                            </td>
                        </tr>
                    @endforelse
                </x-slot>

                <x-slot name="mobile">
                    @forelse($actividades as $actividad)
                        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4 mb-4">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $actividad->nombre }}</h3>
                                    @if($actividad->descripcion)
                                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">
                                            {{ Str::limit($actividad->descripcion, 80) }}
                                        </p>
                                    @endif
                                </div>
                                @if($actividad->estado === 'FORMULACION')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                        Formulación
                                    </span>
                                @elseif($actividad->estado === 'REFORMULACION')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300">
                                        Reformulación
                                    </span>
                                @elseif($actividad->estado === 'REVISION')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300">
                                        Revisión
                                    </span>
                                @elseif($actividad->estado === 'APROBADO')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                        Aprobado
                                    </span>
                                @elseif($actividad->estado === 'RECHAZADO')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                        Rechazado
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-300">
                                        {{ $actividad->estado }}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="text-sm text-zinc-600 dark:text-zinc-400 space-y-2 mb-3">
                                <div>
                                    <span class="font-medium">Tipo:</span> {{ $actividad->tipo->tipo ?? 'N/A' }}
                                </div>
                                @if($actividad->resultado)
                                    <div>
                                        <span class="font-medium">Dimensión:</span> {{ $actividad->resultado->area->objetivo->dimension->nombre ?? 'N/A' }}
                                    </div>
                                    <div>
                                        <span class="font-medium">Resultado:</span> {{ Str::limit($actividad->resultado->nombre, 50) }}
                                    </div>
                                @endif
                            </div>

                            <div class="flex justify-end items-center pt-3 border-t border-zinc-200 dark:border-zinc-700 space-x-2">
                                <button wire:click="editar({{ $actividad->id }})" 
                                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 text-sm font-medium">
                                    Editar
                                </button>
                                <button wire:click="confirmDelete({{ $actividad->id }})" 
                                        class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 text-sm font-medium">
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-zinc-500 dark:text-zinc-400">
                            No se encontraron actividades
                        </div>
                    @endforelse
                </x-slot>

                <x-slot name="footer">
                    {{ $actividades->links() }}
                </x-slot>
            </x-table>

                    @elseif($activeTab === 'resumen')
                        {{-- Contenido del Tab Resumen Presupuestario --}}
                        @if($resumenPresupuesto->count() > 0)
                            <!-- Resumen General -->
                            <div class="bg-gradient-to-r from-indigo-50 to-indigo-50 dark:from-indigo-900/20 dark:to-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-indigo-900 dark:text-indigo-100 mb-4">
                                    Resumen General del Departamento
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
                                            L {{ number_format($totalGeneral, 2) }}
                                        </div>
                                        <div class="text-sm text-zinc-500 dark:text-zinc-400">Techo Asignado</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                            L {{ number_format($asignadoGeneral, 2) }}
                                        </div>
                                        <div class="text-sm text-zinc-500 dark:text-zinc-400">En Actividades</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold {{ $disponibleGeneral > 0 ? 'text-green-600' : 'text-red-600' }} dark:{{ $disponibleGeneral > 0 ? 'text-green-400' : 'text-red-400' }}">
                                            L {{ number_format($disponibleGeneral, 2) }}
                                        </div>
                                        <div class="text-sm text-zinc-500 dark:text-zinc-400">Disponible</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">
                                            {{ number_format($porcentajeGeneral, 1) }}%
                                        </div>
                                        <div class="text-sm text-zinc-500 dark:text-zinc-400">% Utilizado</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Detalle por Fuente de Financiamiento -->
                            <div class="mt-6">
                                <h3 class="text-lg font-semibold text-zinc-800 dark:text-zinc-200 mb-4">
                                    Detalle por Fuente de Financiamiento
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($resumenPresupuesto as $fuente)
                                        <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg p-6 hover:shadow-lg transition-shadow duration-200">
                                            <div class="flex items-center justify-between mb-4">
                                                <h4 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">
                                                    {{ $fuente['identificador'] }} - {{ Str::limit($fuente['fuente'], 25) }}
                                                </h4>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $fuente['estado']['clase'] }} text-white">
                                                    {{ $fuente['estado']['texto'] }}
                                                </span>
                                            </div>
                                            
                                            
                                            <div class="space-y-3">
                                                <div>
                                                    <div class="flex justify-between items-center mb-1">
                                                        <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Techo</span>
                                                        <span class="text-sm font-bold text-zinc-900 dark:text-zinc-100">L {{ number_format($fuente['montoTotal'], 2) }}</span>
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <div class="flex justify-between items-center mb-1">
                                                        <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Asignado</span>
                                                        <span class="text-sm font-bold text-green-600 dark:text-green-400">L {{ number_format($fuente['montoAsignado'], 2) }}</span>
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <div class="flex justify-between items-center mb-1">
                                                        <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Disponible</span>
                                                        <span class="text-sm font-bold {{ $fuente['montoDisponible'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                            L {{ number_format($fuente['montoDisponible'], 2) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                <div class="mt-4">
                                                    <div class="flex justify-between items-center mb-2">
                                                        <span class="text-xs font-medium text-zinc-600 dark:text-zinc-400">Uso del presupuesto</span>
                                                        <span class="text-xs font-bold text-zinc-900 dark:text-zinc-100">{{ number_format($fuente['porcentajeUsado'], 1) }}%</span>
                                                    </div>
                                                    <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-2">
                                                        <div class="{{ $fuente['estado']['clase'] }} h-2 rounded-full transition-all duration-300" 
                                                             style="width: {{ min($fuente['porcentajeUsado'], 100) }}%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-8 text-center">
                                <svg class="mx-auto h-12 w-12 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">Sin información presupuestaria</h3>
                                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                                    No hay techos presupuestarios asignados a este departamento.
                                </p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- Incluir modales --}}
    @include('livewire.actividad.partials.modal-actividad')
    @include('livewire.actividad.partials.modal-delete')
</div>