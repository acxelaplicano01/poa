<div>
    <div class="mx-auto rounded-lg mt-8 sm:mt-6 lg:mt-4 mb-6">
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow sm:rounded-lg p-4 sm:p-6">

            @if (session()->has('message'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md" role="alert">
                    <div class="flex justify-between items-center">
                        <p class="font-medium">{{ session('message') }}</p>
                    </div>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md" role="alert">
                    <div class="flex justify-between items-center">
                        <p class="font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <div class="mb-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <h2 class="text-xl font-semibold text-zinc-800 dark:text-zinc-200">
                        {{ __('Administración de POAs') }}
                    </h2>

                    <div class="flex flex-col sm:flex-row w-full sm:w-auto space-y-2 sm:space-y-0 sm:space-x-3">
                        <div class="relative w-full sm:w-auto">
                            <x-input wire:model.live="search" type="text" placeholder="Buscar POAs..."
                                class="w-full pl-10 pr-4 py-2" />
                            <div class="absolute left-3 top-2.5">
                                <svg class="h-5 w-5 text-zinc-500 dark:text-zinc-400" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="w-full sm:w-auto">
                            <x-select 
                                id="filtroAnio" 
                                wire:model.live="filtroAnio"
                                :options="array_merge([['value' => 'todos', 'text' => 'Todos los años']], $anios->map(fn($anio) => ['value' => $anio, 'text' => $anio])->toArray())"
                                class="w-full"
                            />
                        </div>
                        @can('consola.asignacionnacionalpresupuestaria.crear')
                        <x-spinner-button wire:click="create()"
                            loadingTarget="create" 
                            :loadingText="__('Abriendo...')"
                            class="flex w-full sm:w-auto justify-center">
                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            {{ __('Nuevo POA') }}
                        </x-spinner-button>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Grid de tarjetas de POAs -->
            <div class="mt-6">
                @if($poas->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-4 sm:gap-6">
                        @foreach($poas as $poa)
                            @php
                                // Determinar estado del POA
                                $anioActual = (int) date('Y');
                                $anioPoa = (int) ($poa->anio ?? 0);
                                $estadoPoa = 'pasado';
                                if ($anioPoa == $anioActual) $estadoPoa = 'actual';
                                elseif ($anioPoa > $anioActual) $estadoPoa = 'proximo';
                            @endphp
                            <div class="bg-gradient-to-br {{ $estadoPoa === 'actual' ? 'from-indigo-700 to-purple-700 dark:from-indigo-900 dark:to-purple-900' : ($estadoPoa === 'proximo' ? 'from-emerald-600 to-teal-600 dark:from-emerald-800 dark:to-teal-800' : 'from-zinc-500 to-zinc-600 dark:from-zinc-700 dark:to-zinc-800') }} rounded-lg shadow-lg overflow-hidden text-white hover:shadow-xl transition-all duration-200 cursor-pointer relative group p-5 {{ $estadoPoa === 'pasado' ? 'opacity-75' : '' }}">
                                <div wire:click="gestionarTechoUeNacional({{ $poa->id }})">
                                    <div class="absolute top-2 right-2 {{ $estadoPoa === 'pasado' ? 'bg-zinc-600 hover:bg-zinc-700' : ($estadoPoa === 'actual' ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-emerald-600 hover:bg-emerald-700') }} text-white text-xs font-bold px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                        Gestionar Presupuesto UEs
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-6xl font-extrabold">{{ $poa->anio }}</h3>
                                        @if($estadoPoa === 'actual')
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-white text-indigo-600">
                                                Actual
                                            </span>
                                        @elseif($estadoPoa === 'proximo')
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-white text-emerald-600">
                                                Próximo
                                            </span>
                                        @else
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-300 text-gray-800">
                                                Histórico
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="mt-4 flex flex-col space-y-2 text-sm {{ $estadoPoa === 'pasado' ? 'text-zinc-200' : ($estadoPoa === 'actual' ? 'text-indigo-50' : 'text-emerald-50') }}">
                                        <div class="flex items-center justify-between">
                                            <span>Institución:</span>
                                            <span class="font-semibold truncate ml-2" title="{{ $poa->institucion->nombre ?? 'N/A' }}">
                                                {{ Str::limit($poa->institucion->nombre ?? 'N/A', 15) }}
                                            </span>
                                        </div>
                                        
                                        <div class="flex items-center justify-between">
                                            <span>Presupuesto:</span>
                                            <span class="font-semibold">
                                                @php
                                                    // Sumar solo techos globales (sin UE específica)
                                                    $techosGlobales = $poa->techoUes->whereNull('idUE');
                                                    $totalGlobal = $techosGlobales->sum('monto');
                                                @endphp
                                                @if($totalGlobal > 0)
                                                    L. {{ number_format($totalGlobal, 2) }} 
                                                @else
                                                    No asignado
                                                @endif
                                            </span>
                                        </div>
                                        
                                    </div>
                                    
                                    <!-- Barra de progreso mejorada -->
                                    <div class="mt-3 space-y-2">
                                        <div class="flex items-center justify-between text-xs {{ $poa->activo ? 'text-indigo-50' : 'text-zinc-200' }}">
                                            <span>Progreso de Asignación</span>
                                            <span class="font-semibold">{{ $poa->progreso_departamentos['porcentaje'] }}%</span>
                                        </div>
                                        <div class="w-full {{ $poa->activo ? 'bg-indigo-200 bg-opacity-30' : 'bg-zinc-300 bg-opacity-30' }} rounded-full h-2 overflow-hidden">
                                            <div class="h-2 rounded-full transition-all duration-300 {{ $poa->progreso_departamentos['color'] }}" 
                                                 style="width: {{ $poa->progreso_departamentos['porcentaje'] }}%"
                                                 title="Departamentos con presupuesto: {{ $poa->progreso_departamentos['departamentos_con_presupuesto'] }}/{{ $poa->progreso_departamentos['total_departamentos'] }}">
                                            </div>
                                        </div>
                                        <div class="text-xs {{ $poa->activo ? 'text-indigo-50 opacity-75' : 'text-zinc-200 opacity-75' }}">
                                            {{ $poa->progreso_departamentos['departamentos_con_presupuesto'] }} de {{ $poa->progreso_departamentos['total_departamentos'] }} unidades ejecutoras con presupuesto
                                        </div>
                                    </div>
                                    
                                </div>
                                    @php
                                        $anioActual = now()->year;
                                        $poaVencido = $poa->anio < $anioActual;
                                    @endphp
                                    <div class="mt-5 flex space-x-2">
                                         @can('consola.asignacionnacionalpresupuestaria.editar')
                                        <button 
                                            wire:click="edit({{ $poa->id }})" 
                                            @if($poaVencido) disabled @endif
                                            class="flex-1 flex items-center justify-center px-3 py-2 {{ $poaVencido ? 'bg-zinc-400 cursor-not-allowed opacity-50' : 'bg-yellow-400 hover:bg-yellow-500' }} text-zinc-900 font-medium rounded-md transition-colors text-sm"
                                            @if($poaVencido) title="No se puede editar un POA de años anteriores" @endif>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                            </svg>
                                            <span>Editar</span>
                                        </button>
                                        @endcan
                                         @can('consola.asignacionnacionalpresupuestaria.eliminar')
                                        <button 
                                            wire:click="confirmDelete({{ $poa->id }})" 
                                            @if($poaVencido) disabled @endif
                                            class="px-3 py-2 {{ $poaVencido ? 'bg-zinc-400 cursor-not-allowed opacity-50' : 'bg-red-500 hover:bg-red-600' }} text-white font-medium rounded-md transition-colors"
                                            @if($poaVencido) title="No se puede eliminar un POA de años anteriores" @endif>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        @endcan
                                    </div>
                            </div>
                        @endforeach
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
                            @if($search || $filtroAnio !== 'todos')
                                <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-3">
                                    No se encontraron POAs
                                </h3>
                                <p class="text-zinc-500 dark:text-zinc-400 mb-8">
                                    No hay POAs que coincidan con los filtros aplicados. Intenta ajustar los filtros.
                                </p>
                                <div class="flex justify-center space-x-4">
                                    <x-button wire:click="clearFilters" variant="secondary">
                                        Limpiar filtros
                                    </x-button>
                                     @can('consola.asignacionnacionalpresupuestaria.crear')
                                    <x-button wire:click="create()" class="inline-flex bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700">
                                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                        Crear POA
                                    </x-button>
                                    @endcan
                                </div>
                            @else
                                <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-3">
                                    No hay POAs disponibles
                                </h3>
                                <p class="text-zinc-500 dark:text-zinc-400 mb-8">
                                    Comienza creando tu primer Plan Operativo Anual para gestionar tus proyectos y presupuestos.
                                </p>
                                 @can('consola.asignacionnacionalpresupuestaria.crear')
                                <x-button wire:click="create()" class="inline-flex bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700">
                                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Crear POA
                                </x-button>
                                @endcan
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Paginación -->
            @if($poas->hasPages())
                <div class="mt-6">
                    {{ $poas->links() }}
                </div>
            @endif
        </div>
    </div>

    @include('livewire.poa.poa-nacional.create')
    @include('livewire.poa.poa-nacional.delete-confirmation')
</div>
