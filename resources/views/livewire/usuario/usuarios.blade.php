<div>
    

    <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-6 rounded-lg mt-8 sm:mt-10 lg:mt-12 mb-6">
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow sm:rounded-lg p-4 sm:p-6">

            @if (session()->has('message'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md" role="alert">
                    <p class="font-medium">{{ session('message') }}</p>
                </div>
            @endif

            <div class="mb-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <h2 class="text-xl font-semibold text-zinc-800 dark:text-zinc-200">
                        {{ __('Administración de Usuarios') }}</h2>

                    <div class="flex flex-col sm:flex-row w-full sm:w-auto space-y-3 sm:space-y-0 sm:space-x-2">
                        <div class="relative w-full sm:w-auto">
                            <input wire:model.live="search" type="text" placeholder="Buscar usuarios..."
                                class="w-full pl-10 pr-4 py-2 border rounded-md dark:bg-zinc-700 dark:text-zinc-200 dark:border-zinc-600 focus:ring-indigo-500 focus:border-indigo-500">
                            <div class="absolute left-3 top-2.5">
                                <svg class="h-5 w-5 text-zinc-500 dark:text-zinc-400" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        <x-button wire:click="create()" class="w-full sm:w-auto justify-center">
                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            {{ __('Nuevo Usuario') }}
                        </x-button>
                    </div>
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
                                Nombre</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                Email</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                Roles</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:bg-zinc-800 dark:divide-zinc-700">
                        @forelse($users as $user)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-300">{{ $user->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-300">{{ $user->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-300">{{ $user->email }}
                                </td>
                                <td class="px-6 py-4 text-zinc-900 dark:text-zinc-300">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($user->roles as $role)
                                            <span
                                                class="bg-zinc-100 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-300 px-2 py-1 rounded-full text-xs">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <button wire:click="edit({{ $user->id }})"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                            title="Editar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path
                                                    d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                <path fill-rule="evenodd"
                                                    d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDelete({{ $user->id }})"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                            title="Eliminar">
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
                                <td colspan="5" class="px-6 py-4 text-center text-zinc-500 dark:text-zinc-400">
                                    No se encontraron usuarios
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Vista de tarjetas para pantallas pequeñas (móviles) -->
            <div class="md:hidden space-y-4 mt-6">
                @forelse($users as $user)
                    <div
                        class="bg-white dark:bg-zinc-800 p-4 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <span
                                    class="bg-zinc-100 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-300 px-2 py-1 rounded-full text-xs">
                                    ID: {{ $user->id }}
                                </span>
                            </div>
                            <div class="flex space-x-2">
                                <button wire:click="edit({{ $user->id }})"
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
                                <button wire:click="confirmDelete({{ $user->id }})"
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
                        <h3 class="font-semibold text-zinc-900 dark:text-zinc-200 text-lg mb-1">{{ $user->name }}</h3>
                        <p class="text-zinc-600 dark:text-zinc-400 text-sm mb-2">{{ $user->email }}</p>

                        <div class="mt-2">
                            <h4 class="text-sm font-medium text-zinc-500 dark:text-zinc-400 mb-1">Roles:</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($user->roles as $role)
                                    <span
                                        class="bg-zinc-100 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-300 px-2 py-1 rounded-full text-xs">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div
                        class="bg-white dark:bg-zinc-800 p-4 rounded-lg shadow text-center text-zinc-500 dark:text-zinc-400">
                        No se encontraron usuarios
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- Modal de error -->
    @if (session()->has('error'))
        <x-dialog-modal wire:model="showErrorModal">
            <x-slot name="title">
                <div class="flex items-center">
                    <div class="bg-red-100 dark:bg-red-900/20 rounded-full p-2 mr-2">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-zinc-900 dark:text-white">Error</h3>
                </div>
            </x-slot>

            <x-slot name="content">
                <p>{{ session('error') }}</p>
            </x-slot>

            <x-slot name="footer">
                <div class="flex justify-end">
                    <x-secondary-button wire:click="$set('showErrorModal', false)" wire:loading.attr="disabled">
                        Aceptar
                    </x-secondary-button>
                </div>
            </x-slot>
        </x-dialog-modal>
    @endif

    <!-- Modal para crear/editar Usuario -->
    @include('livewire.Usuario.create')

    <!-- Modal de confirmación para eliminar -->
    @include('livewire.Usuario.delete-confirmation')
</div>