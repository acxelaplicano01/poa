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
                <x-button wire:click="crear" :disabled="!$puedeCrearActividades">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nueva Actividad
                </x-button>
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
                        <option value="planificada">Planificada</option>
                        <option value="en_proceso">En Proceso</option>
                        <option value="completada">Completada</option>
                        <option value="cancelada">Cancelada</option>
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
                                    {{ $actividad->tipoActividad->tipo ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($actividad->resultado)
                                    <div class="text-sm text-zinc-900 dark:text-zinc-100">
                                        <span class="font-medium">Dimensión:</span> {{ $actividad->resultado->dimension->nombre ?? 'N/A' }}
                                    </div>
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                                        <span class="font-medium">Resultado:</span> {{ Str::limit($actividad->resultado->nombre, 40) }}
                                    </div>
                                @else
                                    <span class="text-sm text-zinc-500 dark:text-zinc-400">Sin vincular</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($actividad->estado === 'planificada')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                        Planificada
                                    </span>
                                @elseif($actividad->estado === 'en_proceso')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300">
                                        En Proceso
                                    </span>
                                @elseif($actividad->estado === 'completada')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                        Completada
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                        Cancelada
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <button wire:click="editar({{ $actividad->id }})" 
                                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                    Editar
                                </button>
                                <button wire:click="confirmDelete({{ $actividad->id }})" 
                                        class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                    Eliminar
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
                                @if($actividad->estado === 'planificada')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                        Planificada
                                    </span>
                                @elseif($actividad->estado === 'en_proceso')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300">
                                        En Proceso
                                    </span>
                                @elseif($actividad->estado === 'completada')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                        Completada
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                        Cancelada
                                    </span>
                                @endif
                            </div>
                            
                            <div class="text-sm text-zinc-600 dark:text-zinc-400 space-y-2 mb-3">
                                <div>
                                    <span class="font-medium">Tipo:</span> {{ $actividad->tipoActividad->tipo ?? 'N/A' }}
                                </div>
                                @if($actividad->resultado)
                                    <div>
                                        <span class="font-medium">Dimensión:</span> {{ $actividad->resultado->dimension->nombre ?? 'N/A' }}
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

        </div>
    </div>

    {{-- Incluir modales --}}
    @include('livewire.actividad.partials.modal-actividad')
    @include('livewire.actividad.partials.modal-delete')
</div>