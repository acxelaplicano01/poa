<div>
    <div class=" mx-auto rounded-lg mt-8 sm:mt-6 lg:mt-4 mb-6">
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

            <div class="mb-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <h2 class="text-xl font-semibold text-zinc-800 dark:text-zinc-200">
                        {{ __('Administración de Empleados') }}</h2>

                    <div class="flex flex-col sm:flex-row w-full sm:w-auto space-y-3 sm:space-y-0 sm:space-x-2">
                        <div class="relative w-full sm:w-auto">
                            <x-input wire:model.live="search" type="text" placeholder="Buscar empleados..."
                                class="w-full pl-10 pr-4 py-2" />
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
                        @can('configuracion.empleados.crear')
                            <x-spinner-button wire:click="create()" loadingTarget="create()" :loadingText="__('Abriendo...')">
                                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                {{ __('Nuevo Empleado') }}
                            </x-spinner-button>
                        @endcan
                    </div>
                </div>
            </div>

            <x-table
                sort-field="{{ $sortField }}"
                sort-direction="{{ $sortDirection }}"
                :columns="[
                    ['key' => 'id', 'label' => '#', 'sortable' => true],
                    ['key' => 'num_empleado', 'label' => 'N° empleado', 'sortable' => true],
                    ['key' => 'nombre', 'label' => 'Nombre', 'sortable' => true],
                    ['key' => 'departamentos', 'label' => 'Departamentos'],
                    ['key' => 'actions', 'label' => 'Acciones', 'class' => 'text-right'],
                ]"
                empty-message="No se encontraron empleados"
                class="mt-6"
            >
                <x-slot name="desktop">
                    @forelse($empleados as $empleado)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-300">{{ $empleado->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-300">{{ $empleado->num_empleado }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-300">{{ $empleado->nombre }} {{ $empleado->apellido }}</td>
                            <td class="px-6 py-4 text-zinc-900 dark:text-zinc-300">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($empleado->departamentos as $departamento)
                                        <span
                                            class="bg-zinc-100 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-300 px-2 py-1 rounded-full text-xs">
                                            {{ $departamento->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    @can('configuracion.empleados.editar')
                                        <button wire:click="edit({{ $empleado->id }})"
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
                                    @endcan
                                    @can('configuracion.empleados.eliminar')
                                        <button wire:click="confirmDelete({{ $empleado->id }})"
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
                                No se encontraron empleados
                            </td>
                        </tr>
                    @endforelse
                </x-slot>

                <x-slot name="mobile">
                    @forelse($empleados as $empleado)
                        <div
                            class="bg-white dark:bg-zinc-800 p-4 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <span
                                        class="bg-zinc-100 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-300 px-2 py-1 rounded-full text-xs">
                                        # {{ $empleado->num_empleado }}
                                    </span>
                                </div>
                                <div class="flex space-x-2">
                                    @can('configuracion.empleados.editar')
                                        <button wire:click="edit({{ $empleado->id }})"
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
                                    @endcan
                                    @can('configuracion.empleados.eliminar')
                                        <button wire:click="confirmDelete({{ $empleado->id }})"
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
                            <h3 class="font-semibold text-zinc-900 dark:text-zinc-200 text-lg mb-1">
                                {{ $empleado->nombre }} {{ $empleado->apellido }}
                            </h3>
                            <p class="text-zinc-600 dark:text-zinc-400 text-sm mb-2">DNI: {{ $empleado->dni }}</p>

                            <div class="mt-2">
                                <h4 class="text-sm font-medium text-zinc-500 dark:text-zinc-400 mb-1">Departamentos:</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($empleado->departamentos as $departamento)
                                        <span
                                            class="bg-zinc-100 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-300 px-2 py-1 rounded-full text-xs">
                                            {{ $departamento->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @empty
                        <div
                            class="bg-white dark:bg-zinc-800 p-4 rounded-lg shadow text-center text-zinc-500 dark:text-zinc-400">
                            No se encontraron empleados
                        </div>
                    @endforelse
                </x-slot>

                <x-slot name="footer">
                    {{ $empleados->links() }}
                </x-slot>
            </x-table>
        </div>
    </div>

    <!-- Modal para crear/editar Empleado -->
    @include('livewire.empleado.create')

    <!-- modal para errores-->
    @include('livewire.empleado.error-modal')

    <!-- Modal de confirmación para eliminar -->
    @include('livewire.empleado.delete-confirmation')
</div>