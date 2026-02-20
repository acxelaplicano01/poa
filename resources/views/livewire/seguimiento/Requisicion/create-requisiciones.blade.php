<div>
    @can('planificacion.requisicion.crear')

        {{-- Mensajes de éxito/error --}}
        @if ($successMessage)
            @include('rk.default.notifications.notification-alert', [
                'type' => 'success',
                'dismissible' => true,
                'icon' => true,
                'duration' => 5,
                'slot' => $successMessage,
            ])
        @endif

        @if (session()->has('message'))
            @include('rk.default.notifications.notification-alert', [
                'type' => 'success',
                'dismissible' => true,
                'icon' => true,
                'duration' => 5,
                'slot' => session('message'),
            ])
        @endif

        @if (session()->has('error'))
            @include('rk.default.notifications.notification-alert', [
                'type' => 'error',
                'dismissible' => true,
                'icon' => true,
                'duration' => 8,
                'slot' => session('error'),
            ])
        @endif

        {{-- Alerta de plazo --}}
        @if (!$puedeCrearRequisicion && $mensajePlazoRequisicion)
            <div class="mb-4 bg-amber-100 dark:bg-amber-900/30 border border-amber-400 dark:border-amber-700 text-amber-800 dark:text-amber-300 px-4 py-3 rounded-lg"
                role="alert">
                <div class="flex items-start justify-between">
                    <div class="flex items-start flex-1">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="font-semibold">Plazo de gestión de requisiciones no disponible</p>
                            <p class="text-sm mt-1">{{ $mensajePlazoRequisicion }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Contador de días restantes --}}
        @if ($puedeCrearRequisicion && $diasRestantes !== null)
            <div class="mb-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-300 px-4 py-3 rounded-lg flex items-center justify-between"
                role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <div>
                        <p class="font-semibold text-sm">Plazo de gestión de requisiciones activo</p>
                        <p class="text-xs mt-0.5">Puedes gestionar requisiciones dentro del plazo establecido.</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="flex items-baseline">
                        <span class="text-3xl font-bold">{{ intval($diasRestantes) }}</span>
                        <span class="text-sm ml-1">{{ $diasRestantes == 1 ? 'día' : 'días' }}</span>
                    </div>
                    <p class="text-xs mt-0.5">{{ $diasRestantes == 1 ? 'restante' : 'restantes' }}</p>
                </div>
            </div>
        @endif

        <div class="bg-white dark:bg-zinc-900 rounded-lg shadow p-4 mb-6">
            @if ($mostrarSelector)
                <div class="mb-4 w-full">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                        Departamento
                    </label>
                    <select x-data x-on:change="$wire.set('departamentoSeleccionado', $event.target.value)"
                        class="w-full sm:w-auto min-w-[300px] rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Selecciona un departamento</option>
                        @foreach ($departamentosUsuario as $depto)
                            <option value="{{ $depto->id }}"
                                {{ $departamentoSeleccionado == $depto->id ? 'selected' : '' }}>
                                {{ $depto->name }} - {{ $depto->unidadEjecutora->name ?? 'Sin UE' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="flex flex-wrap items-center w-full gap-4">
                <!-- Input de búsqueda -->
                <div class="relative w-full sm:w-auto">
                    <x-input wire:model.live="buscarActividad" type="text" placeholder="Buscar actividad..."
                        class="w-full pl-10 pr-4 py-2" />
                    <div class="absolute left-3 top-2.5">
                        <svg class="h-5 w-5 text-zinc-500 dark:text-zinc-400" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Selector de paginación -->
                <div class="w-full sm:w-auto">
                    <x-select id="perPage" wire:model.live="perPage" :options="[
                        ['value' => '10', 'text' => '10 por página'],
                        ['value' => '25', 'text' => '25 por página'],
                        ['value' => '50', 'text' => '50 por página'],
                        ['value' => '100', 'text' => '100 por página'],
                    ]" class="w-full" />
                </div>

                <!-- Filtro de POA por años -->
                <div class="w-full sm:w-auto min-w-[150px] max-w-xs">
                    <select wire:model.live="poaYear"
                        class="block w-full min-w-[180px] max-w-xs rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 text-sm py-2 px-3">

                        @foreach ($poaYears as $year)
                            <option value="{{ $year }}">POA {{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Botón Revisar Sumario -->
                <div class="flex items-center justify-end flex-shrink-0 w-fit ml-auto">
                    @if ($puedeCrearRequisicion)
                        <x-spinner-button wire:click="irAlSumario"
                            class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            {{ __('Revisar sumario') }}
                        </x-spinner-button>
                    @else
                        <div class="relative group">
                            <button disabled
                                class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium 
                       bg-zinc-300 dark:bg-zinc-700 text-zinc-400 dark:text-zinc-500 
                       cursor-not-allowed opacity-60">
                                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                {{ __('Revisar sumario') }}
                            </button>
                            <div
                                class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block
                        bg-zinc-800 text-white text-xs rounded-lg px-3 py-2 shadow-lg z-50 w-64 text-center">
                                {{ $mensajePlazoRequisicion }}
                                <div
                                    class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-zinc-800">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Contenedor principal para el componente -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
            <!-- Tabla de actividades aprobadas con presupuesto disponible -->
            <div class="col-span-3 bg-white dark:bg-zinc-900 rounded-lg shadow p-4">
                <h2 class="text-lg font-semibold mb-4 text-zinc-800 dark:text-zinc-200">Recursos disponibles de
                    actividades aprobadas</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-700">
                            <tr>
                                <th
                                    class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase w-[25%]">
                                    Recurso</th>
                                <th
                                    class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase w-[30%]">
                                    Act./Tarea</th>
                                <th
                                    class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase w-[15%]">
                                    Cantidad</th>
                                <th
                                    class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase w-[20%]">
                                    Costo</th>
                                <th
                                    class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase w-[10%]">
                                    Acción</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                            @forelse ($actividades_aprobadas as $actividad)
                                @foreach ($actividad->presupuestos as $presupuesto)
                                    @php
                                        $valores = $valoresPlanificados[$presupuesto->id] ?? [
                                            'cantidad_disponible' => 0,
                                            'cantidad_planificada' => 0,
                                            'costo_disponible' => 0,
                                            'costo_planificado' => 0,
                                        ];
                                    @endphp
                                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50">
                                        <td
                                            class="px-3 py-2 text-xs text-zinc-900 dark:text-zinc-100 break-words max-w-[180px]">
                                            {{ $presupuesto->recurso ?? 'N/A' }}
                                        </td>
                                        <td class="px-3 py-2 text-xs text-zinc-600 dark:text-zinc-400">
                                            <div class="font-semibold text-zinc-800 dark:text-zinc-200">
                                                {{ $actividad->actividad->nombre ?? '-' }}</div>
                                            <div>{{ $actividad->nombre ?? '-' }}</div>
                                        </td>
                                        <td class="px-3 py-2 text-xs text-zinc-600 dark:text-zinc-400">
                                            <div class="flex flex-col gap-0.5">
                                                <div><span class="font-medium">Disponible:</span>
                                                    {{ $valores['cantidad_disponible'] }}</div>
                                                <div><span class="font-medium">Planificado:</span>
                                                    {{ $valores['cantidad_planificada'] }}</div>
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 text-xs text-zinc-600 dark:text-zinc-400">
                                            <div class="flex flex-col gap-0.5">
                                                <div><span class="font-medium"> Costo Unitario:</span> L
                                                    {{ number_format($presupuesto->costounitario ?? 0, 0) }}</div>
                                                <div><span class="font-medium">Disponible:</span> L
                                                    {{ number_format($valores['costo_disponible'], 0) }}</div>
                                                <div><span class="font-medium">Planificado:</span> L
                                                    {{ number_format($valores['costo_planificado'], 0) }}</div>
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 text-xs text-zinc-600 dark:text-zinc-400">
                                            <label
                                                class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-1">Cantidad</label>
                                            <input type="number" step="1" min="0"
                                                max="{{ $valores['cantidad_disponible'] }}"
                                                class="w-16 text-xs border-zinc-300 dark:border-zinc-700 rounded focus:ring-indigo-500 focus:border-indigo-500 dark:bg-zinc-800 dark:text-zinc-100"
                                                wire:model.live="presupuestosSeleccionados.{{ $presupuesto->id }}" />
                                            @error('presupuestosSeleccionados.' . $presupuesto->id)
                                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                   <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="h-12 w-12 text-zinc-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                        <p class="text-zinc-500 dark:text-zinc-400 text-lg font-medium">No se encontraron requisiciones</p>
                                        <p class="text-zinc-400 dark:text-zinc-500 text-sm mt-2">Intenta cambiar los filtros de búsqueda</p>
                                    </div>
                                </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($actividades_aprobadas->hasPages())
                    <div class="mt-4">
                        {{ $actividades_aprobadas->links() }}
                    </div>
                @endif
            </div>

            <!-- Pantalla fija para el sumario -->
            <div
                class="bg-white dark:bg-zinc-900 rounded-lg shadow p-4 sticky top-4 self-start max-h-[calc(100vh-6rem)] flex flex-col">
                <h2 class="text-sm font-semibold mb-4 text-zinc-800 dark:text-zinc-200">Sumario de Recursos</h2>
                <!-- Ajuste de altura dinámica -->
                <div class="flex flex-col gap-2 overflow-y-auto flex-1">
                    @forelse($recursosSeleccionados as $recurso)
                        <div class="relative flex items-start group">
                            <!-- Contenido -->
                            <div class="ml-4 sm:ml-1 flex-1">
                                <div
                                    class="border-l-4 sm:border-l-4 border-green-500 bg-green-50 dark:bg-green-900/20 rounded-lg p-2 sm:p-3 shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-start mb-1 sm:mb-2">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-1 sm:gap-2 flex-wrap">
                                                <h4
                                                    class="text-xs sm:text-sm font-semibold text-green-700 dark:text-green-300">
                                                    {{ $recurso['nombre'] ?? '-' }}
                                                </h4>
                                                <span
                                                    class="px-2 py-0.5 text-[10px] font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                    AGREGADO
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-2 text-[10px] sm:text-xs">
                                        <div class="flex items-center text-zinc-600 dark:text-zinc-400">
                                            <span class="font-medium">Cantidad:</span>
                                            {{ $recurso['cantidad_seleccionada'] ?? '-' }}
                                        </div>

                                        <div class="flex items-center text-zinc-600 dark:text-zinc-400">
                                            <span class="font-medium">Total:</span> L
                                            {{ number_format($recurso['total'] ?? 0, 0) }}
                                        </div>
                                    </div>

                                    <!-- Botón para eliminar -->
                                    <div class="absolute bottom-2 right-2">
                                        <button wire:click="quitarRecursoDelSumario({{ $recurso['id'] }})"
                                            class="p-2 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-red-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-zinc-500 dark:text-zinc-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="h-12 w-12 text-zinc-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                        <p class="text-zinc-500 dark:text-zinc-400 text-sm font-medium">No hay recursos seeccionados</p>
                                        <p class="text-zinc-400 dark:text-zinc-500 text-xs mt-2">Intenta cambiar los filtros de búsqueda</p>
                                    </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endcan
</div>
