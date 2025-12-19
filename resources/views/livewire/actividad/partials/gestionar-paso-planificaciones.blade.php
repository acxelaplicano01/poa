<!-- Paso 2: Planificaciones -->
<div>
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-zinc-800 dark:text-zinc-200">
            Planificación por Trimestre
        </h3>
        <x-button wire:click="openPlanificacionModal" class="{{ !$actividadEnFormulacion ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}" :disabled="!$actividadEnFormulacion">
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
                                            
                                            // Verificar comentarios para cada planificación individual
                                            $comentariosPlan = $actividad->revisiones()
                                                ->where('tipo', 'PLANIFICACION')
                                                ->where('idElemento', $planificacion['id'])
                                                ->orderBy('created_at', 'desc')
                                                ->get();
                                            $ultimoComentarioPlan = $comentariosPlan->first();
                                            
                                            // Verificar si puede editar esta planificación
                                            $tieneRevisionPendiente = $ultimoComentarioPlan && !$ultimoComentarioPlan->corregido;
                                            $puedeEditarPlanificacion = $actividadEnFormulacion || $tieneRevisionPendiente;
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
                                                            class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 cursor-pointer {{ !$puedeEditarPlanificacion ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}"
                                                            {{ !$puedeEditarPlanificacion ? 'disabled' : '' }}
                                                            title="Editar planificación">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </button>
                                                    <button wire:click="openDeletePlanificacionModal({{ $planificacion['id'] }})"
                                                            class="text-red-600 hover:text-red-800 dark:text-red-400 cursor-pointer {{ !$actividadEnFormulacion ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}"
                                                            {{ !$actividadEnFormulacion ? 'disabled' : '' }}
                                                            title="Eliminar planificación">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        {{-- Fila de comentarios para esta planificación específica --}}
                                        @if($comentariosPlan->isNotEmpty() && !$ultimoComentarioPlan->corregido)
                                            <tr>
                                                <td colspan="5" class="px-4 py-2 bg-purple-50 dark:bg-purple-900/10">
                                                    <div x-data="{ open: false }">
                                                        <div class="flex items-center justify-between">
                                                            <button @click="open = !open" type="button" class="flex items-center gap-2 text-purple-700 dark:text-purple-300 hover:text-purple-900 dark:hover:text-purple-100 transition-colors flex-1">
                                                                <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-90' : ''" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                                                </svg>
                                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                                                                </svg>
                                                                <span class="text-sm font-semibold">Comentarios de revisión</span>
                                                            </button>
                                                            <span class="text-xs text-purple-600 dark:text-purple-400">{{ $ultimoComentarioPlan->created_at->format('d/m/Y H:i') }}</span>
                                                            @if(!$ultimoComentarioPlan->corregido)
                                                                @php
                                                                    // Verificar si la planificación se actualizó después del comentario
                                                                    $planificacionActualizada = \Carbon\Carbon::parse($planificacion['updated_at'])->isAfter($ultimoComentarioPlan->created_at);
                                                                @endphp
                                                                @if($planificacionActualizada)
                                                                    <button wire:click="marcarPlanificacionCorregida({{ $planificacion['id'] }})" 
                                                                            class="ml-3 inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-md transition">
                                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                                        </svg>
                                                                        Marcar como Corregido
                                                                    </button>
                                                                @endif
                                                            @else
                                                                <span class="ml-3 inline-flex items-center px-3 py-1.5 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-xs font-semibold rounded-md">
                                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                    Corregido
                                                                </span>
                                                            @endif
                                                        </div>
                                                        
                                                        <div x-show="open" x-collapse class="mt-2">
                                                            <div class="bg-white dark:bg-zinc-800 rounded-lg p-3 shadow-sm">
                                                                <p class="text-sm text-zinc-700 dark:text-zinc-300">{{ $ultimoComentarioPlan->revision }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
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
