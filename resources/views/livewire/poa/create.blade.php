
    <x-modal wire:model="showModal" maxWidth="2xl">
        <div class="px-6 py-4">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">
                {{ $isEditing ? 'Editar POA' : 'Crear Nuevo POA' }}
            </h3>
            
            <form wire:submit="save">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nombre del POA -->
                    <div class="md:col-span-2">
                        <x-label for="name" value="{{ __('Nombre del POA') }}" class="mb-2" />
                        <x-input wire:model="name" id="name" type="text" placeholder="Ej: POA 2024 - Plan Operativo Anual" class="mt-1 block w-full" />
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
                </div>

                <!-- Información adicional -->
                <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700 dark:text-blue-200">
                                <strong>Información:</strong> El POA (Plan Operativo Anual) es el documento que establece las actividades, metas y recursos necesarios para el funcionamiento de la institución durante un año específico.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end mt-6 space-x-3">
                    <x-secondary-button wire:click="closeModal" type="button">
                        {{ __('Cancelar') }}
                    </x-secondary-button>
                    
                    <x-button type="submit" wire:loading.attr="disabled" class="flex items-center">
                        <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="save">
                            {{ $isEditing ? __('Actualizar') : __('Crear') }}
                        </span>
                        <span wire:loading wire:target="save">
                            {{ $isEditing ? __('Actualizando...') : __('Creando...') }}
                        </span>
                    </x-button>
                </div>
            </form>
        </div>
    </x-modal>