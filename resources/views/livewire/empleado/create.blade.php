<!-- Modal para crear/editar empleado -->
    <x-dialog-modal wire:model="isOpen" maxWidth="lg">
        <x-slot name="title">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">
                    {{ $isEditing ? 'Editar Empleado' : 'Crear Empleado' }}
                </h3>
                <button type="button" wire:click="closeModal"
                    class="text-zinc-400 bg-transparent hover:bg-zinc-200 hover:text-zinc-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-zinc-600 dark:hover:text-white">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Cerrar modal</span>
                </button>
            </div>
        </x-slot>

        <x-slot name="content">
            <form class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-label for="dni" value="DNI" />
                        <x-input id="dni" type="text" class="mt-1 block w-full" wire:model="dni" placeholder="Ingrese el DNI" />
                        @error('dni') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-label for="num_empleado" value="Número de Empleado" />
                        <x-input id="num_empleado" type="text" class="mt-1 block w-full" wire:model="num_empleado" placeholder="Ingrese número de empleado" />
                        @error('num_empleado') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-label for="nombre" value="Nombre" />
                        <x-input id="nombre" type="text" class="mt-1 block w-full" wire:model="nombre" placeholder="Ingrese el nombre" />
                        @error('nombre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-label for="apellido" value="Apellido" />
                        <x-input id="apellido" type="text" class="mt-1 block w-full" wire:model="apellido" placeholder="Ingrese el apellido" />
                        @error('apellido') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-label for="telefono" value="Teléfono" />
                        <x-input id="telefono" type="text" class="mt-1 block w-full" wire:model="telefono" placeholder="Ingrese el teléfono" />
                        @error('telefono') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-label for="fechaNacimiento" value="Fecha de Nacimiento" />
                        <x-input id="fechaNacimiento" type="date" class="mt-1 block w-full"
                            wire:model="fechaNacimiento" />
                        @error('fechaNacimiento') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <div>
                        <x-label for="sexo" value="Sexo" />
                        <x-select 
                            id="sexo" 
                            wire:model="sexo"
                            :options="[
        ['value' => 'M', 'text' => 'Masculino'],
        ['value' => 'F', 'text' => 'Femenino'],
    ]"
                            :placeholder="'Seleccione el sexo'"
                            :has-error="$errors->has('sexo')"
                            class="mt-1"
                        />
                        @error('sexo') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-label for="direccion" value="Dirección" />
                        <x-input id="direccion" type="text" class="mt-1 block w-full" wire:model="direccion" placeholder="Ingrese la dirección" />
                        @error('direccion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div>
                    <x-label for="idUnidadEjecutora" value="Unidad Ejecutora" />
                    <x-select 
                        id="idUnidadEjecutora" 
                        wire:model.live="idUnidadEjecutora"
                        :options="collect($unidadesEjecutoras)->map(fn($ue) => ['value' => $ue['id'], 'text' => $ue['name']])->prepend(['value' => '', 'text' => 'Seleccione una unidad ejecutora'])->toArray()"
                        :has-error="$errors->has('idUnidadEjecutora')"
                        class="mt-1"
                    />
                    @error('idUnidadEjecutora') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <!-- Selector de departamentos con buscador integrado y lista hacia arriba -->
                <div x-data="{ 
                    open: false, 
                    search: '', 
                    get filteredDepartamentos() {
                        return this.search === '' 
                            ? $wire.departamentos 
                            : $wire.departamentos.filter(dept => 
                                dept.name.toLowerCase().includes(this.search.toLowerCase())
                            );
                    }
                }" class="relative">
                    <x-label for="departamentos" value="Departamentos" />
                    
                    <!-- Mensaje informativo cuando no hay unidad ejecutora seleccionada -->
                    @if(!$idUnidadEjecutora)
                        <div class="mt-1 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-yellow-600 dark:text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm text-yellow-800 dark:text-yellow-200">
                                    Seleccione una unidad ejecutora para ver los departamentos disponibles
                                </span>
                            </div>
                        </div>
                    @else
                        <!-- Contenedor del selector de departamentos -->
                        <div class="mt-1 relative">
                            <!-- Input de búsqueda como selector principal -->
                            <div class="relative">
                                <x-input
                                    x-model="search"
                                    @focus="open = true"
                                    @input="open = true"
                                    type="text"
                                    placeholder="{{ count($selectedDepartamentos) > 0 ? count($selectedDepartamentos) . ' departamento(s) seleccionado(s)' : 'Buscar y seleccionar departamentos...' }}"
                                    class="block w-full"
                                    @keydown.escape.prevent="open = false"
                                    @click="open = !open; $event.stopPropagation();"
                                />
                                <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <button type="button" @click="open = !open; $event.stopPropagation();"
                                        class="text-zinc-600 hover:text-zinc-700 dark:hover:text-zinc-300 focus:outline-none">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Lista desplegable de departamentos (ahora aparece arriba) -->
                            <div x-show="open" @click.away="open = false"
                                class="absolute bottom-full mb-1 w-full z-50 bg-white border dark:border-zinc-700 dark:bg-zinc-800 shadow-lg rounded-md py-1 text-base overflow-auto focus:outline-none sm:text-sm"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95">
                                
                                <!-- Lista de departamentos filtrados -->
                                <div class="max-h-60 overflow-y-auto">
                                    <template x-for="departamento in filteredDepartamentos" :key="departamento.id">
                                        <button type="button" @click="$wire.addDepartamento(departamento.id); search = '';"
                                            class="w-full text-left px-4 py-2 text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700"
                                            :class="{ 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-900 dark:text-indigo-200': $wire.selectedDepartamentos.includes(departamento.id), 'text-zinc-900 dark:text-zinc-300': !$wire.selectedDepartamentos.includes(departamento.id) }">
                                            <span x-text="departamento.name"></span>
                                        </button>
                                    </template>
                                    
                                    <!-- Mensaje cuando no hay resultados -->
                                    <div 
                                        x-show="filteredDepartamentos.length === 0" 
                                        class="px-4 py-2 text-sm text-zinc-500 dark:text-zinc-400 text-center">
                                        No se encontraron departamentos
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mostrar departamentos seleccionados como tags -->
                        <div class="mt-3">
                            <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                                Departamentos seleccionados:
                            </p>
                            <div class="flex flex-wrap gap-2">
                                @forelse($selectedDepartamentos as $index => $deptId)
                                    @php
        $dept = collect($departamentos)->firstWhere('id', $deptId);
                                    @endphp
                                    @if($dept)
                                        <div
                                            class="inline-flex items-center px-2 py-1 rounded-md text-sm bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                                            <span>{{ $dept['name'] }}</span>
                                            <button 
                                                type="button" 
                                                wire:click="removeDepartamento({{ $index }})"
                                                loadingTarget="removeDepartamento({{ $index }})"
                                                loadingText=""
                                                variant="ghost"
                                                size="sm"
                                                class="ml-2 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 p-0">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endif
                                @empty
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400 italic">
                                        Ningún departamento seleccionado
                                    </p>
                                @endforelse
                            </div>
                        </div>
                    @endif
                    
                    @error('selectedDepartamentos') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </form>
        </x-slot>

        <x-slot name="footer">
            <div class="flex justify-end gap-x-4">
                <x-spinner-secondary-button wire:click="closeModal" type="button" loadingTarget="closeModal" loadingText="Cerrando...">
                    {{ __('Cancelar') }}
                </x-spinner-secondary-button>
                
                <x-spinner-button type="submit" wire:click="store" loadingTarget="store" :loadingText="$isEditing ? 'Actualizando...' : 'Creando...'">
                    {{ $isEditing ? __('Actualizar') : __('Crear') }}
                </x-spinner-button>
            </div>
        </x-slot>
    </x-dialog-modal>