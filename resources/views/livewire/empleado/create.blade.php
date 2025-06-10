<x-dialog-modal maxWidth="md">
    <x-slot name="title">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white"> Crear Empleado</h3>
            <button wire:click="closeModal" type="button"
                class="text-zinc-400 bg-transparent hover:bg-zinc-200 hover:text-zinc-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-zinc-600 dark:hover:text-white">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg>
                <span class="sr-only">Cerrar modal</span>
            </button>
        </div>
    </x-slot>

    <x-slot name="content">
        <form id="userForm" class="space-y-4">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-label for="dni" value="{{ __('DNI') }}" />
                    <x-input id="dni" type="text" class="mt-1 block w-full" />
                    @error('dni') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-label for="nEmpleado" value="{{ __('N° Empleado') }}" />
                    <x-input id="nEmpleado" type="text" class="mt-1 block w-full" />
                    @error('nEmpleado') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-label for="nombre" value="{{ __('Nombre') }}" />
                    <x-input id="nombre" type="text" class="mt-1 block w-full" wire:model="nombre" />
                    @error('nombre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-label for="apellido" value="{{ __('Apellido') }}" />
                    <x-input id="apellido" type="text" class="mt-1 block w-full" wire:model="apellido" />
                    @error('apellido') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-label for="telefono" value="{{ __('Teléfono') }}" />
                    <x-input id="telefono" type="text" class="mt-1 block w-full" wire:model="telefono" />
                    @error('telefono') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-label for="fechaNacimiento" value="{{ __('Fecha de Nacimiento') }}" />
                    <x-input id="fechaNacimiento" type="date" class="mt-1 block w-full" wire:model="fechaNacimiento" />
                    @error('fechaNacimiento') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <x-label for="direccion" value="{{ __('Dirección') }}" />
                <x-input id="direccion" type="text" class="mt-1 block w-full" wire:model="direccion" />
                @error('direccion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <x-label for="Departamento" value="{{ __('Departamentos') }}" />
                <div class="mt-1">
                    <div class="relative">
                        <select id="departamento-select"
                            class="block w-full rounded-md border-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300"
                            wire:model="selectedDepartamento">
                            <option value="">Seleccione un departamento</option>
                            @foreach($departamentos as $departamento)
                                <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <svg class="w-4 h-4 text-zinc-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <button type="button" wire:click="addDepartamento"
                        class="mt-2 px-3 py-1 text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md">
                        Añadir departamento
                    </button>
                </div>

                <!-- Departamentos seleccionados -->
                <div class="mt-3">
                    <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        Departamentos seleccionados:
                    </p>
                    <div class="flex flex-wrap gap-2 mt-2">
                        @forelse($selectedDepartamentos as $index => $deptoId)
                            @php
                                $departamentoNombre = $departamentos->where('id', $deptoId)->first()->nombre ?? 'Desconocido';
                            @endphp
                            <div
                                class="flex items-center gap-1 px-2 py-1 bg-indigo-100 dark:bg-indigo-900 dark:text-indigo-200 text-indigo-800 text-sm rounded-md">
                                <span>{{ $departamentoNombre }}</span>
                                <button wire:click="removeDepartamento({{ $index }})" type="button"
                                    class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    <span class="sr-only">Eliminar</span>
                                </button>
                            </div>
                        @empty
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 italic">
                                Ningún departamento seleccionado
                            </p>
                        @endforelse
                    </div>
                </div>

                @error('selectedDepartamentos')
                    <span class="text-red-500 text-sm block mt-1">{{ $message }}</span>
                @enderror
            </div>
        </form>
    </x-slot>

    <x-slot name="footer">
        <div class="flex justify-end gap-x-4">
            <x-secondary-button wire:click="closeModal" wire:loading.attr="disabled">
                {{ __('Cancelar') }}
            </x-secondary-button>

            <x-button wire:click="store" wire:loading.attr="disabled">
                Guardar
            </x-button>
        </div>
    </x-slot>
</x-dialog-modal>