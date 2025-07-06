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

                <!-- Unidad Ejecutora -->
                <div class="md:col-span-2">
                    <x-label for="idUE" value="{{ __('Unidad Ejecutora') }}" class="mb-2" />
                    <x-select 
                        id="idUE" 
                        wire:model="idUE"
                        :options="$unidadesEjecutoras->map(fn($ue) => ['value' => $ue->id, 'text' => $ue->name])->prepend(['value' => '', 'text' => 'Seleccione una unidad ejecutora'])->toArray()"
                        class="mt-1 block w-full"
                    />
                    <x-input-error for="idUE" class="mt-2" />
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
                        <div class="text-sm text-red-600 mb-3">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session()->has('warning'))
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-3">
                            {{ session('warning') }}
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
                                        min="0" 
                                        placeholder="0.00" 
                                        class="mt-1 block w-full"
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