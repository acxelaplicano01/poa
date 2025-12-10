<!-- Paso 2: Planificaciones -->
<div>
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-zinc-800 dark:text-zinc-200">
            Planificación por Trimestre
        </h3>
        <x-button wire:click="openPlanificacionModal">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Agregar Planificación
        </x-button>
    </div>

    @if (empty($indicadores))
        <div class="text-center py-12 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
            <svg class="mx-auto h-12 w-12 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">No hay indicadores</h3>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                Primero debes crear indicadores en el paso anterior.
            </p>
        </div>
    @else
        @php
            // Agrupar todas las planificaciones por trimestre
            $planificacionesPorTrimestre = [];
            foreach($indicadores as $indicador) {
                foreach($indicador['planificacions'] ?? [] as $planificacion) {
                    $trimestreNum = $planificacion['mes']['trimestre']['trimestre'] ?? null;
                    if ($trimestreNum) {
                        if (!isset($planificacionesPorTrimestre[$trimestreNum])) {
                            $planificacionesPorTrimestre[$trimestreNum] = [];
                        }
                        $planificacionesPorTrimestre[$trimestreNum][] = [
                            'planificacion' => $planificacion,
                            'indicador' => $indicador
                        ];
                    }
                }
            }
            ksort($planificacionesPorTrimestre);
        @endphp

        @if(empty($planificacionesPorTrimestre))
            <div class="text-center py-12 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                <svg class="mx-auto h-12 w-12 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">No hay planificaciones</h3>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    Agrega planificaciones para distribuir las metas por trimestres.
                </p>
            </div>
        @else
            <div class="space-y-6">
                @foreach($planificacionesPorTrimestre as $trimestreNum => $planificaciones)
                    <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg p-4">
                        <h4 class="text-md font-semibold text-zinc-900 dark:text-zinc-100 mb-3">
                            {{ $trimestreNum }}
                        </h4>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                                <thead class="bg-zinc-50 dark:bg-zinc-700">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Indicador</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Cantidad</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Fecha Inicio</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Fecha Fin</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                                    @foreach($planificaciones as $item)
                                        @php
                                            $planificacion = $item['planificacion'];
                                            $indicador = $item['indicador'];
                                            $totalPlanificado = collect($indicador['planificacions'] ?? [])->sum('cantidad');
                                        @endphp
                                        <tr>
                                            <td class="px-4 py-2 text-sm">
                                                <div class="text-zinc-900 dark:text-zinc-100 font-medium">
                                                    {{ $indicador['nombre'] }}
                                                </div>
                                                <div class="text-xs text-zinc-500 dark:text-zinc-400">
                                                    Meta: {{ $indicador['cantidadPlanificada'] }} | 
                                                    Planificado: <span class="{{ $totalPlanificado > $indicador['cantidadPlanificada'] ? 'text-red-600' : 'text-green-600' }}">
                                                        {{ $totalPlanificado }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-2 text-sm text-zinc-900 dark:text-zinc-100">
                                                {{ $planificacion['cantidad'] }}
                                            </td>
                                            <td class="px-4 py-2 text-sm text-zinc-900 dark:text-zinc-100">
                                                {{ date('d/m/Y', strtotime($planificacion['fechaInicio'])) }}
                                            </td>
                                            <td class="px-4 py-2 text-sm text-zinc-900 dark:text-zinc-100">
                                                {{ date('d/m/Y', strtotime($planificacion['fechaFin'])) }}
                                            </td>
                                            <td class="px-4 py-2 text-right text-sm">
                                                <div class="flex items-center justify-end space-x-2">
                                                    <button wire:click="editPlanificacion({{ $planificacion['id'] }})"
                                                            class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400"
                                                            title="Editar planificación">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </button>
                                                    <button wire:click="openDeletePlanificacionModal({{ $planificacion['id'] }})"
                                                            class="text-red-600 hover:text-red-800 dark:text-red-400"
                                                            title="Eliminar planificación">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
            <p class="text-sm text-blue-800 dark:text-blue-300">
                <strong>Nota:</strong> Las planificaciones se agrupan por trimestre para facilitar la visualización y seguimiento.
            </p>
        </div>
    @endif

   @include('livewire.actividad.delete-confirmation-planificacion')

</div>
