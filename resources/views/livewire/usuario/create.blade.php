<x-dialog-modal wire:model="isOpen" maxWidth="md">
    <x-slot name="title">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $isEditing ? 'Editar' : 'Nuevo' }} Usuario</h3>
            <button wire:click="closeModal" type="button" class="text-zinc-400 bg-transparent hover:bg-zinc-200 hover:text-zinc-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-zinc-600 dark:hover:text-white">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
                <span class="sr-only">Cerrar modal</span>
            </button>
        </div>
    </x-slot>

    <x-slot name="content">
        <form id="userForm" class="space-y-4">
            <div>
                <x-label for="name" value="{{ __('Nombre') }}" />
                <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" placeholder="Ingrese el nombre" />
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <x-label for="email" value="{{ __('Correo Electrónico') }}" />
                <x-input id="email" type="email" class="mt-1 block w-full" wire:model="email" placeholder="Ingrese el correo electrónico" />
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <x-label for="idEmpleado" value="{{ __('Empleado (Opcional)') }}" />
                <div x-data="{
                    open: false,
                    search: '',
                    selected: @entangle('idEmpleado'),
                    empleados: {{ json_encode($empleados->map(fn($e) => ['id' => $e->id, 'text' => $e->nombre . ' ' . $e->apellido . ' - ' . $e->dni])->values()) }},
                    get filteredEmpleados() {
                        if (this.search === '') return this.empleados;
                        return this.empleados.filter(empleado => 
                            empleado.text.toLowerCase().includes(this.search.toLowerCase())
                        );
                    },
                    get selectedText() {
                        if (!this.selected) return 'Seleccione un empleado';
                        const empleado = this.empleados.find(e => e.id == this.selected);
                        return empleado ? empleado.text : 'Seleccione un empleado';
                    },
                    selectEmpleado(id) {
                        this.selected = id;
                        this.open = false;
                        this.search = '';
                    },
                    clearSelection() {
                        this.selected = null;
                        this.search = '';
                    }
                }" 
                @click.away="open = false"
                class="relative">
                    <!-- Input de búsqueda / Display -->
                    <button 
                        @click="open = !open" 
                        type="button"
                        class="relative w-full bg-white dark:bg-zinc-900 border border-zinc-300 dark:border-zinc-700 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-pointer focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <span class="block truncate" x-text="selectedText" :class="!selected ? 'text-zinc-400' : 'text-zinc-900 dark:text-zinc-300'"></span>
                        <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                            <svg class="h-5 w-5 text-zinc-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </button>

                    <!-- Dropdown -->
                    <div 
                        x-show="open"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute z-50 mt-1 w-full bg-white dark:bg-zinc-800 shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
                        style="display: none;">
                        
                        <!-- Campo de búsqueda -->
                        <div class="sticky top-0 z-10 bg-white dark:bg-zinc-800 px-2 py-2 border-b border-zinc-200 dark:border-zinc-700">
                            <input 
                                x-model="search"
                                type="text"
                                class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Buscar empleado..."
                                @click.stop
                            />
                        </div>

                        <!-- Opción para limpiar selección -->
                        <div 
                            @click="clearSelection()"
                            class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-400 dark:text-zinc-500 italic">
                            Sin asignar
                        </div>

                        <!-- Lista de opciones -->
                        <template x-for="empleado in filteredEmpleados" :key="empleado.id">
                            <div 
                                @click="selectEmpleado(empleado.id)"
                                class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-indigo-50 dark:hover:bg-indigo-900/20"
                                :class="selected == empleado.id ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-900 dark:text-indigo-100' : 'text-zinc-900 dark:text-zinc-300'">
                                <span class="block truncate" x-text="empleado.text"></span>
                                <span 
                                    x-show="selected == empleado.id"
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-indigo-600 dark:text-indigo-400">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </div>
                        </template>

                        <!-- Mensaje cuando no hay resultados -->
                        <div 
                            x-show="filteredEmpleados.length === 0"
                            class="px-3 py-2 text-zinc-500 dark:text-zinc-400 text-sm italic">
                            No se encontraron empleados
                        </div>
                    </div>
                </div>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                    Asociar este usuario con un empleado permite gestionar departamentos y permisos específicos.
                </p>
                @error('idEmpleado') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <x-label for="password" value="{{ __('Contraseña') }}" />
                <x-input id="password" type="password" class="mt-1 block w-full" wire:model="password" placeholder="Ingrese la contraseña" />
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                    {{ $isEditing ? 'Dejar en blanco para mantener la contraseña actual.' : 'La contraseña debe tener al menos 8 caracteres.' }}
                </p>
                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <x-label for="password_confirmation" value="{{ __('Confirmar Contraseña') }}" />
                <x-input id="password_confirmation" type="password" class="mt-1 block w-full" wire:model="password_confirmation" placeholder="Confirme la contraseña" />
                @error('password_confirmation') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <x-label for="roles" value="{{ __('Roles') }}" />
                <div class="mt-2 grid grid-cols-1 gap-3 max-h-60 overflow-y-auto p-2 border border-zinc-200 dark:border-zinc-700 rounded-md">
                    @forelse($roles ?? [] as $role)
                        @if(is_object($role))
                        <div class="py-1">
                            <x-toggle wire:model="selectedRoles" 
                                value="{{ $role->id }}">
                                {{ $role->name }}
                            </x-toggle>
                        </div>
                        @endif
                    @empty
                        <p class="text-zinc-500 dark:text-zinc-400">No hay roles disponibles</p>
                    @endforelse
                </div>
                @error('selectedRoles') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </form>
    </x-slot>

    <x-slot name="footer">
        <div class="flex justify-end gap-x-4">
            <x-spinner-secondary-button 
                wire:click="closeModal" 
                type="button"
                loadingTarget="closeModal"
                loadingText="Cerrando...">
                {{ __('Cancelar') }}
            </x-spinner-secondary-button>

            <x-spinner-button 
                type="submit" 
                wire:click="store"
                loadingTarget="store" 
                :loadingText="$isEditing ? 'Actualizando...' : 'Creando...'">
                {{ $isEditing ? 'Actualizar Usuario' : 'Crear Usuario' }}
            </x-spinner-button>
        </div>
    </x-slot>
</x-dialog-modal>