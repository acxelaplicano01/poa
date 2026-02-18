<div class="mx-auto rounded-lg mt-8 sm:mt-6 lg:mt-4 mb-6">
    <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow sm:rounded-lg p-4 sm:p-6">
        <!-- Resumen de revisión y presupuesto -->
        <div class="mb-6">
            <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg p-4">
                <h2 class="text-lg font-semibold text-indigo-900 dark:text-indigo-100 mb-2">
                    REVISIÓN PARA {{ $resumen['nombreDepartamento'] ?? '-' }}
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                            L. {{ number_format($resumen['presupuesto'] ?? 0, 2) }}
                        </div>
                        <div class="text-xs text-zinc-500 dark:text-zinc-400">Presupuesto</div>
                    </div>
                    <div class="text-center">
                        <div class="text-lg font-bold text-green-600">
                            L. {{ number_format($resumen['planificado'] ?? 0, 2) }}
                        </div>
                        <div class="text-xs text-zinc-500 dark:text-zinc-400">Planificado</div>
                    </div>
                    <div class="text-center">
                        <div class="text-lg font-bold text-blue-600">
                            {{ $resumen['numActividades'] ?? 0 }}
                        </div>
                        <div class="text-xs text-zinc-500 dark:text-zinc-400">Actividades</div>
                    </div>
                    <div class="text-center">
                        <div class="text-lg font-bold text-zinc-900 dark:text-zinc-100">
                            {{ $resumen['porcentaje'] ?? 0 }}%
                        </div>
                        <div class="text-xs text-zinc-500 dark:text-zinc-400">% Planificado</div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <br>
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
            <h2 class="text-xl font-semibold text-zinc-800 dark:text-zinc-200">{{ __('Actividades en Revisión del Departamento') }}</h2>

            <div class="flex flex-col sm:flex-row w-full sm:w-auto space-y-3 sm:space-y-0 sm:space-x-2">
                <!-- Buscador por nombre de actividad o tarea -->
                <div class="relative w-full sm:w-auto">
                    <x-input wire:model.live="search" type="text" placeholder="Buscar actividad..." class="w-full pl-10 pr-4 py-2"/>
                    
                    <div class="absolute left-3 top-2.5">
                        <svg class="h-5 w-5 text-zinc-500 dark:text-zinc-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Filtro por año -->
                <div class="w-full sm:w-auto min-w-[150px] max-w-xs">
                    <select wire:model.live="poaYear" class="block w-full min-w-[180px] max-w-xs rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 text-sm py-2 px-3">
                        @foreach($poaYears as $year)
                            <option value="{{ $year }}">POA {{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Paginación -->
                <div class="w-full sm:w-auto">
                    <x-select 
                        id="perPage" 
                        wire:model.live="perPage"
                        :options="[
                            ['value' => '10', 'text' => '10 por página'],
                            ['value' => '25', 'text' => '25 por página'],
                            ['value' => '50', 'text' => '50 por página'],
                            ['value' => '100', 'text' => '100 por página'],
                        ]"
                        class="w-full"
                    />
                </div>
            </div>
        </div>
        <x-table
            :columns="[
                ['key' => 'nombre', 'label' => 'Nombre'],
                ['key' => 'tipo', 'label' => 'Tipo de Actividad'],
                ['key' => 'categoria', 'label' => 'Categoría'],
                ['key' => 'estado', 'label' => 'Estado'],
                ['key' => 'actions', 'label' => 'Acciones'],
            ]"
            empty-message="No hay actividades en revisión para este departamento."
            class="mt-6"
        >
            <x-slot name="desktop">
                @forelse($actividades as $actividad)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-100">{{ $actividad->nombre }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-100">{{ $actividad->tipo->tipo ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-100">{{ $actividad->categoria->categoria ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($actividad->estado)
                                @case('APROBADO')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                        {{ $actividad->estado }}
                                    </span>
                                    @break
                                @case('RECHAZADO')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                        {{ $actividad->estado }}
                                    </span>
                                    @break
                                @case('REVISION')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                        {{ $actividad->estado }}
                                    </span>
                                    @break
                                @case('REFORMULACION')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300">
                                        {{ $actividad->estado }}
                                    </span>
                                    @break
                                @default
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-zinc-100 dark:bg-zinc-900/30 text-zinc-800 dark:text-zinc-300">
                                        {{ $actividad->estado }}
                                    </span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('review-actividad-detalle', $actividad->id) }}" class="inline-flex items-center px-3 py-1 bg-indigo-600 dark:bg-indigo-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-700 active:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Ver Detalles
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-zinc-500 dark:text-zinc-400">
                            No hay actividades en revisión para este departamento.
                        </td>
                    </tr>
                @endforelse
            </x-slot>
            
            <x-slot name="mobile">
                @forelse($actividades as $actividad)
                    <div class="bg-white dark:bg-zinc-800 p-4 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700 mb-4">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <span class="bg-zinc-100 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-300 px-2 py-1 rounded-full text-xs">
                                    {{ $actividad->nombre }}
                                </span>
                            </div>
                            <a href="{{ route('review-actividad-detalle', $actividad->id) }}"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-700 active:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Ver Detalle
                            </a>
                        </div>
                        <div class="text-zinc-600 dark:text-zinc-400 text-sm mb-1">
                            <span class="font-semibold">Tipo:</span> {{ $actividad->tipo->tipo ?? '-' }}<br>
                            <span class="font-semibold">Categoría:</span> {{ $actividad->categoria->categoria ?? '-' }}<br>
                            <span class="font-semibold">Estado:</span> 
                            @switch($actividad->estado)
                                @case('APROBADO')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                        {{ $actividad->estado }}
                                    </span>
                                    @break
                                @case('RECHAZADO')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                        {{ $actividad->estado }}
                                    </span>
                                    @break
                                @case('REVISION')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                        {{ $actividad->estado }}
                                    </span>
                                    @break
                                @case('REFORMULACION')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300">
                                        {{ $actividad->estado }}
                                    </span>
                                    @break
                                @default
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-zinc-100 dark:bg-zinc-900/30 text-zinc-800 dark:text-zinc-300">
                                        {{ $actividad->estado }}
                                    </span>
                            @endswitch
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-zinc-800 p-4 rounded-lg shadow text-center text-zinc-500 dark:text-zinc-400">
                        No hay actividades en revisión para este departamento.
                    </div>
                @endforelse
            </x-slot>
        </x-table>
          {{-- Paginación FUERA del x-table --}}
        @if($actividades->hasPages())
            <div class="mt-4 px-4">
                {{ $actividades->links() }}
            </div>
        @endif

    </div>
</div>
