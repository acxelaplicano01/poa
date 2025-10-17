<x-dialog-modal maxWidth="lg" wire:model="isOpen">
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
                    id="name" placeholder="Ingrese el nombre del rol"/>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description"
                    class="block text-zinc-700 dark:text-zinc-300 font-semibold">Descripción</label>
                <x-input wire:model="description" type="text" name="description"
                    class="form-input mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                    id="description" placeholder="Ingrese la descripción del rol"/>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4">
                <label class="block text-zinc-700 dark:text-zinc-300 font-semibold mb-2">Permisos</label>

                <div class="border border-zinc-200 dark:border-zinc-700 rounded-md p-2 max-h-80 overflow-y-auto">
                    @php
                        // Organizar permisos por jerarquía de 3 niveles
                        $permissionTree = [];
                        $standalonePermissions = [];

                        foreach ($permissions ?? [] as $permission) {
                            $name = $permission->name;
                            $parts = explode('.', $name);

                            if (count($parts) >= 2) {
                                // Tiene al menos 2 partes: módulo.funcionalidad o módulo.funcionalidad.accion
                                $module = $parts[0];
                                $functionality = $parts[1];
                                $action = isset($parts[2]) ? $parts[2] : null;
                                
                                $parentKey = 'acceso-' . $module;

                                // Inicializar el módulo padre si no existe
                                if (!isset($permissionTree[$parentKey])) {
                                    $permissionTree[$parentKey] = [
                                        'id' => null,
                                        'name' => ucfirst($module),
                                        'children' => []
                                    ];
                                }

                                // Si tiene 3 partes (módulo.funcionalidad.accion)
                                if ($action) {
                                    // Buscar o crear la funcionalidad
                                    $functionalityKey = $module . '.' . $functionality;
                                    $functionalityIndex = null;
                                    
                                    foreach ($permissionTree[$parentKey]['children'] as $index => $child) {
                                        if ($child['key'] === $functionalityKey) {
                                            $functionalityIndex = $index;
                                            break;
                                        }
                                    }
                                    
                                    // Si no existe la funcionalidad, crearla
                                    if ($functionalityIndex === null) {
                                        $permissionTree[$parentKey]['children'][] = [
                                            'id' => null,
                                            'key' => $functionalityKey,
                                            'name' => $functionality,
                                            'display' => ucfirst($functionality),
                                            'children' => []
                                        ];
                                        $functionalityIndex = count($permissionTree[$parentKey]['children']) - 1;
                                    }
                                    
                                    // Agregar la acción
                                    $permissionTree[$parentKey]['children'][$functionalityIndex]['children'][] = [
                                        'id' => $permission->id,
                                        'name' => $name,
                                        'display' => ucfirst($action)
                                    ];
                                } else {
                                    // Solo tiene 2 partes (módulo.funcionalidad)
                                    $permissionTree[$parentKey]['children'][] = [
                                        'id' => $permission->id,
                                        'key' => $name,
                                        'name' => $functionality,
                                        'display' => ucfirst($functionality),
                                        'children' => []
                                    ];
                                }
                            } else if (strpos($name, 'acceso-') === 0) {
                                // Es un permiso padre de módulo
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
                                    <div class="flex items-center font-medium text-zinc-800 dark:text-zinc-200">
                                        @if($parentData['id'] !== null)
                                            <x-toggle wire:model="selectedPermissions" name="selectedPermissions[]"
                                                value="{{ $parentData['id'] }}">
                                                Módulo de {{ $parentData['name'] }}
                                            </x-toggle>
                                        @else
                                            <div class="w-11 h-6"></div>
                                            <span class="ms-3 text-sm font-medium">Módulo de {{ $parentData['name'] }}</span>
                                        @endif
                                    </div>

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

                                <!-- Permisos hijos (funcionalidades) -->
                                <div x-show="open" class="ml-6 pl-2 border-l-2 border-zinc-200 dark:border-zinc-700">
                                    @foreach($parentData['children'] as $child)
                                        @if(count($child['children']) > 0)
                                            <!-- Funcionalidad con acciones (crear, editar, eliminar) -->
                                            <div x-data="{ childOpen: false }" class="py-1">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center">
                                                        <span class="text-sm font-medium text-zinc-700 dark:text-zinc-400">{{ $child['display'] }}</span>
                                                    </div>
                                                    <button @click="childOpen = !childOpen" type="button"
                                                        class="text-zinc-400 dark:text-zinc-500 hover:text-zinc-600 dark:hover:text-zinc-300 ml-2">
                                                        <svg x-show="!childOpen" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                        </svg>
                                                        <svg x-show="childOpen" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                                
                                                <!-- Acciones de la funcionalidad -->
                                                <div x-show="childOpen" class="ml-4 mt-2 pl-2 border-l border-zinc-100 dark:border-zinc-600">
                                                    @foreach($child['children'] as $action)
                                                        <div class="py-1">
                                                            <x-toggle wire:model="selectedPermissions" name="selectedPermissions[]"
                                                                value="{{ $action['id'] }}">
                                                                {{ $action['display'] }}
                                                            </x-toggle>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @else
                                            <!-- Funcionalidad sin acciones anidadas -->
                                            <div class="py-1">
                                                <x-toggle wire:model="selectedPermissions" name="selectedPermissions[]"
                                                    value="{{ $child['id'] }}">
                                                    {{ $child['display'] }}
                                                </x-toggle>
                                            </div>
                                        @endif
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
                                    <div class="py-1">
                                        <x-toggle wire:model="selectedPermissions" name="selectedPermissions[]"
                                            value="{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </x-toggle>
                                    </div>
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