    <x-modal wire:model="showModal" maxWidth="2xl">
        <div class="px-6 py-4">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">
                {{ $isEditing ? 'Editar Departamento' : 'Crear Nuevo Departamento' }}
            </h3>
            
            <form wire:submit="save">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nombre -->
                    <div class="md:col-span-2">
                        <x-label for="name" value="{{ __('Nombre del Departamento') }}" class="mb-2" />
                        <x-input wire:model="name" id="name" type="text" class="mt-1 block w-full" />
                        <x-input-error for="name" class="mt-2" />
                    </div>

                    <!-- Siglas -->
                    <div>
                        <x-label for="siglas" value="{{ __('Siglas') }}" class="mb-2" />
                        <x-input wire:model="siglas" id="siglas" type="text" maxlength="10" class="mt-1 block w-full" />
                        <x-input-error for="siglas" class="mt-2" />
                    </div>

                    <!-- Tipo -->
                    <div>
                        <x-label for="tipo" value="{{ __('Tipo') }}" class="mb-2" />
                        <x-select 
                            id="tipo" 
                            wire:model="tipo"
                            :options="[
                                ['value' => '', 'text' => 'Seleccione un tipo'],
                                ['value' => 'ADMINISTRATIVO', 'text' => 'Administrativo'],
                                ['value' => 'COORDINACIÓN REGIONAL', 'text' => 'Coordinación Regional'],
                                ['value' => 'SECCIÓN ACADÉMICA', 'text' => 'Sección Académica'],
                                ['value' => 'DEPARTAMENTO ACADÉMICO', 'text' => 'Departamento Académico'],
                            ]"
                            class="mt-1 block w-full"
                        />
                        <x-input-error for="tipo" class="mt-2" />
                    </div>

                    <!-- Estructura -->
                    <div>
                        <x-label for="estructura" value="{{ __('Estructura') }}" class="mb-2" />
                        <x-input wire:model="estructura" id="estructura" type="text" class="mt-1 block w-full" />
                        <x-input-error for="estructura" class="mt-2" />
                    </div>

                    <!-- Unidad Ejecutora -->
                    <div>
                        <x-label for="idUnidadEjecutora" value="{{ __('Unidad Ejecutora') }}" class="mb-2" />
                        <x-select 
                            id="idUnidadEjecutora" 
                            wire:model="idUnidadEjecutora"
                            :options="array_merge([['value' => '', 'text' => 'Seleccione una unidad ejecutora']], $unidadesEjecutoras->map(fn($unidad) => ['value' => $unidad->id, 'text' => $unidad->name])->toArray())"
                            class="mt-1 block w-full"
                        />
                        <x-input-error for="idUnidadEjecutora" class="mt-2" />
                    </div>
                </div>

                <!-- Botones del modal -->
                <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-zinc-200 dark:border-zinc-700">
                    <x-secondary-button wire:click="closeModal">
                        {{ __('Cancelar') }}
                    </x-secondary-button>
                    <x-button type="submit">
                        {{ $isEditing ? __('Actualizar') : __('Crear') }} {{ __('Departamento') }}
                    </x-button>
                </div>
            </form>
        </div>
    </x-modal>