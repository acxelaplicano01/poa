<div>

    <div class="bg-white dark:bg-zinc-900 rounded-lg shadow p-4 mb-6">
        <div class="flex flex-row items-center w-full gap-2">
            <x-input wire:model.live="buscarActividad" type="text" placeholder="Buscar por nombre de actividad o tarea" class="w-55 text-sm" />
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
            <div class="flex items-center justify-end flex-shrink-0 w-fit ml-auto">
                <x-spinner-button wire:click="abrirSumario" loadingTarget="abrirSumario" :loadingText="__('Abriendo...')"
                    class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('Revisar sumario') }}
                </x-spinner-button>
            </div>
        </div>
    </div>

    <!-- Actividades aprobadas con presupuesto disponible -->
    @if(isset($actividades_aprobadas) && count($actividades_aprobadas) > 0)
        <div class="bg-white dark:bg-zinc-900 rounded-lg shadow p-4 mb-6">
            <h2 class="text-lg font-semibold mb-4 text-zinc-800 dark:text-zinc-200">Recursos disponibles de actividades aprobadas</h2>
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-700">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Recurso</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Detalle Técnico</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Act./Tarea</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Mes</th>
                        <th class="px-3 py-2 text-center text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Cant. Disp.</th>
                        <th class="px-3 py-2 text-center text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Cant. Planif.</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Monetario</th>
                   <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Acción</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                    @foreach($actividades_aprobadas as $actividad)
                        @foreach($actividad->presupuestos as $presupuesto)
                            @php
                                $valores = $valoresPlanificados[$presupuesto->id] ?? [
                                    'cantidad_disponible' => 0,
                                    'cantidad_planificada' => 0,
                                    'costo_disponible' => 0,
                                    'costo_planificado' => 0
                                ];
                            @endphp
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50">
                                <td class="px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100">{{ $presupuesto->recurso ?? 'N/A' }}</td>
                                <td class="px-3 py-2 text-sm text-zinc-600 dark:text-zinc-400 max-w-xs truncate">{{ $presupuesto->detalle_tecnico ?? '-' }}</td>
                                <td class="px-3 py-2 text-sm text-zinc-600 dark:text-zinc-400">
                                    <div><span class="font-semibold">{{ $actividad->actividad->nombre ?? '-' }}</span></div>
                                    <div>{{ $actividad->nombre ?? '-' }}</div>
                                </td>
                                <td class="px-3 py-2 text-sm text-zinc-600 dark:text-zinc-400">{{ $presupuesto->mes->mes ?? 'N/A' }}</td>
                                <td class="px-3 py-2 text-sm text-center text-zinc-600 dark:text-zinc-400">{{ $valores['cantidad_disponible'] }}</td>
                                <td class="px-3 py-2 text-sm text-center text-zinc-600 dark:text-zinc-400">{{ $valores['cantidad_planificada'] }}</td>
                                <td class="px-3 py-2 text-sm text-zinc-600 dark:text-zinc-400">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs font-semibold px-3 py-1 rounded-full">Costo unitario</span>
                                            <span class="font-bold text-sm text-zinc-600 dark:text-zinc-400">L {{ number_format($presupuesto->costounitario ?? 0, 2) }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs font-semibold px-3 py-1 rounded-full">Disponible</span>
                                            <span class="font-bold text-sm text-zinc-600 dark:text-zinc-400">L {{ number_format($valores['costo_disponible'], 2) }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs font-semibold px-3 py-1 rounded-full">Costo planificado</span>
                                            <span class="font-bold text-sm text-zinc-600 dark:text-zinc-400">L {{ number_format($valores['costo_planificado'], 2) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-2 text-sm text-zinc-600 dark:text-zinc-400">
                                    <div>
                                        <label for="cantidad-{{ $presupuesto->id }}" class="block text-xs font-medium text-zinc-700 dark:text-zinc-300">Cantidad a solicitar</label>
                                        <input id="cantidad-{{ $presupuesto->id }}" type="number" step="1" min="0" max="{{ $valores['cantidad_disponible'] }}" class="mt-1 w-20 text-sm border-zinc-300 dark:border-zinc-700 rounded focus:ring-indigo-500 focus:border-indigo-500 dark:bg-zinc-800 dark:text-zinc-100" wire:model.lazy="presupuestosSeleccionados.{{ $presupuesto->id }}" />
                                        @error('presupuestosSeleccionados.' . $presupuesto->id) <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif



    <!-- Modal de confirmación para eliminar -->
    @include('livewire.seguimiento.Requisicion.delete-confirmation')

    <!-- Modal de errores -->
    <x-error-modal 
        :show="$showErrorModal" 
        :message="$errorMessage"
        wire:click="closeErrorModal"
    />

    <!-- Modal de sumario de recursos seleccionados usando wire:model -->
    <x-dialog-modal wire:model="showSumarioModal" maxWidth="4xl">
        <x-slot name="title">
            {{ __('Sumario de Recursos Seleccionados') }}
        </x-slot>
        <x-slot name="content">
            <div class="space-y-6">
                <!-- Tabla de recursos seleccionados -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700 mb-4">
                        <thead class="bg-zinc-50 dark:bg-zinc-700">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Recurso</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Actividad</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Proceso Compra</th>
                                <th class="px-3 py-2 text-center text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Cantidad Seleccionada</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Unidad de Medida</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Precio Unitario</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Total</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recursosSeleccionados as $recurso)
                                <tr>
                                    <td class="px-3 py-2 text-zinc-900 dark:text-zinc-100">{{ $recurso['nombre'] ?? '-' }}</td>
                                    <td class="px-3 py-2 text-zinc-600 dark:text-zinc-400">{{ $recurso['actividad'] ?? '-' }}</td>
                                    <td class="px-3 py-2 text-zinc-600 dark:text-zinc-400">{{ $recurso['proceso_compra'] ?? '-' }}</td>
                                    <td class="px-3 py-2 text-center text-zinc-600 dark:text-zinc-400">{{ $recurso['cantidad_seleccionada'] ?? '-' }}</td>
                                    <td class="px-3 py-2 text-zinc-600 dark:text-zinc-400">{{ $recurso['unidad_medida'] ?? '-' }}</td>
                                    <td class="px-3 py-2 text-zinc-600 dark:text-zinc-400">L {{ number_format($recurso['precio_unitario'] ?? 0, 2) }}</td>
                                    <td class="px-3 py-2 text-zinc-600 dark:text-zinc-400 font-bold">L {{ number_format($recurso['total'] ?? 0, 2) }}</td>
                                <td class="px-3 py-2">
                                    <div class="flex flex-col gap-2 max-h-16 overflow-y-auto">
                                        <button wire:click="quitarRecursoDelSumario({{ $recurso['id'] }})" class="text-red-600 hover:text-red-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                        @if(Str::of(strtolower($recurso['nombre']))->contains('gasolina') || Str::of(strtolower($recurso['nombre']))->contains('diesel'))
                                            <button
                                                wire:click="abrirOrdenCombustibleModal({{ $recurso['id'] }})"
                                                class="text-yellow-600 hover:text-yellow-700 dark:text-yellow-400 dark:hover:text-yellow-300"
                                                title="Registrar orden de combustible"
                                            >
                                                <!-- Icono alternativo: Carro (Heroicons outline: car) -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="inline w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13l2-5a2 2 0 0 1 1.85-1.3h10.3A2 2 0 0 1 19 8l2 5m-18 0h18m-18 0v5a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-1h8v1a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-5" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-3 py-2 text-center text-zinc-500 dark:text-zinc-400">
                                    {{ __('No hay recursos seleccionados.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Formulario para crear requisición -->
            <div class="bg-zinc-50 dark:bg-zinc-700 p-4 rounded-lg space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-label for="descripcionRequisicion" value="Descripción" />
                        <x-input id="descripcionRequisicion"
                                 type="text"
                                 wire:model.defer="descripcion"
                                 placeholder="Descripción"
                                 class="w-full text-base px-4 py-2"
                        />
                        @error('descripcion')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <x-label for="observacionRequisicion" value="Observación" />
                        <x-input id="observacionRequisicion"
                                 type="text"
                                 wire:model.defer="observacion"
                                 placeholder="Observación"
                                 class="w-full text-base px-4 py-2"
                        />
                        @error('observacion')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 items-end">
                    <div>
                        <x-label for="fechaRequerido" value="Fecha a requerir" />
                        <x-input id="fechaRequerido"
                                 type="date"
                                 wire:model.defer="fechaRequerido"
                                 placeholder="dd/mm/aaaa"
                                 class="w-full text-base px-4 py-2"
                        />
                        @error('fechaRequerido')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex justify-end">
                        <span class="font-semibold text-zinc-900 dark:text-zinc-100">
                            Monto total a requerir: L {{ number_format(collect($recursosSeleccionados)->sum('total'), 2) }}
                        </span>
                    </div>
                </div>
                @if(session('combustible_error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-2 rounded mt-2 text-sm">
                        {{ session('combustible_error') }}
                    </div>
                @endif
            </div>
        </div>
    </x-slot>
    <x-slot name="footer">
        <div class="flex justify-end gap-2">
            <button wire:click="cerrarSumario" class="px-4 py-2 bg-zinc-400 text-white rounded hover:bg-zinc-500">Cerrar</button>
            <button wire:click="crearRequisicionDesdeSumario" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 font-semibold">
                Crear Requisición
            </button>
        </div>
    </x-slot>
</x-dialog-modal>

{{-- Modal Orden de Combustible --}}
<x-dialog-modal wire:model="showOrdenCombustibleModal" maxWidth="4xl">
    <x-slot name="title">
        <div class="flex items-center gap-2">
            <!-- Icono de tanque de gasolina o carro -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <!-- Heroicons outline: fuel (tanque de gasolina) -->
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 3v1a1 1 0 001 1h1a1 1 0 011 1v13a2 2 0 01-2 2H8a2 2 0 01-2-2V5a2 2 0 012-2h8zm0 0V2a1 1 0 00-1-1h-2a1 1 0 00-1 1v1" />
            </svg>
            <span class="font-semibold text-zinc-800 dark:text-zinc-100">Orden de Combustible ({{ $ordenCombustibleRecursoNombre ?? '' }})</span>
        </div>
    </x-slot>
    <x-slot name="content">
        <div class="dark:bg-zinc-900 bg-white rounded-lg p-4">
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Modelo de Vehículo</label>
                    <input type="text" wire:model.defer="ordenCombustibleData.modelo_vehiculo" class="w-full border rounded px-2 py-1 dark:bg-zinc-800 dark:text-zinc-100" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">No. de Placa</label>
                    <input type="text" wire:model.defer="ordenCombustibleData.placa" class="w-full border rounded px-2 py-1 dark:bg-zinc-800 dark:text-zinc-100" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Lugar de salida</label>
                    <input type="text" wire:model.defer="ordenCombustibleData.lugar_salida" class="w-full border rounded px-2 py-1 dark:bg-zinc-800 dark:text-zinc-100" />
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Recorrido en km</label>
                    <input type="number" wire:model.defer="ordenCombustibleData.recorrido_km" class="w-full border rounded px-2 py-1 dark:bg-zinc-800 dark:text-zinc-100" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Lugar de destino</label>
                    <input type="text" wire:model.defer="ordenCombustibleData.lugar_destino" class="w-full border rounded px-2 py-1 dark:bg-zinc-800 dark:text-zinc-100" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Fecha a realizar</label>
                    <input type="date" wire:model.defer="ordenCombustibleData.fecha_actividad" class="w-full border rounded px-2 py-1 dark:bg-zinc-800 dark:text-zinc-100" />
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Monto</label>
                    <input type="number" step="0.01" wire:model.defer="ordenCombustibleData.monto" class="w-full border rounded px-2 py-1 dark:bg-zinc-800 dark:text-zinc-100" />
                    @error('ordenCombustibleData.monto') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Monto en letras</label>
                    <input type="text" wire:model.defer="ordenCombustibleData.monto_en_letras" class="w-full border rounded px-2 py-1 dark:bg-zinc-800 dark:text-zinc-100" />
                    @error('ordenCombustibleData.monto_en_letras') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Empleado Responsable</label>
                <select wire:model.defer="ordenCombustibleData.responsable" class="w-full border rounded px-2 py-1 dark:bg-zinc-800 dark:text-zinc-100">
                    <option value="">Seleccione un empleado</option>
                    @foreach($empleados as $empleado)
                        <option value="{{ $empleado->id }}">
                            {{ $empleado->nombre ?? '' }} {{ $empleado->apellido ?? '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Actividades a realizar</label>
                <textarea wire:model.defer="ordenCombustibleData.actividades_realizar" class="w-full border rounded px-2 py-1 dark:bg-zinc-800 dark:text-zinc-100"></textarea>
            </div>
        </div>
    </x-slot>
    <x-slot name="footer">
        <button wire:click="cerrarOrdenCombustibleModal" class="px-4 py-2 bg-zinc-400 text-white rounded hover:bg-zinc-500">Cancelar</button>
        <button wire:click="guardarOrdenCombustible" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Confirmar</button>
    </x-slot>
</x-dialog-modal>
</div>
