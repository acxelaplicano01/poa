<x-modal 
    wire:model="showModal" 
    maxWidth="xl"
    x-on:close="$wire.closeModal()"
>
    <div  x-data="{
        techos: $wire.entangle('techos').live,
        get totalTechos() {
            return this.techos.reduce((sum, techo) => sum + (parseFloat(techo.monto) || 0), 0).toFixed(2);
        }
    }" 
    class="px-6 py-4"
>
        <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">
            {{ $isEditing ? 'Editar POA' : 'Crear Nuevo POA' }}
        </h3>
        
        <form wire:submit.prevent="save">            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombre del POA -->
                <div class="md:col-span-2">
                    <x-label for="name" value="{{ __('Nombre del POA') }}" class="mb-2" />
                    <x-input 
                        id="name" 
                        wire:model="name"
                        type="text"
                        placeholder="Ingrese el nombre del POA Nacional"
                        class="mt-1 block w-full"
                    />
                    <x-input-error for="name" class="mt-2" />
                </div>

                <!-- Año -->
                <div>
                    <x-label for="anio" value="{{ __('Año') }}" class="mb-2" />
                    <x-year-picker 
                        id="anio" 
                        wire:model="anio"
                        class="mt-1 block w-full"
                    />
                    <x-input-error for="anio" class="mt-2" />
                </div>

                <!-- Institución -->
                <div>
                    <x-label for="idInstitucion" value="{{ __('Institución') }}" class="mb-2" />
                    <x-select 
                        id="idInstitucion" 
                        wire:model="idInstitucion"
                        :options="$instituciones->map(fn($institucion) => ['value' => $institucion->id, 'text' => $institucion->nombre])->prepend(['value' => '', 'text' => 'Seleccione una institución'])->toArray()"
                        class="mt-1 block w-full"
                    />
                    <x-input-error for="idInstitucion" class="mt-2" />
                </div>

                <!-- Nota informativa sobre UEs -->
                <div class="md:col-span-2">
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-500 dark:text-blue-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">
                                    POA Nacional - Gestión Centralizada
                                </h3>
                                <div class="mt-2 text-sm text-blue-700 dark:text-blue-400">
                                    <p>Este POA permitirá gestionar presupuesto para todas las unidades ejecutoras de la institución de forma centralizada. La asignación específica a cada UE se realizará después de crear el POA.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Título para Techo Presupuestario -->
                <div class="md:col-span-2 mt-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="text-lg font-medium text-zinc-900 dark:text-zinc-100 border-b border-zinc-200 dark:border-zinc-700 pb-2">
                                Techos Presupuestarios
                            </h4>
                            <div class="flex items-center mt-1">
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">Máximo 3 techos presupuestarios</p>
                            </div>
                        </div>
                        <x-spinner-secondary-button 
                            type="button" 
                            wire:click="addTecho" 
                            loadingTarget="addTecho"
                            loadingText="Agregando..."
                            :disabled="count($techos) >= 3">
                            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Agregar Techo
                        </x-spinner-secondary-button>
                    </div>
                    @if (session()->has('error'))
                        <div class="mb-3 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                                <div class="text-sm text-red-700 dark:text-red-300">
                                    {!! session('error') !!}
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (session()->has('warning'))
                        <div class="mb-3 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-yellow-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="text-sm text-yellow-700 dark:text-yellow-300">
                                    {{ session('warning') }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Techos Presupuestarios Dinámicos -->
                <div class="md:col-span-2">
                    @foreach($techos as $index => $techo)
                        <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-4 mb-4 bg-zinc-50 dark:bg-zinc-800/50">
                            <div class="flex items-center justify-between mb-3">
                                <h5 class="font-medium text-zinc-900 dark:text-zinc-100">
                                    Techo Presupuestario {{ $index + 1 }}
                                </h5>
                                @if(count($techos) > 1)
                                    <x-spinner-danger-button 
                                        type="button" 
                                        wire:click="removeTecho({{ $index }})"
                                        loadingTarget="removeTecho({{ $index }})"
                                        loadingText="Eliminando..."
                                        class="!p-1 !bg-transparent !border-0 !text-red-600 hover:!text-red-800 dark:!text-red-400 dark:hover:!text-red-300">
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </x-spinner-danger-button>
                                @endif
                            </div>
                            @if($isEditing && isset($techo['id']))
                                @php
                                    $montoMinimo = $this->getMontoMinimo($techo['id']);
                                @endphp
                                @if($montoMinimo > 0)
                                    <div class="mb-2 p-2 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-md">
                                        <div class="flex items-center text-orange-700 dark:text-orange-300">
                                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="text-sm">
                                                Mínimo permitido: {{ number_format($montoMinimo, 2) }} (último monto asignado)
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            @endif
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Fuente de Financiamiento -->
                                <div>
                                    <x-label for="techos.{{ $index }}.idFuente" value="{{ __('Fuente') }}" class="mb-2" />
                                    <x-select 
                                        id="techos.{{ $index }}.idFuente" 
                                        wire:model.live="techos.{{ $index }}.idFuente"
                                        :options="$this->getFuentesDisponibles($index)"
                                        class="mt-1 block w-full"
                                    />
                                    <x-input-error for="techos.{{ $index }}.idFuente" class="mt-2" />
                                </div>
                                <!-- Monto -->
                                <div>
                                    <x-label for="techos.{{ $index }}.monto" value="{{ __('Monto') }}" class="mb-2" />
                                    <x-input 
                                        x-model="techos[{{ $index }}].monto"
                                        id="techos.{{ $index }}.monto" 
                                        type="number" 
                                        step="0.01" 
                                        min="{{ $isEditing && isset($techo['id']) ? $this->getMontoMinimo($techo['id']) : 0 }}" 
                                        placeholder="{{ $isEditing && isset($techo['id']) && $this->getMontoMinimo($techo['id']) > 0 ? number_format($this->getMontoMinimo($techo['id']), 2) : '0.00' }}" 
                                        class="mt-1 block w-full"
                                        x-bind:class="{ 'border-orange-300 dark:border-orange-700': {{ $isEditing && isset($techo['id']) && $this->getMontoMinimo($techo['id']) > 0 ? 'true' : 'false' }} }"
                                    />
                                    <x-input-error for="techos.{{ $index }}.monto" class="mt-2" />
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Resumen de Presupuesto -->
            <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-100 dark:border-blue-800">
                <h4 class="font-medium text-blue-800 dark:text-blue-300 mb-2">Resumen de Presupuesto</h4>
                <div class="flex justify-between items-center">
                    <span class="text-blue-700 dark:text-blue-400">Total asignado:</span>
                    <span class="text-lg font-bold text-blue-800 dark:text-blue-300" x-text="new Intl.NumberFormat('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalTechos)"></span>
                </div>
                @if($isEditing)
                    <div class="mt-2 pt-2 border-t border-blue-200 dark:border-blue-700">
                        <div class="flex items-center text-blue-600 dark:text-blue-400">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm">
                                Al editar, no puede asignar menos del último monto asignado a cada techo
                            </span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Botones -->
            <div class="flex justify-end mt-6 space-x-3">
                <x-spinner-secondary-button 
                    wire:click="closeModal" 
                    type="button"
                    loadingTarget="closeModal"
                    loadingText="Cerrando...">
                    {{ __('Cancelar') }}
                </x-spinner-secondary-button>
                
                <x-spinner-button 
                type="submit" 
                loadingTarget="save" 
                :loadingText="$isEditing ? __('Actualizando...') : __('Creando...')">
                    {{ $isEditing ? __('Actualizar POA') : __('Crear POA') }}
                </x-spinner-button>
            </div>
            
        </form>
    </div>
</x-modal>