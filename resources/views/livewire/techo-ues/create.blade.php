 <!-- Modal para Crear/Editar Techo -->
    <x-modal wire:model="showModal" maxWidth="lg">
        <div class="px-6 py-4">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">
                {{ $isEditing ? 'Editar Techo Presupuestario' : 'Crear Techo Presupuestario' }}
            </h3>
            
            <form wire:submit.prevent="save">            
                <!-- Selector de Unidad Ejecutora -->
                @if($idUnidadEjecutora && !$isEditing)
                    <!-- UE preseleccionada (solo lectura) -->
                    <div class="mb-6">
                        <x-label value="{{ __('Unidad Ejecutora Seleccionada') }}" class="mb-2" />
                        <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-md">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h6m-6 4h6m-6 4h6" />
                                </svg>
                                <span class="text-sm font-medium text-indigo-900 dark:text-indigo-100">
                                    {{ collect($unidadesEjecutoras)->firstWhere('id', $idUnidadEjecutora)?->name ?? 'Unidad Ejecutora seleccionada' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Select de UE (para modo edición o creación libre) -->
                    <div class="mb-6">
                        <x-label for="idUnidadEjecutora" value="{{ __('Unidad Ejecutora') }}" class="mb-2" />
                        <x-select 
                            id="idUnidadEjecutora" 
                            wire:model.live="idUnidadEjecutora"
                            :options="collect($unidadesEjecutoras)->map(fn($ue) => ['value' => $ue->id, 'text' => $ue->name . ' (' . ($ue->descripcion ?? 'Sin descripción') . ')'])->prepend(['value' => '', 'text' => 'Seleccione una unidad ejecutora'])->toArray()"
                            class="mt-1 block w-full"
                            :disabled="$isEditing"
                        />
                        <x-input-error for="idUnidadEjecutora" class="mt-2" />
                    </div>
                @endif

                <!-- Asignación de montos por fuente -->
                <div class="mb-6">
                    <x-label value="{{ __('Asignación de Presupuesto por Fuente') }}" class="mb-4" />
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4">
                        Asigne montos desde las diferentes fuentes de financiamiento disponibles. Solo se crearán asignaciones para montos mayores a cero.
                    </p>
                    
                    <!-- Total General Asignado -->
                    @php
                        $totalAsignado = array_sum(array_map('floatval', $montosPorFuente));
                    @endphp
                    <div class="mb-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-700 rounded-lg">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-blue-900 dark:text-blue-100">Total a Asignar:</span>
                            <span class="text-lg font-bold text-blue-900 dark:text-blue-100">L. {{ number_format($totalAsignado, 2) }} </span>
                        </div>
                    </div>

                    @if(count($fuentes) > 0)
                        <div class="space-y-4">
                            @foreach($fuentes as $fuente)
                                @php
                                    $disponibilidad = $this->getDisponibilidadFuente($fuente->id);
                                    $montoActual = floatval($montosPorFuente[$fuente->id] ?? 0);
                                    $maxMonto = $disponibilidad['disponible'] + $montoActual;
                                    $minMonto = $disponibilidad['minimo'];
                                @endphp
                                
                                <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-4 bg-zinc-50 dark:bg-zinc-800/50">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3">
                                        <div class="flex items-center mb-2 sm:mb-0">
                                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mr-3">
                                                <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                                   {{ $fuente->nombre ?? 'Sin fuente' }}
                                                </h4>
                                                <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                                    Total: {{ number_format($disponibilidad['total'], 2) }}
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <div class="text-right">
                                            <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                                Disponible: <span class="font-semibold text-green-600 dark:text-green-400">{{ number_format($disponibilidad['disponible'], 2) }}</span>
                                            </p>
                                            <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                                Usado: {{ number_format($disponibilidad['porcentaje'], 1) }}%
                                            </p>
                                            @if($isEditing && $minMonto > 0)
                                                <p class="text-xs text-orange-600 dark:text-orange-400">
                                                    Mínimo: {{ number_format($minMonto, 2) }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center space-x-3">
                                        <x-label for="monto_{{ $fuente->id }}" value="Monto a asignar" class="text-sm whitespace-nowrap" />
                                        <x-input 
                                            id="monto_{{ $fuente->id }}" 
                                            type="number"
                                            step="0.01"
                                            min="{{ $minMonto }}"
                                            max="{{ $maxMonto }}"
                                            wire:model.live="montosPorFuente.{{ $fuente->id }}"
                                            class="flex-1"
                                            placeholder="{{ $minMonto > 0 ? number_format($minMonto, 2) : '0.00' }}"
                                        />
                                    </div>
                                    
                                    @if($maxMonto <= 0)
                                        <div class="mt-2 flex items-center text-red-600 dark:text-red-400">
                                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                            </svg>
                                            <span class="text-xs">Presupuesto agotado</span>
                                        </div>
                                    @elseif($isEditing && $minMonto > 0)
                                        <div class="mt-2 flex items-center text-orange-600 dark:text-orange-400">
                                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="text-xs">No puede reducirse por debajo de {{ number_format($minMonto, 2) }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="mx-auto h-12 w-12 text-zinc-400 mb-4">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round" 
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-2">
                                No hay fuentes disponibles
                            </h3>
                            <p class="text-zinc-500 dark:text-zinc-400">
                                Debe configurar techos globales primero.
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Botones -->
                <div class="flex justify-end mt-6 space-x-3">
                    <x-spinner-secondary-button wire:click="$set('showModal', false)" type="button" loadingTarget="closeModal" loadingText="Cerrando...">
                        {{ __('Cancelar') }}
                    </x-spinner-secondary-button>
                    
                    <x-spinner-button type="submit" wire:click="save" loadingTarget="save" :loadingText="$isEditing ? 'Actualizando...' : 'Creando...'">
                        {{ $isEditing ? 'Actualizar Techo' : 'Crear Techo' }}
                    </x-spinner-button>
                </div>
            </form>
        </div>
    </x-modal>