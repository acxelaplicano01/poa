<div>
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

    <div class="bg-white dark:bg-zinc-900 rounded-lg shadow p-4 mb-6">
        @if($mostrarSelector)
    <div class="mb-4 w-full">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
            Departamento
        </label>
        <select
            x-data
            x-on:change="$wire.set('departamentoSeleccionado', $event.target.value)"
            class="w-full sm:w-auto min-w-[300px] rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Selecciona un departamento</option>
            @foreach($departamentosUsuario as $depto)
                <option value="{{ $depto->id }}" {{ $departamentoSeleccionado == $depto->id ? 'selected' : '' }}>
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
                            <option value="">Todos los años</option>
                            @foreach($poaYears as $year)
                                <option value="{{ $year }}">POA {{ $year }}</option>
                            @endforeach
                        </select>
            </div>

            <!-- Botón Revisar Sumario -->
            <div class="flex items-center justify-end flex-shrink-0 w-fit ml-auto">
                <button wire:click="irAlSumario"
                    class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('Revisar sumario') }}
                </button>
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
                                <td colspan="5" class="px-3 py-2 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ __('No hay recursos disponibles.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
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
                        {{ __('No hay recursos seleccionados.') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
