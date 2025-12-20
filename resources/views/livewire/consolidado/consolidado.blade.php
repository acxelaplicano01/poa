<div>
        <div class=" mx-auto px-4 bg-white dark:bg-zinc-900 overflow-hidden shadow sm:rounded-lg p-4 sm:p-6">
        <!-- Header -->
        <div class="mb-3">
            <h2 class="text-2xl font-bold text-zinc-800 dark:text-zinc-200">
                Consolidado de Actividades
            </h2>
            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                Vista consolidada de todas las actividades por departamento
            </p>
        </div>

        <!-- Filtros -->
        <div class="bg-white dark:bg-zinc-900 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Filtro Año -->
                <div>
                    <label for="anio" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        Año
                    </label>
                    <select wire:model.live="anio" id="anio"
                        class="block w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach($anios as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro Dimensión -->
                <div>
                    <label for="dimension" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        Dimensión
                    </label>
                    <select wire:model.live="dimensionId" id="dimension"
                        class="block w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Todas las dimensiones</option>
                        @foreach($dimensiones as $dimension)
                            <option value="{{ $dimension->id }}">{{ $dimension->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro Departamento con buscador integrado -->
                <div x-data="{ 
                    open: false, 
                    search: '', 
                    get filteredDepartamentos() {
                        return this.search === '' 
                            ? $wire.departamentos 
                            : $wire.departamentos.filter(dept => 
                                dept.name.toLowerCase().includes(this.search.toLowerCase())
                            );
                    },
                    get selectedNombre() {
                        if (!$wire.departamentoId) return 'Todos los departamentos';
                        const dept = $wire.departamentos.find(d => d.id == $wire.departamentoId);
                        return dept ? dept.name : 'Todos los departamentos';
                    }
                }" class="relative">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        Departamento
                    </label>
                    
                    <!-- Input de búsqueda como selector principal -->
                    <div class="relative">
                        <input
                            x-model="search"
                            @focus="open = true"
                            @input="open = true"
                            type="text"
                            :placeholder="search === '' ? selectedNombre : 'Buscar departamento...'"
                            class="block w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            @keydown.escape.prevent="open = false"
                            @click="open = !open; $event.stopPropagation();"
                        />
                        <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                            <button type="button" @click="open = !open; $event.stopPropagation();"
                                class="text-zinc-600 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300 focus:outline-none">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Lista desplegable de departamentos -->
                    <div x-show="open" @click.away="open = false"
                        class="absolute z-50 mt-1 w-full bg-white border dark:border-zinc-700 dark:bg-zinc-800 shadow-lg rounded-md py-1 text-base overflow-auto focus:outline-none sm:text-sm"
                        style="display: none;"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95">
                        
                        <!-- Opción "Todos" -->
                        <button type="button" @click="$wire.departamentoId = ''; search = ''; open = false;"
                            class="w-full text-left px-4 py-2 text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700"
                            :class="{ 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-900 dark:text-indigo-200': $wire.departamentoId === '', 'text-zinc-900 dark:text-zinc-300': $wire.departamentoId !== '' }">
                            <span>Todos los departamentos</span>
                        </button>
                        
                        <!-- Lista de departamentos filtrados -->
                        <div class="max-h-60 overflow-y-auto">
                            <template x-for="departamento in filteredDepartamentos" :key="departamento.id">
                                <button type="button" @click="$wire.departamentoId = departamento.id; search = ''; open = false;"
                                    class="w-full text-left px-4 py-2 text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700"
                                    :class="{ 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-900 dark:text-indigo-200': $wire.departamentoId == departamento.id, 'text-zinc-900 dark:text-zinc-300': $wire.departamentoId != departamento.id }">
                                    <span x-text="departamento.name"></span>
                                </button>
                            </template>
                            
                            <!-- Mensaje cuando no hay resultados -->
                            <div 
                                x-show="filteredDepartamentos.length === 0" 
                                class="px-4 py-2 text-sm text-zinc-500 dark:text-zinc-400 text-center">
                                No se encontraron departamentos
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Actividades -->
        <x-table 
            :columns="[
                ['key' => 'detalles', 'label' => 'Detalles'],
                ['key' => 'correlativo', 'label' => 'Correlativo'],
                ['key' => 'actividad', 'label' => 'Actividad'],
                ['key' => 'departamento', 'label' => 'Departamento'],
                ['key' => 'spi', 'label' => 'Subido al SPI']
            ]"
            :sortField="''"
            :sortDirection="'asc'"
            :showMobile="true"
            class="bg-white dark:bg-zinc-900 rounded-lg shadow-sm overflow-hidden"
        >
            <x-slot name="desktop">
                @forelse($actividades as $actividad)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <button wire:click="toggleExpand({{ $actividad->id }})" 
                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-transform duration-200 cursor-pointer {{ $expandedRow === $actividad->id ? 'rotate-180' : '' }}">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300">
                                {{ $actividad->correlativo }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $actividad->nombre }}
                            </div>
                            @if($actividad->categoria)
                                <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                                    {{ $actividad->categoria->categoria }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-zinc-900 dark:text-zinc-100">
                                {{ $actividad->departamento->name ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <button wire:click="toggleSPI({{ $actividad->id }})" type="button"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 {{ $actividad->uploadedIntoSPI ? 'bg-green-600' : 'bg-zinc-200 dark:bg-zinc-600' }}"
                                    role="switch" aria-checked="{{ $actividad->uploadedIntoSPI ? 'true' : 'false' }}">
                                    <span class="sr-only">Subido al SPI</span>
                                    <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $actividad->uploadedIntoSPI ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                                <span class="text-sm font-medium {{ $actividad->uploadedIntoSPI ? 'text-green-600 dark:text-green-400' : 'text-zinc-500 dark:text-zinc-400' }}">
                                    {{ $actividad->uploadedIntoSPI ? 'Listo' : 'Pendiente' }}
                                </span>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Fila expandida con detalles -->
                    @if($expandedRow === $actividad->id && $actividadDetalle)
                    <tr>
                        <td colspan="6" class="py-4 bg-zinc-50 dark:bg-zinc-900">
                                    <div class="space-y-6">
                                        <!-- Datos Institucionales -->
                                        <div class="bg-white dark:bg-zinc-800 rounded-lg p-4 shadow-sm">
                                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4 flex items-center">
                                                <svg class="h-5 w-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                                Datos Institucionales
                                            </h3>
                                            <div class="space-y-3">
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Dimensión:</label>
                                                        <p class="text-sm text-zinc-900 dark:text-zinc-200 mt-1">
                                                            {{ $actividadDetalle->resultado->area->objetivo->dimension->nombre ?? 'N/A' }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-1">
                                                    <div>
                                                        <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Objetivo:</label>
                                                        <p class="text-sm text-zinc-900 dark:text-zinc-200 mt-1">
                                                            {{ $actividadDetalle->resultado->area->objetivo->nombre ?? 'N/A' }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-1">
                                                    <div>
                                                        <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Área:</label>
                                                        <p class="text-sm text-zinc-900 dark:text-zinc-200 mt-1">
                                                            {{ $actividadDetalle->resultado->area->nombre ?? 'N/A' }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-1">
                                                    <div>
                                                        <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Resultado Institucional:</label>
                                                        <p class="text-sm text-zinc-900 dark:text-zinc-200 mt-1">
                                                            {{ $actividadDetalle->resultado->nombre ?? 'N/A' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Datos Generales de la Actividad -->
                                        <div class="bg-white dark:bg-zinc-800 rounded-lg p-4 shadow-sm">
                                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4 flex items-center">
                                                <svg class="h-5 w-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                Datos Generales de la Actividad
                                            </h3>
                                            <div class="space-y-3">
                                                <div>
                                                    <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Resultado de actividad:</label>
                                                    <p class="text-sm text-zinc-900 dark:text-zinc-200 mt-1">
                                                        {{ $actividadDetalle->resultadoActividad ?? 'N/A' }}
                                                    </p>
                                                </div>
                                                <div>
                                                    <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Población objetivo:</label>
                                                    <p class="text-sm text-zinc-900 dark:text-zinc-200 mt-1">
                                                        {{ $actividadDetalle->poblacion_objetivo ?? 'N/A' }}
                                                    </p>
                                                </div>
                                                <div>
                                                    <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Medio Verificación:</label>
                                                    <p class="text-sm text-zinc-900 dark:text-zinc-200 mt-1">
                                                        {{ $actividadDetalle->medio_verificacion ?? 'N/A' }}
                                                    </p>
                                                </div>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Categoría:</label>
                                                        <p class="text-sm text-zinc-900 dark:text-zinc-200 mt-1">
                                                            {{ $actividadDetalle->categoria->categoria ?? 'N/A' }}
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Tipo de Actividad:</label>
                                                        <p class="text-sm text-zinc-900 dark:text-zinc-200 mt-1">
                                                            {{ $actividadDetalle->tipo->tipo ?? 'N/A' }}
                                                        </p>
                                                    </div>
                                                </div>
                                                
                                                <!-- Encargados de la actividad movidos aquí -->
                                                @if($actividadDetalle->empleados->count() > 0)
                                                <div class="mt-3 pt-3 border-t border-zinc-200 dark:border-zinc-700">
                                                    <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400 mb-3 block">Encargados de la actividad:</label>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                        @foreach($actividadDetalle->empleados as $empleado)
                                                        <div class="flex justify-between items-center p-2 bg-zinc-50 dark:bg-zinc-700 rounded">
                                                            <div>
                                                                <label class="text-xs text-zinc-500 dark:text-zinc-400">Empleado</label>
                                                                <p class="text-sm font-medium text-zinc-900 dark:text-zinc-200">{{ $empleado->nombre }} {{ $empleado->apellido }}</p>
                                                            </div>
                                                            <div class="text-right">
                                                                <label class="text-xs text-zinc-500 dark:text-zinc-400"># de Empleado</label>
                                                                <p class="text-sm font-medium text-zinc-900 dark:text-zinc-200">{{ $empleado->num_empleado ?? 'N/A' }}</p>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Indicadores y Planificaciones -->
                                        @if($actividadDetalle->indicadores->count() > 0)
                                        <div class="bg-white dark:bg-zinc-800 rounded-lg p-4 shadow-sm">
                                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4 flex items-center">
                                                <svg class="h-5 w-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                                </svg>
                                                Indicadores y Planificaciones
                                            </h3>
                                            <div class="space-y-4">
                                                @foreach($actividadDetalle->indicadores as $indicador)
                                                <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-4">
                                                    <div class="mb-3">
                                                        <div class="flex items-center justify-between mb-2">
                                                            <h4 class="font-medium text-zinc-900 dark:text-zinc-100">{{ $indicador->nombre }}</h4>
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $indicador->isPorcentaje ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300' : 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300' }}">
                                                                @if($indicador->isPorcentaje)
                                                                    %
                                                                    Porcentaje
                                                                @else
                                                                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                                                    </svg>
                                                                    Cantidad
                                                                @endif
                                                            </span>
                                                        </div>
                                                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">
                                                            @if($indicador->descripcion)
                                                                {{ $indicador->descripcion }}<br>
                                                            @endif
                                                            Meta Planificada: {{ $indicador->cantidadPlanificada ?? '0' }} | 
                                                            Cantidad Ejecutada: {{ $indicador->cantidadEjecutada ?? '0' }}
                                                            @if($indicador->promedioAlcanzado)
                                                                | Promedio: {{ $indicador->promedioAlcanzado }}%
                                                            @endif
                                                        </p>
                                                    </div>
                                                    @if($indicador->planificacions->count() > 0)
                                                    @php
                                                        // Agrupar planificaciones por trimestre
                                                        $trimestres = [
                                                            'T1' => ['meses' => [1, 2, 3], 'nombre' => 'Trimestre 1', 'planificado' => 0, 'ejecutado' => 0, 'fechaInicio' => null, 'fechaFin' => null],
                                                            'T2' => ['meses' => [4, 5, 6], 'nombre' => 'Trimestre 2', 'planificado' => 0, 'ejecutado' => 0, 'fechaInicio' => null, 'fechaFin' => null],
                                                            'T3' => ['meses' => [7, 8, 9], 'nombre' => 'Trimestre 3', 'planificado' => 0, 'ejecutado' => 0, 'fechaInicio' => null, 'fechaFin' => null],
                                                            'T4' => ['meses' => [10, 11, 12], 'nombre' => 'Trimestre 4', 'planificado' => 0, 'ejecutado' => 0, 'fechaInicio' => null, 'fechaFin' => null],
                                                        ];
                                                        
                                                        foreach($indicador->planificacions as $planificacion) {
                                                            $mesId = $planificacion->mes->id ?? null;
                                                            foreach($trimestres as $key => &$trimestre) {
                                                                if($mesId && in_array($mesId, $trimestre['meses'])) {
                                                                    $trimestre['planificado'] += $planificacion->cantidad ?? 0;
                                                                    // Sumar seguimientos ejecutados si existen
                                                                    $trimestre['ejecutado'] += $planificacion->seguimientos->sum('cantidad') ?? 0;
                                                                    
                                                                    // Establecer fechas (primera fecha inicio y última fecha fin)
                                                                    if($planificacion->fechaInicio && (!$trimestre['fechaInicio'] || $planificacion->fechaInicio < $trimestre['fechaInicio'])) {
                                                                        $trimestre['fechaInicio'] = $planificacion->fechaInicio;
                                                                    }
                                                                    if($planificacion->fechaFin && (!$trimestre['fechaFin'] || $planificacion->fechaFin > $trimestre['fechaFin'])) {
                                                                        $trimestre['fechaFin'] = $planificacion->fechaFin;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    @endphp
                                                    <div class="overflow-x-auto">
                                                        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700 text-sm">
                                                            <thead class="bg-zinc-50 dark:bg-zinc-900">
                                                                <tr>
                                                                    <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Trimestre</th>
                                                                    <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Fecha Inicio</th>
                                                                    <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Fecha Fin</th>
                                                                    <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Planificado</th>
                                                                    <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Ejecutado</th>
                                                                    <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">% Avance</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                                                                @foreach($trimestres as $trimestre)
                                                                @if($trimestre['planificado'] > 0 || $trimestre['ejecutado'] > 0)
                                                                <tr>
                                                                    <td class="px-3 py-2 text-zinc-900 dark:text-zinc-200 font-medium">
                                                                        {{ $trimestre['nombre'] }}
                                                                    </td>
                                                                    <td class="px-3 py-2 text-zinc-900 dark:text-zinc-200 text-xs">
                                                                        @if($trimestre['fechaInicio'])
                                                                            {{ \Carbon\Carbon::parse($trimestre['fechaInicio'])->format('d/m/Y') }}
                                                                        @else
                                                                            <span class="text-zinc-400">-</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="px-3 py-2 text-zinc-900 dark:text-zinc-200 text-xs">
                                                                        @if($trimestre['fechaFin'])
                                                                            {{ \Carbon\Carbon::parse($trimestre['fechaFin'])->format('d/m/Y') }}
                                                                        @else
                                                                            <span class="text-zinc-400">-</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="px-3 py-2 text-zinc-900 dark:text-zinc-200">
                                                                        {{ number_format($trimestre['planificado'], 2) }}
                                                                    </td>
                                                                    <td class="px-3 py-2 text-zinc-900 dark:text-zinc-200">
                                                                        {{ number_format($trimestre['ejecutado'], 2) }}
                                                                    </td>
                                                                    <td class="px-3 py-2">
                                                                        @php
                                                                            $porcentaje = $trimestre['planificado'] > 0 
                                                                                ? ($trimestre['ejecutado'] / $trimestre['planificado']) * 100 
                                                                                : 0;
                                                                            $colorClass = $porcentaje >= 80 
                                                                                ? 'text-green-600 dark:text-green-400' 
                                                                                : ($porcentaje >= 50 
                                                                                    ? 'text-yellow-600 dark:text-yellow-400' 
                                                                                    : 'text-red-600 dark:text-red-400');
                                                                        @endphp
                                                                        <span class="{{ $colorClass }} font-medium">
                                                                            {{ number_format($porcentaje, 1) }}%
                                                                        </span>
                                                                    </td>
                                                                </tr>
                                                                @endif
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    @endif
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif

                                        <!-- Tareas y Recursos -->
                                        @if($actividadDetalle->tareas->count() > 0)
                                        <div class="bg-white dark:bg-zinc-800 rounded-lg p-4 shadow-sm">
                                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4 flex items-center">
                                                <svg class="h-5 w-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                                </svg>
                                                Tareas y Recursos
                                            </h3>
                                            <div class="space-y-4">
                                                @foreach($actividadDetalle->tareas as $tarea)
                                                <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-4">
                                                    <div class="mb-3">
                                                        <div class="flex items-center justify-between">
                                                            <h4 class="font-medium text-zinc-900 dark:text-zinc-100">{{ $tarea->nombre }}</h4>
                                                            <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ $tarea->correlativo }}</span>
                                                        </div>
                                                        @if($tarea->descripcion)
                                                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">
                                                            {{ $tarea->descripcion }}
                                                        </p>
                                                        @endif
                                                    </div>
                                                    @if($tarea->presupuestos->count() > 0)
                                                    <div class="overflow-x-auto">
                                                        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700 text-xs">
                                                            <thead class="bg-zinc-50 dark:bg-zinc-900">
                                                                <tr>
                                                                    <th class="px-2 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Recurso</th>
                                                                    <th class="px-2 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Cantidad</th>
                                                                    <th class="px-2 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Costo Unit.</th>
                                                                    <th class="px-2 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Total</th>
                                                                    <th class="px-2 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Grupo Gasto</th>
                                                                    <th class="px-2 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Objeto</th>
                                                                    <th class="px-2 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Fuente</th>
                                                                    <th class="px-2 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Mes</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                                                                @foreach($tarea->presupuestos as $presupuesto)
                                                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700">
                                                                    <td class="px-2 py-2 text-zinc-900 dark:text-zinc-200">
                                                                        {{ $presupuesto->recurso ?? 'N/A' }}
                                                                        @if($presupuesto->detalle_tecnico)
                                                                            <br><span class="text-xs text-zinc-500">{{ $presupuesto->detalle_tecnico }}</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="px-2 py-2 text-zinc-900 dark:text-zinc-200 text-right">
                                                                        {{ number_format($presupuesto->cantidad ?? 0, 2) }}
                                                                    </td>
                                                                    <td class="px-2 py-2 text-zinc-900 dark:text-zinc-200 text-right">
                                                                        L {{ number_format($presupuesto->costounitario ?? 0, 2) }}
                                                                    </td>
                                                                    <td class="px-2 py-2 text-zinc-900 dark:text-zinc-200 font-medium text-right">
                                                                        L {{ number_format($presupuesto->total ?? 0, 2) }}
                                                                    </td>
                                                                    <td class="px-2 py-2 text-zinc-900 dark:text-zinc-200">
                                                                        {{ $presupuesto->grupoGasto->nombre ?? 'N/A' }}
                                                                        <br><span class="text-xs text-zinc-500">{{ $presupuesto->grupoGasto->codigo ?? '' }}</span>
                                                                    </td>
                                                                    <td class="px-2 py-2 text-zinc-900 dark:text-zinc-200">
                                                                        {{ $presupuesto->objetoGasto->nombre ?? 'N/A' }}
                                                                        <br><span class="text-xs text-zinc-500">{{ $presupuesto->objetoGasto->codigo ?? '' }}</span>
                                                                    </td>
                                                                    <td class="px-2 py-2 text-zinc-900 dark:text-zinc-200">
                                                                        {{ $presupuesto->fuente->nombre ?? 'N/A' }}
                                                                    </td>
                                                                    <td class="px-2 py-2 text-zinc-900 dark:text-zinc-200">
                                                                        {{ $presupuesto->mes->mes ?? 'N/A' }}
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                                <tr class="bg-zinc-100 dark:bg-zinc-700 font-bold">
                                                                    <td colspan="3" class="px-2 py-2 text-right text-zinc-900 dark:text-zinc-200">
                                                                        Total Tarea:
                                                                    </td>
                                                                    <td class="px-2 py-2 text-zinc-900 dark:text-zinc-200 text-right">
                                                                        L {{ number_format($tarea->presupuestos->sum('total'), 2) }}
                                                                    </td>
                                                                    <td colspan="4"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    @endif
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="h-12 w-12 text-zinc-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                        <p class="text-zinc-500 dark:text-zinc-400 text-lg font-medium">No se encontraron actividades</p>
                                        <p class="text-zinc-400 dark:text-zinc-500 text-sm mt-2">Intenta cambiar los filtros de búsqueda</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
            </x-slot>

            <x-slot name="mobile">
                @forelse($actividades as $actividad)
                    <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg p-4 mb-4">
                        <!-- Header de la tarjeta -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300">
                                        {{ $actividad->correlativo }}
                                    </span>
                                    <span class="text-xs text-zinc-500 dark:text-zinc-400">
                                        #{{ $loop->iteration + ($actividades->currentPage() - 1) * $actividades->perPage() }}
                                    </span>
                                </div>
                                <h3 class="text-sm font-medium text-zinc-900 dark:text-zinc-100 mb-1">
                                    {{ $actividad->nombre }}
                                </h3>
                                @if($actividad->categoria)
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                        {{ $actividad->categoria->nombre }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Información adicional -->
                        <div class="space-y-2 mb-3">
                            <div class="flex items-center text-xs">
                                <span class="text-zinc-500 dark:text-zinc-400 w-24">Departamento:</span>
                                <span class="text-zinc-900 dark:text-zinc-100 font-medium">
                                    {{ $actividad->departamento->nombre ?? 'N/A' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-zinc-500 dark:text-zinc-400">Subido al SPI:</span>
                                <div class="flex items-center space-x-2">
                                    <button wire:click="toggleSPI({{ $actividad->id }})" type="button"
                                        class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 {{ $actividad->uploadedIntoSPI ? 'bg-green-600' : 'bg-zinc-200 dark:bg-zinc-600' }}"
                                        role="switch" aria-checked="{{ $actividad->uploadedIntoSPI ? 'true' : 'false' }}">
                                        <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $actividad->uploadedIntoSPI ? 'translate-x-4' : 'translate-x-0' }}"></span>
                                    </button>
                                    <span class="text-xs font-medium {{ $actividad->uploadedIntoSPI ? 'text-green-600 dark:text-green-400' : 'text-zinc-500 dark:text-zinc-400' }}">
                                        {{ $actividad->uploadedIntoSPI ? 'Listo' : 'Pendiente' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Botón expandir -->
                        <button wire:click="toggleExpand({{ $actividad->id }})" 
                            class="w-full flex items-center justify-center gap-2 py-2 px-3 text-sm font-medium text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/30 transition-colors">
                            <span>{{ $expandedRow === $actividad->id ? 'Ocultar' : 'Ver' }} Detalles</span>
                            <svg class="h-4 w-4 transition-transform duration-200 {{ $expandedRow === $actividad->id ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Detalles expandidos -->
                        @if($expandedRow === $actividad->id && $actividadDetalle)
                        <div class="mt-4 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                            <div class="space-y-4">
                                <!-- Datos Institucionales -->
                                <div class="bg-zinc-50 dark:bg-zinc-900 rounded-lg p-3">
                                    <h4 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mb-3 flex items-center">
                                        <svg class="h-4 w-4 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        Datos Institucionales
                                    </h4>
                                    <div class="space-y-2 text-xs">
                                        <div>
                                            <span class="text-zinc-500 dark:text-zinc-400">Dimensión:</span>
                                            <p class="text-zinc-900 dark:text-zinc-200 mt-0.5">
                                                {{ $actividadDetalle->resultado->area->objetivo->dimension->nombre ?? 'N/A' }}
                                            </p>
                                        </div>
                                        <div>
                                            <span class="text-zinc-500 dark:text-zinc-400">Objetivo:</span>
                                            <p class="text-zinc-900 dark:text-zinc-200 mt-0.5">
                                                {{ $actividadDetalle->resultado->area->objetivo->nombre ?? 'N/A' }}
                                            </p>
                                        </div>
                                        <div>
                                            <span class="text-zinc-500 dark:text-zinc-400">Área:</span>
                                            <p class="text-zinc-900 dark:text-zinc-200 mt-0.5">
                                                {{ $actividadDetalle->resultado->area->nombre ?? 'N/A' }}
                                            </p>
                                        </div>
                                        <div>
                                            <span class="text-zinc-500 dark:text-zinc-400">Resultado Institucional:</span>
                                            <p class="text-zinc-900 dark:text-zinc-200 mt-0.5">
                                                {{ $actividadDetalle->resultado->nombre ?? 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Datos Generales -->
                                <div class="bg-zinc-50 dark:bg-zinc-900 rounded-lg p-3">
                                    <h4 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mb-3 flex items-center">
                                        <svg class="h-4 w-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Datos Generales
                                    </h4>
                                    <div class="space-y-2 text-xs">
                                        <div>
                                            <span class="text-zinc-500 dark:text-zinc-400">Resultado de actividad:</span>
                                            <p class="text-zinc-900 dark:text-zinc-200 mt-0.5">
                                                {{ $actividadDetalle->resultadoActividad ?? 'N/A' }}
                                            </p>
                                        </div>
                                        <div>
                                            <span class="text-zinc-500 dark:text-zinc-400">Población objetivo:</span>
                                            <p class="text-zinc-900 dark:text-zinc-200 mt-0.5">
                                                {{ $actividadDetalle->poblacion_objetivo ?? 'N/A' }}
                                            </p>
                                        </div>
                                        @if($actividadDetalle->empleados->count() > 0)
                                        <div class="pt-2 border-t border-zinc-200 dark:border-zinc-700">
                                            <span class="text-zinc-500 dark:text-zinc-400 block mb-2">Encargados:</span>
                                            <div class="space-y-1">
                                                @foreach($actividadDetalle->empleados as $empleado)
                                                <div class="bg-white dark:bg-zinc-800 rounded p-2">
                                                    <p class="text-zinc-900 dark:text-zinc-200 font-medium">{{ $empleado->nombre }}</p>
                                                    <p class="text-zinc-500 dark:text-zinc-400">#{{ $empleado->numeroEmpleado ?? 'N/A' }}</p>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Indicadores -->
                                @if($actividadDetalle->indicadores->count() > 0)
                                <div class="bg-zinc-50 dark:bg-zinc-900 rounded-lg p-3">
                                    <h4 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mb-3 flex items-center">
                                        <svg class="h-4 w-4 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        Indicadores
                                    </h4>
                                    <div class="space-y-3">
                                        @foreach($actividadDetalle->indicadores as $indicador)
                                        <div class="bg-white dark:bg-zinc-800 rounded p-2">
                                            <div class="flex items-start justify-between mb-2">
                                                <p class="text-xs font-medium text-zinc-900 dark:text-zinc-100 flex-1">{{ $indicador->nombre }}</p>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ml-2 {{ $indicador->isPorcentaje ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300' : 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300' }}">
                                                    {{ $indicador->isPorcentaje ? '%' : '#' }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-zinc-600 dark:text-zinc-400 mb-2">
                                                Meta: {{ $indicador->cantidadPlanificada ?? '0' }} | 
                                                Ejecutada: {{ $indicador->cantidadEjecutada ?? '0' }}
                                            </p>
                                            @if($indicador->planificacions->count() > 0)
                                            @php
                                                $trimestres = [
                                                    'T1' => ['meses' => [1, 2, 3], 'nombre' => 'T1', 'planificado' => 0, 'ejecutado' => 0, 'fechaInicio' => null, 'fechaFin' => null],
                                                    'T2' => ['meses' => [4, 5, 6], 'nombre' => 'T2', 'planificado' => 0, 'ejecutado' => 0, 'fechaInicio' => null, 'fechaFin' => null],
                                                    'T3' => ['meses' => [7, 8, 9], 'nombre' => 'T3', 'planificado' => 0, 'ejecutado' => 0, 'fechaInicio' => null, 'fechaFin' => null],
                                                    'T4' => ['meses' => [10, 11, 12], 'nombre' => 'T4', 'planificado' => 0, 'ejecutado' => 0, 'fechaInicio' => null, 'fechaFin' => null],
                                                ];
                                                
                                                foreach($indicador->planificacions as $planificacion) {
                                                    $mesId = $planificacion->mes->id ?? null;
                                                    foreach($trimestres as $key => &$trimestre) {
                                                        if($mesId && in_array($mesId, $trimestre['meses'])) {
                                                            $trimestre['planificado'] += $planificacion->cantidad ?? 0;
                                                            $trimestre['ejecutado'] += $planificacion->seguimientos->sum('cantidad') ?? 0;
                                                            
                                                            // Establecer fechas (primera fecha inicio y última fecha fin)
                                                            if($planificacion->fechaInicio && (!$trimestre['fechaInicio'] || $planificacion->fechaInicio < $trimestre['fechaInicio'])) {
                                                                $trimestre['fechaInicio'] = $planificacion->fechaInicio;
                                                            }
                                                            if($planificacion->fechaFin && (!$trimestre['fechaFin'] || $planificacion->fechaFin > $trimestre['fechaFin'])) {
                                                                $trimestre['fechaFin'] = $planificacion->fechaFin;
                                                            }
                                                        }
                                                    }
                                                }
                                            @endphp
                                            <div class="grid grid-cols-2 gap-2 text-xs">
                                                @foreach($trimestres as $trimestre)
                                                @if($trimestre['planificado'] > 0 || $trimestre['ejecutado'] > 0)
                                                <div class="bg-zinc-50 dark:bg-zinc-900 rounded p-2">
                                                    <p class="font-medium text-zinc-900 dark:text-zinc-200">{{ $trimestre['nombre'] }}</p>
                                                    @if($trimestre['fechaInicio'])
                                                        <p class="text-zinc-500 dark:text-zinc-400 text-xs">
                                                            {{ \Carbon\Carbon::parse($trimestre['fechaInicio'])->format('d/m/Y') }} - 
                                                            {{ $trimestre['fechaFin'] ? \Carbon\Carbon::parse($trimestre['fechaFin'])->format('d/m/Y') : '-' }}
                                                        </p>
                                                    @endif
                                                    <p class="text-zinc-600 dark:text-zinc-400">P: {{ number_format($trimestre['planificado'], 1) }}</p>
                                                    <p class="text-zinc-600 dark:text-zinc-400">E: {{ number_format($trimestre['ejecutado'], 1) }}</p>
                                                    @php
                                                        $porcentaje = $trimestre['planificado'] > 0 ? ($trimestre['ejecutado'] / $trimestre['planificado']) * 100 : 0;
                                                        $colorClass = $porcentaje >= 80 ? 'text-green-600' : ($porcentaje >= 50 ? 'text-yellow-600' : 'text-red-600');
                                                    @endphp
                                                    <p class="{{ $colorClass }} font-medium">{{ number_format($porcentaje, 0) }}%</p>
                                                </div>
                                                @endif
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                <!-- Tareas -->
                                @if($actividadDetalle->tareas->count() > 0)
                                <div class="bg-zinc-50 dark:bg-zinc-900 rounded-lg p-3">
                                    <h4 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mb-3 flex items-center">
                                        <svg class="h-4 w-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                        </svg>
                                        Tareas ({{ $actividadDetalle->tareas->count() }})
                                    </h4>
                                    <div class="space-y-2">
                                        @foreach($actividadDetalle->tareas as $tarea)
                                        <div class="bg-white dark:bg-zinc-800 rounded p-2">
                                            <p class="text-xs font-medium text-zinc-900 dark:text-zinc-100">{{ $tarea->nombre }}</p>
                                            @if($tarea->presupuestos->count() > 0)
                                            <p class="text-xs text-green-600 dark:text-green-400 font-medium mt-1">
                                                Total: ${{ number_format($tarea->presupuestos->sum('total'), 2) }}
                                            </p>
                                            <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                                {{ $tarea->presupuestos->count() }} recurso(s)
                                            </p>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                @empty
                    <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg p-8">
                        <div class="flex flex-col items-center justify-center text-center">
                            <svg class="h-12 w-12 text-zinc-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-zinc-500 dark:text-zinc-400 font-medium">No se encontraron actividades</p>
                            <p class="text-zinc-400 dark:text-zinc-500 text-sm mt-1">Intenta cambiar los filtros</p>
                        </div>
                    </div>
                @endforelse
            </x-slot>

            <x-slot name="footer">   
                    {{ $actividades->links() }}
            </x-slot>
        </x-table>
    </div>

    @push('scripts')
    <script>
        // Notificación cuando se actualiza el estado SPI
        Livewire.on('spi-updated', (event) => {
            // Aquí puedes agregar una notificación toast si lo deseas
            console.log(event.message);
        });
    </script>
    @endpush
</div>
