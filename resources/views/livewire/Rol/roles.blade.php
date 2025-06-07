<div>
    <x-nav current_module="configuracion">
        <!-- Los enlaces se cargarán automáticamente según el módulo -->

        <x-slot name="actions">
            <!-- Aquí podemos agregar más botones o elementos a la derecha -->
            <div class="ml-3">
                <!-- Notificaciones 
                <button
                    class="text-zinc-500 dark:text-white hover:bg-zinc-100 dark:hover:bg-zinc-700 focus:outline-none rounded-lg text-sm p-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                        </path>
                    </svg>
                </button>-->
            </div>
        </x-slot>
    </x-nav>
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 rounded-lg mt-8 sm:mt-10 lg:mt-12 mb-6">
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow sm:rounded-lg p-4 sm:p-6">

            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <span class="block sm:inline">{{ session('message') }}</span>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                <h2 class="text-xl font-semibold text-zinc-800 dark:text-zinc-200">{{ __('Administración de Roles')}}
                </h2>

                <div class="flex flex-col sm:flex-row w-full sm:w-auto space-y-3 sm:space-y-0 sm:space-x-2">
                    <div class="relative w-full sm:w-auto">
                        <input wire:model.live="search" type="text" placeholder="Buscar roles..."
                            class="w-full pl-10 pr-4 py-2 border rounded-md dark:bg-zinc-700 dark:text-zinc-200 dark:border-zinc-600">
                        <div class="absolute left-3 top-2.5">
                            <svg class="h-5 w-5 text-zinc-500 dark:text-zinc-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <x-button wire:click="create" class="w-full sm:w-auto justify-center">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('Nuevo rol')}}
                    </x-button>
                </div>
            </div>

            <!-- Vista de tabla para pantallas medianas y grandes -->
            <div class="hidden md:block overflow-x-auto mt-6">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                ID</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                Rol</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                Descripción</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                Permisos</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:bg-zinc-800 dark:divide-zinc-700">
                        @forelse ($roles as $role)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-300">{{ $role->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-300">{{ $role->name }}
                                </td>
                                <td class="px-6 py-4  text-zinc-900 dark:text-zinc-300">{{ $role->description }}
                                </td>
                                <td class="px-6 py-4 text-zinc-900 dark:text-zinc-300">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($role->permissions as $permission)
                                            <span
                                                class="bg-zinc-100 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-300 px-2 py-1 rounded-full text-xs">
                                                {{ $permission->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button wire:click="edit({{ $role->id }})"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path
                                                    d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                <path fill-rule="evenodd"
                                                    d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDelete({{ $role->id }})"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-zinc-500 dark:text-zinc-400">
                                    {{ __('No se encontraron roles')}}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Vista de tarjetas para pantallas pequeñas (móviles) -->
            <div class="md:hidden space-y-4 mt-6">
                @forelse ($roles as $role)
                    <div
                        class="bg-white dark:bg-zinc-800 p-4 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <span
                                    class="bg-zinc-100 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-300 px-2 py-1 rounded-full text-xs">
                                    ID: {{ $role->id }}
                                </span>
                            </div>
                            <div class="flex space-x-2">
                                <button wire:click="edit({{ $role->id }})"
                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                        <path fill-rule="evenodd"
                                            d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <button wire:click="confirmDelete({{ $role->id }})"
                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <h3 class="font-semibold text-zinc-900 dark:text-zinc-200 text-lg mb-2">{{ $role->name }}</h3>
                        <div class="mt-2">
                            <h4 class="text-sm font-medium text-zinc-500 dark:text-zinc-400 mb-1">{{__('Permisos:')}}</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($role->permissions as $permission)
                                    <span
                                        class="bg-zinc-100 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-300 px-2 py-1 rounded-full text-xs">
                                        {{ $permission->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div
                        class="bg-white dark:bg-zinc-800 p-4 rounded-lg shadow text-center text-zinc-500 dark:text-zinc-400">
                        {{__('No se encontraron roles')}}
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $roles->links() }}
            </div>
        </div>
    </div>

    <!-- Modal para crear/editar rol -->
    @include('livewire.Rol.create')

    <!-- Modal de confirmación para eliminar -->
    @include('livewire.Rol.delete-confirmation')
</div>