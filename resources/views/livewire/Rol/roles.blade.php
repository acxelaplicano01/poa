<div>
    <div class="mx-auto rounded-lg mt-8 sm:mt-6 lg:mt-4 mb-6">
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow sm:rounded-lg p-4 sm:p-6">

            @if (session()->has('message'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md" role="alert">
                    <p class="font-medium">{{ session('message') }}</p>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md" role="alert">
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                <h2 class="text-xl font-semibold text-zinc-800 dark:text-zinc-200">{{ __('Administración de Roles')}}
                </h2>

                <div class="flex flex-col sm:flex-row w-full sm:w-auto space-y-3 sm:space-y-0 sm:space-x-2">
                    <div class="relative w-full sm:w-auto">
                        <x-input wire:model.live="search" type="text" placeholder="Buscar roles..."
                            class="w-full pl-10 pr-4 py-2"/>
                        <div class="absolute left-3 top-2.5">
                            <svg class="h-5 w-5 text-zinc-500 dark:text-zinc-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="w-full sm:w-auto">
                            <x-select 
                                id="perPage" 
                                wire:model.live="perPage"
                                :options="[
                                    ['value' => '10', 'text' => '10 por página'],
                                    ['value' => '25', 'text' => '25 por página'],
                                    ['value' => '50', 'text' => '50 por página'],
                                    ['value' => '100', 'text' => '100 por página'],
                                ]"
                                class="w-full"
                            />
                    </div>
                    @can('configuracion.roles.crear')
                        <a href="{{ route('roles.create') }}" class="inline-flex justify-center items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-800 dark:border-indigo-700 dark:text-white dark:hover:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-700 dark:focus:bg-indigo-900 active:bg-zinc-900 dark:active:bg-indigo-800 focus:outline-none focus:ring-2 dark:focus:ring-indigo-500 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-indigo-800 transition ease-in-out duration-150">
                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            {{ __('Nuevo Rol') }}
                        </a>
                    @endcan
                </div>
            </div>

            <x-table
                sort-field="{{ $sortField }}"
                sort-direction="{{ $sortDirection }}"
                :columns="[
                    ['key' => 'id', 'label' => 'ID', 'sortable' => true],
                    ['key' => 'name', 'label' => 'Rol', 'sortable' => true],
                    ['key' => 'description', 'label' => 'Descripción', 'sortable' => true],
                    ['key' => 'permissions', 'label' => 'Permisos'],
                    ['key' => 'actions', 'label' => 'Acciones'],
                ]"
                empty-message="{{ __('No se encontraron roles')}}"
                class="mt-6"
            >
                <x-slot name="desktop">
                    @forelse ($roles as $role)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-300">
                                {{ $role->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-300">
                                {{ $role->name }}
                            </td>
                            <td class="px-6 py-4 text-zinc-900 dark:text-zinc-300">
                                {{ $role->description }}
                            </td>
                            <td class="px-6 py-4 text-zinc-900 dark:text-zinc-300">
                                @if($role->permissions->count() > 0)
                                    <div class="max-w-xs">
                                        @php
                                            // Agrupar permisos por módulo
                                            $groupedPermissions = [];
                                            foreach($role->permissions as $permission) {
                                                $parts = explode('.', $permission->name);
                                                if (count($parts) >= 2) {
                                                    $module = $parts[0];
                                                    $action = implode('.', array_slice($parts, 1));
                                                    if (!isset($groupedPermissions[$module])) {
                                                        $groupedPermissions[$module] = [];
                                                    }
                                                    $groupedPermissions[$module][] = $action;
                                                } else {
                                                    // Si no tiene estructura de módulo, ponerlo en "general"
                                                    if (!isset($groupedPermissions['general'])) {
                                                        $groupedPermissions['general'] = [];
                                                    }
                                                    $groupedPermissions['general'][] = $permission->name;
                                                }
                                            }
                                        @endphp
                                        
                                        @if(count($groupedPermissions) <= 2)
                                            <!-- Si hay pocos módulos, mostrar expandido -->
                                            @foreach($groupedPermissions as $module => $actions)
                                                <div class="mb-2">
                                                    <span class="inline-block bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 px-2 py-1 rounded text-xs font-medium mb-1">
                                                        {{ ucfirst($module) }}
                                                    </span>
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach($actions as $action)
                                                            <span class="bg-zinc-100 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 px-1.5 py-0.5 rounded text-xs">
                                                                {{ $action }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <!-- Si hay muchos módulos, mostrar resumen -->
                                            <div class="text-sm">
                                                <span class="font-medium text-zinc-600 dark:text-zinc-400">
                                                    {{ $role->permissions->count() }} permisos
                                                </span>
                                                <div class="mt-1">
                                                    @foreach(array_slice(array_keys($groupedPermissions), 0, 3) as $module)
                                                        <span class="inline-block bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 px-2 py-0.5 rounded text-xs mr-1">
                                                            {{ ucfirst($module) }}
                                                        </span>
                                                    @endforeach
                                                    @if(count($groupedPermissions) > 3)
                                                        <span class="text-xs text-zinc-500 dark:text-zinc-400">
                                                            +{{ count($groupedPermissions) - 3 }} más
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-zinc-400 dark:text-zinc-500 text-sm italic">Sin permisos</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    @can('configuracion.roles.editar')
                                        <a href="{{ route('roles.edit', $role->id) }}"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                            title="Editar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                <path fill-rule="evenodd"
                                                    d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('configuracion.roles.eliminar')
                                        <button wire:click="confirmDelete({{ $role->id }})"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                            title="Eliminar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-zinc-500 dark:text-zinc-400">
                                {{ __('No se encontraron roles')}}
                            </td>
                        </tr>
                    @endforelse
                </x-slot>

                <x-slot name="mobile">
                    @forelse ($roles as $role)
                        <div class="bg-white dark:bg-zinc-800 p-4 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <span class="bg-zinc-100 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-300 px-2 py-1 rounded-full text-xs">
                                        ID: {{ $role->id }}
                                    </span>
                                </div>
                                <div class="flex space-x-2">
                                    @can('configuracion.roles.editar')
                                        <a href="{{ route('roles.edit', $role->id) }}"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                            title="Editar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                <path fill-rule="evenodd"
                                                    d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('configuracion.roles.eliminar')
                                        <button wire:click="confirmDelete({{ $role->id }})"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                            title="Eliminar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    @endcan
                                </div>
                            </div>
                            <h3 class="font-semibold text-zinc-900 dark:text-zinc-200 text-lg mb-2">{{ $role->name }}</h3>
                            <p class="text-zinc-600 dark:text-zinc-400 text-sm mb-2">{{ $role->description }}</p>
                            <div class="mt-2">
                                <h4 class="text-sm font-medium text-zinc-500 dark:text-zinc-400 mb-2">{{__('Permisos:')}}</h4>
                                @if($role->permissions->count() > 0)
                                    @php
                                        // Agrupar permisos por módulo para móvil
                                        $groupedPermissions = [];
                                        foreach($role->permissions as $permission) {
                                            $parts = explode('.', $permission->name);
                                            if (count($parts) >= 2) {
                                                $module = $parts[0];
                                                $action = implode('.', array_slice($parts, 1));
                                                if (!isset($groupedPermissions[$module])) {
                                                    $groupedPermissions[$module] = [];
                                                }
                                                $groupedPermissions[$module][] = $action;
                                            } else {
                                                if (!isset($groupedPermissions['general'])) {
                                                    $groupedPermissions['general'] = [];
                                                }
                                                $groupedPermissions['general'][] = $permission->name;
                                            }
                                        }
                                    @endphp
                                    
                                    @foreach($groupedPermissions as $module => $actions)
                                        <div class="mb-3">
                                            <span class="inline-block bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 px-2 py-1 rounded text-xs font-medium mb-1">
                                                {{ ucfirst($module) }}
                                            </span>
                                            <div class="flex flex-wrap gap-1 mt-1">
                                                @foreach($actions as $action)
                                                    <span class="bg-zinc-100 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 px-2 py-1 rounded text-xs">
                                                        {{ $action }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <span class="text-zinc-400 dark:text-zinc-500 text-sm italic">Sin permisos</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="bg-white dark:bg-zinc-800 p-4 rounded-lg shadow text-center text-zinc-500 dark:text-zinc-400">
                            {{__('No se encontraron roles')}}
                        </div>
                    @endforelse
                </x-slot>

                <x-slot name="footer">
                    {{ $roles->links() }}
                </x-slot>
            </x-table>
        </div>
    </div>

    <!-- Modal de confirmación para eliminar -->
    @include('livewire.Rol.delete-confirmation')

    <!-- Modal de error -->
    @include('livewire.Rol.error-modal')
</div>