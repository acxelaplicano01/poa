<x-dialog-modal wire:model="isOpen">
    <x-slot name="title">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $isEditing ? 'Editar' : 'Nuevo' }} Rol
            </h3>
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
        <form id="roleForm">
            <div class="mb-4">
                <label for="name" class="block text-zinc-700 dark:text-zinc-300 font-semibold">Nombre del rol</label>
                <x-input wire:model="name" type="text" name="name"
                    class="form-input mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                    id="name" />
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description"
                    class="block text-zinc-700 dark:text-zinc-300 font-semibold">Descripción</label>
                <x-input wire:model="description" type="text" name="description"
                    class="form-input mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                    id="description" />
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4">
                <label class="block text-zinc-700 dark:text-zinc-300 font-semibold mb-2">Permisos</label>

                <div class="border border-zinc-200 dark:border-zinc-700 rounded-md p-2 max-h-80 overflow-y-auto">
                    @php
                        // Organizar permisos por jerarquía
                        $permissionTree = [];
                        $standalonePermissions = [];

                        foreach ($permissions ?? [] as $permission) {
                            $name = $permission->name;

                            // Detectar si es un permiso padre o hijo
                            if (strpos($name, '.') !== false) {
                                // Es un permiso hijo
                                list($parent, $child) = explode('.', $name, 2);
                                $parentKey = 'acceso-' . $parent;

                                if (!isset($permissionTree[$parentKey])) {
                                    $permissionTree[$parentKey] = [
                                        'id' => null, // Se actualizará si encontramos este permiso
                                        'name' => ucfirst($parent),
                                        'children' => []
                                    ];
                                }

                                $permissionTree[$parentKey]['children'][] = [
                                    'id' => $permission->id,
                                    'name' => $name,
                                    'display' => ucfirst($child)
                                ];
                            } else if (strpos($name, 'acceso-') === 0) {
                                // Es un permiso padre
                                $module = str_replace('acceso-', '', $name);

                                if (!isset($permissionTree[$name])) {
                                    $permissionTree[$name] = [
                                        'id' => $permission->id,
                                        'name' => ucfirst($module),
                                        'children' => []
                                    ];
                                } else {
                                    $permissionTree[$name]['id'] = $permission->id;
                                }
                            } else {
                                // Es un permiso independiente
                                $standalonePermissions[] = $permission;
                            }
                        }

                        // Ordenar los permisos por nombre
                        ksort($permissionTree);
                    @endphp

                    <!-- Sección de permisos jerárquicos -->
                    @forelse($permissionTree as $parentKey => $parentData)
                        <div class="mb-4 border-b border-zinc-100 dark:border-zinc-800 pb-2 last:border-b-0 last:pb-0">
                            <div x-data="{ open: true }" class="permission-group">
                                <!-- Cabecera del grupo (permiso padre) -->
                                <div class="flex items-center justify-between mb-2">
                                    <label class="flex items-center font-medium text-zinc-800 dark:text-zinc-200">
                                        @if($parentData['id'] !== null)
                                            <x-checkbox wire:model="selectedPermissions" name="selectedPermissions[]"
                                                value="{{ $parentData['id'] }}" class="form-checkbox" />
                                        @else
                                            <div class="w-5 h-5"></div>
                                        @endif
                                        <span class="ml-2">Módulo de {{ $parentData['name'] }}</span>
                                    </label>

                                    @if(count($parentData['children']) > 0)
                                        <button @click="open = !open" type="button"
                                            class="text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-300">
                                            <svg x-show="!open" class="h-5 w-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                            <svg x-show="open" class="h-5 w-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>

                                <!-- Permisos hijos -->
                                <div x-show="open" class="ml-6 pl-2 border-l-2 border-zinc-200 dark:border-zinc-700">
                                    @foreach($parentData['children'] as $child)
                                        <label class="flex items-center py-1">
                                            <x-checkbox wire:model="selectedPermissions" name="selectedPermissions[]"
                                                value="{{ $child['id'] }}" class="form-checkbox" />
                                            <span
                                                class="ml-2 text-sm dark:text-zinc-300 text-zinc-800">{{ $child['display'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- No hay permisos jerárquicos -->
                    @endforelse

                    <!-- Permisos independientes -->
                    @if(count($standalonePermissions) > 0)
                        <div class="mt-3">
                            <h4 class="text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Otros permisos</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 ml-2">
                                @foreach($standalonePermissions as $permission)
                                    <label class="flex items-center">
                                        <x-checkbox wire:model="selectedPermissions" name="selectedPermissions[]"
                                            value="{{ $permission->id }}" class="form-checkbox" />
                                        <span
                                            class="ml-2 text-sm dark:text-zinc-300 text-zinc-800">{{ $permission->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                @error('selectedPermissions')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </form>
    </x-slot>

    <x-slot name="footer">
        <div class="flex justify-end gap-x-4">
            <x-secondary-button wire:click="closeModal" type="button">
                Cancelar
            </x-secondary-button>
            <x-button wire:click="store" type="button">
                {{ $isEditing ? 'Actualizar' : 'Guardar' }}
            </x-button>
        </div>
    </x-slot>
</x-dialog-modal>