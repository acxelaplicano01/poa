<div>
    <div class="flex items-center justify-center px-4 py-12 sm:px-6 lg:px-8 bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-zinc-900 dark:to-zinc-800">
        <div class="w-full bg-white dark:bg-zinc-800 rounded-2xl shadow-2xl overflow-hidden">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-zinc-800 to-zinc-800 px-8 py-6">
                
                <h2 class="text-3xl font-bold text-center text-white">
                    Completa tu Perfil
                </h2>
                <p class="mt-2 text-center text-indigo-100">
                    Para continuar, necesitamos algunos datos adicionales sobre ti para una correcta administración y asignación de roles.
                </p>
            </div>

            {{-- Form --}}
            <div class="px-8 py-8">
            @if (session()->has('error'))
                @include('rk.default.notifications.notification-alert', [
                    'type' => 'error',
                    'dismissible' => true,
                    'icon' => true,
                    'duration' => 8,
                    'slot' => session('error')
                ])
            @endif

                <form wire:submit.prevent="guardar">
                    <div class="space-y-6">
                        {{-- Información Personal --}}
                        <div class="bg-zinc-50 dark:bg-zinc-900/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Información Personal
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- DNI --}}
                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">DNI </label>
                                    <x-input wire:model="dni" type="text" class="w-full" placeholder="0000-0000-00000" />
                                    @error('dni') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Número de Empleado --}}
                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Número de Empleado</label>
                                    <x-input wire:model="num_empleado" type="text" class="w-full" placeholder="Número de empleado" />
                                    @error('num_empleado') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Nombre --}}
                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Nombre </label>
                                    <x-input wire:model="nombre" type="text" class="w-full" />
                                    @error('nombre') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Apellido --}}
                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Apellido </label>
                                    <x-input wire:model="apellido" type="text" class="w-full" />
                                    @error('apellido') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Sexo --}}
                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Sexo </label>
                                    <select wire:model="sexo" class="block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="M">Masculino</option>
                                        <option value="F">Femenino</option>
                                    </select>
                                    @error('sexo') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Fecha de Nacimiento --}}
                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Fecha de Nacimiento</label>
                                    <x-input wire:model="fechaNacimiento" type="date" class="w-full" />
                                    @error('fechaNacimiento') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Teléfono --}}
                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Teléfono</label>
                                    <x-input wire:model="telefono" type="text" class="w-full" placeholder="0000-0000" />
                                    @error('telefono') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Dirección --}}
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Dirección</label>
                                    <x-input wire:model="direccion" type="text" class="w-full" placeholder="Dirección completa" />
                                    @error('direccion') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Información Laboral --}}
                        <div class="bg-zinc-50 dark:bg-zinc-900/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Información Laboral
                            </h3>
                            
                            {{-- Unidad Ejecutora --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Unidad Ejecutora </label>
                                <select wire:model.live="idUnidadEjecutora" class="block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Seleccione una Unidad Ejecutora</option>
                                    @foreach($unidadesEjecutoras as $ue)
                                        <option value="{{ $ue['id'] }}">{{ $ue['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('idUnidadEjecutora') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Selector de departamentos con buscador --}}
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
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Departamentos</label>
                                
                                {{-- Mensaje informativo cuando no hay unidad ejecutora seleccionada --}}
                                @if(!$idUnidadEjecutora)
                                    <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md">
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
                                    {{-- Contenedor del selector de departamentos --}}
                                    <div class="relative">
                                        {{-- Input de búsqueda como selector principal --}}
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

                                        {{-- Lista desplegable de departamentos --}}
                                        <div x-show="open" @click.away="open = false"
                                            class="absolute z-50 mt-1 w-full bg-white border dark:border-zinc-700 dark:bg-zinc-800 shadow-lg rounded-md py-1 text-base overflow-auto focus:outline-none sm:text-sm max-h-60"
                                            x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="transform opacity-0 scale-95"
                                            x-transition:enter-end="transform opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-75"
                                            x-transition:leave-start="transform opacity-100 scale-100"
                                            x-transition:leave-end="transform opacity-0 scale-95">
                                            
                                            {{-- Lista de departamentos filtrados --}}
                                            <div class="max-h-60 overflow-y-auto">
                                                <template x-for="departamento in filteredDepartamentos" :key="departamento.id">
                                                    <button type="button" @click="$wire.addDepartamento(departamento.id); search = '';"
                                                        class="w-full text-left px-4 py-2 text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700"
                                                        :class="{ 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-900 dark:text-indigo-200': $wire.selectedDepartamentos.includes(departamento.id), 'text-zinc-900 dark:text-zinc-300': !$wire.selectedDepartamentos.includes(departamento.id) }">
                                                        <span x-text="departamento.name"></span>
                                                    </button>
                                                </template>
                                                
                                                {{-- Mensaje cuando no hay resultados --}}
                                                <div 
                                                    x-show="filteredDepartamentos.length === 0" 
                                                    class="px-4 py-2 text-sm text-zinc-500 dark:text-zinc-400 text-center">
                                                    No se encontraron departamentos
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Mostrar departamentos seleccionados como tags --}}
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
                                                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                                                        <span>{{ $dept['name'] }}</span>
                                                        <button 
                                                            type="button" 
                                                            wire:click="removeDepartamento({{ $index }})"
                                                            class="ml-2 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 focus:outline-none">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
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
                                
                                @error('selectedDepartamentos') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Nota informativa --}}
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div class="text-sm text-blue-800 dark:text-blue-300">
                                    <p class="font-medium mb-1">Información importante</p>
                                    <p>Esta información será utilizada para identificarte en el sistema y asignar tus responsabilidades laborales.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Mensaje de error general --}}
                        @if (session()->has('error'))
                            @include('rk.default.notifications.notification-alert', [
                                'type' => 'error',
                                'dismissible' => true,
                                'icon' => true,
                                'duration' => 8,
                                'slot' => session('error')
                            ])
                        @endif
                        {{-- Botón de envío --}}
                        <div class="flex items-center justify-end pt-4">
                            <x-spinner-button type="submit" loadingTarget="guardar" :loadingText="'Guardando...'" class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-8 py-3 text-lg font-semibold cursor-pointer">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Completar Registro
                            </x-spinner-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
