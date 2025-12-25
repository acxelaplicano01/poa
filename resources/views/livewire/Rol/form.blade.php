<div class="mx-auto rounded-lg mt-8 sm:mt-6 lg:mt-4 mb-6">
    <!-- Mensajes de sesión -->
    @if (session()->has('message'))
        @include('rk.default.notifications.notification-alert', [
            'type' => 'success',
            'dismissible' => true,
            'icon' => true,
            'duration' => 5,
            'slot' => session('message')
        ])
    @endif

    @if (session()->has('error'))
        @include('rk.default.notifications.notification-alert', [
            'type' => 'error',
            'dismissible' => true,
            'icon' => true,
            'duration' => 8,
            'slot' => session('error')
        ])
    @endif

    <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg">
        <!-- Header -->
        <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-zinc-900 dark:text-white">
                        {{ $isEditing ? 'Editar Rol' : 'Crear Nuevo Rol' }}
                    </h1>
                    <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                        {{ $isEditing ? 'Modifica los datos del rol y sus permisos.' : 'Crea un nuevo rol y asigna los permisos correspondientes.' }}
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <x-spinner-secondary-button wire:click="cancel" type="button" loadingTarget="cancel" loadingText="Cerrando...">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        {{ __('Cancelar') }}
                    </x-spinner-secondary-button>
                    
                    <x-spinner-button type="submit" wire:click="store" loadingTarget="store" :loadingText="$isEditing ? 'Actualizando...' : 'Creando...'">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ $isEditing ? 'Actualizar Rol' : 'Crear Rol' }}
                    </x-spinner-button>
                </div>
            </div>
        </div>

        <!-- Contenido -->
        <div class="p-6">
            <form id="roleForm" class="space-y-6">
                <!-- Información básica del rol -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                            Nombre del rol *
                        </label>
                        <x-input wire:model="name" type="text" name="name"
                            class="form-input block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                            id="name" placeholder="Ingrese el nombre del rol"/>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                            Descripción
                        </label>
                        <x-input wire:model="description" type="text" name="description"
                            class="form-input block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                            id="description" placeholder="Ingrese la descripción del rol"/>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Sección de permisos -->
                <div class="border-t border-zinc-200 dark:border-zinc-700 pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-medium text-zinc-900 dark:text-white">Permisos del Rol</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                                Selecciona los permisos que tendrá este rol. Los permisos están organizados por módulos y funcionalidades.
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="button" 
                                onclick="document.querySelectorAll('input[name=\'selectedPermissions[]\']').forEach(cb => cb.checked = true); 
                                         document.querySelectorAll('input[name=\'selectedPermissions[]\']').forEach(cb => cb.dispatchEvent(new Event('change')));"
                                class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                Seleccionar todo
                            </button>
                            <span class="text-zinc-300 dark:text-zinc-600">|</span>
                            <button type="button" 
                                onclick="document.querySelectorAll('input[name=\'selectedPermissions[]\']').forEach(cb => cb.checked = false); 
                                         document.querySelectorAll('input[name=\'selectedPermissions[]\']').forEach(cb => cb.dispatchEvent(new Event('change')));"
                                class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                Deseleccionar todo
                            </button>
                        </div>
                    </div>

                    <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-4 bg-zinc-50 dark:bg-zinc-900">
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
                        <div class="space-y-6">
                            @forelse($permissionTree as $parentKey => $parentData)
                                <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-4 bg-white dark:bg-zinc-800">
                                    <div x-data="{ open: true }" class="permission-group">
                                        <!-- Cabecera del grupo (permiso padre) -->
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center">
                                                @if($parentData['id'] !== null)
                                                    <x-toggle wire:model="selectedPermissions" name="selectedPermissions[]"
                                                        value="{{ $parentData['id'] }}">
                                                        <span class="text-base font-semibold">Módulo de {{ $parentData['name'] }}</span>
                                                    </x-toggle>
                                                @else
                                                    <div class="w-11 h-6"></div>
                                                    <span class="ms-3 text-base font-semibold text-zinc-800 dark:text-zinc-200">
                                                        Módulo de {{ $parentData['name'] }}
                                                    </span>
                                                @endif
                                            </div>

                                            @if(count($parentData['children']) > 0)
                                                <button @click="open = !open" type="button"
                                                    class="flex items-center text-sm text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-300">
                                                    <span x-text="open ? 'Ocultar' : 'Mostrar'" class="mr-1"></span>
                                                    <svg x-show="!open" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                    <svg x-show="open" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>

                                        <!-- Permisos hijos (funcionalidades) -->
                                        <div x-show="open" class="ml-6 space-y-3">
                                            @foreach($parentData['children'] as $child)
                                                @if(count($child['children']) > 0)
                                                    <!-- Funcionalidad con acciones (crear, editar, eliminar) -->
                                                    <div x-data="{ childOpen: true }" class="border-l-2 border-zinc-200 dark:border-zinc-600 pl-4">
                                                        <div class="flex items-center justify-between mb-2">
                                                            <div class="flex items-center">
                                                                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                                                    {{ $child['display'] }}
                                                                </span>
                                                            </div>
                                                            <button @click="childOpen = !childOpen" type="button"
                                                                class="flex items-center text-xs text-zinc-400 dark:text-zinc-500 hover:text-zinc-600 dark:hover:text-zinc-300">
                                                                <span x-text="childOpen ? 'Ocultar' : 'Mostrar'" class="mr-1"></span>
                                                                <svg x-show="!childOpen" class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                                </svg>
                                                                <svg x-show="childOpen" class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        
                                                        <!-- Acciones de la funcionalidad -->
                                                        <div x-show="childOpen" class="ml-4 space-y-2">
                                                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2">
                                                                @foreach($child['children'] as $action)
                                                                    <div class="flex items-center">
                                                                        <x-toggle wire:model="selectedPermissions" name="selectedPermissions[]"
                                                                            value="{{ $action['id'] }}">
                                                                            {{ $action['display'] }}
                                                                        </x-toggle>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <!-- Funcionalidad sin acciones anidadas -->
                                                    <div class="border-l-2 border-zinc-200 dark:border-zinc-600 pl-4">
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
                                <div class="text-center py-8 text-zinc-500 dark:text-zinc-400">
                                    <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p>No hay permisos jerárquicos disponibles</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Permisos independientes -->
                        @if(count($standalonePermissions) > 0)
                            <div class="mt-6 pt-6 border-t border-zinc-200 dark:border-zinc-700">
                                <h4 class="text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-3">Otros permisos</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($standalonePermissions as $permission)
                                        <div class="flex items-center">
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
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </form>
        </div>

        <!-- Footer fijo -->
        <div class="sticky bottom-0 bg-white dark:bg-zinc-800 px-6 py-4 border-t border-zinc-200 dark:border-zinc-700">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                <div class="text-sm text-zinc-600 dark:text-zinc-400">
                    <span id="selectedCount">0</span> permisos seleccionados
                </div>
                <div class="flex items-center space-x-3">
                    <x-secondary-button wire:click="cancel" type="button">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancelar
                    </x-secondary-button>
                    <x-button wire:click="store" type="button">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ $isEditing ? 'Actualizar Rol' : 'Crear Rol' }}
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateSelectedCount() {
        const checkedBoxes = document.querySelectorAll('input[name="selectedPermissions[]"]:checked');
        const count = checkedBoxes.length;
        const countElement = document.getElementById('selectedCount');
        if (countElement) {
            countElement.textContent = count;
        }
    }

    // Actualizar contador cuando cambien los checkboxes
    document.addEventListener('change', function(e) {
        if (e.target.name === 'selectedPermissions[]') {
            updateSelectedCount();
        }
    });

    // Actualizar contador inicial
    updateSelectedCount();
});
</script>
@endpush
