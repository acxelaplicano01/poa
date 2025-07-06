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

            <div class="mb-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <h2 class="text-xl font-semibold text-zinc-800 dark:text-zinc-200">
                        {{ __('Administración de Departamentos') }}
                    </h2>

                    <div class="flex flex-col sm:flex-row w-full sm:w-auto space-y-3 sm:space-y-0 sm:space-x-2">
                        <div class="relative w-full sm:w-auto">
                            <x-input wire:model.live="search" type="text" placeholder="Buscar departamentos..."
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
                                id="filtroTipo" 
                                wire:model.live="filtroTipo"
                                :options="array_merge([['value' => 'todos', 'text' => 'Todos los tipos']], $tipos->map(fn($tipo) => ['value' => $tipo, 'text' => $tipo])->toArray())"
                                class="w-full"
                            />
                        </div>
                        <x-spinner-button wire:click="create()" loadingTarget="create()" :loadingText="__('Abriendo...')">
                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            {{ __('Nuevo Departamento') }}
                        </x-spinner-button>
                    </div>
                </div>
            </div>

            <x-table
                sort-field="{{ $sortField ?? 'name' }}"
                sort-direction="{{ $sortDirection ?? 'asc' }}"
                :show-mobile="true"
                :columns="[
        ['key' => 'departamento', 'label' => 'Departamento'],
        ['key' => 'tipo', 'label' => 'Tipo'],
        ['key' => 'estructura', 'label' => 'Estructura'],
        ['key' => 'unidad_ejecutora', 'label' => 'Unidad Ejecutora'],
        ['key' => 'empleados', 'label' => 'Empleados'],
        ['key' => 'actions', 'label' => 'Acciones', 'class' => 'text-right'],
    ]"
                empty-message="No se encontraron departamentos"
                class="mt-6"
            >
                <x-slot name="desktop">
                    @forelse($departamentos as $departamento)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center">
                                            <span class="text-xs font-bold text-white">{{ $departamento->siglas }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                            {{ $departamento->name }}
                                        </div>
                                        <div class="text-xs text-zinc-500 dark:text-zinc-400">
                                            {{ $departamento->siglas }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $departamento->tipo === 'ADMINISTRATIVO' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' :
        ($departamento->tipo === 'COORDINACIÓN REGIONAL' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
            ($departamento->tipo === 'SECCIÓN ACADÉMICA' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' :
                ($departamento->tipo === 'DEPARTAMENTO ACADÉMICO' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                    ($departamento->tipo === 'COORDINACIÓN ACADÉMICA' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' :
                        'bg-zinc-100 text-zinc-800 dark:bg-zinc-900 dark:text-zinc-200')))) }}">
                                    {{ $departamento->tipo }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-100">
                                {{ $departamento->estructura ?? 'Sin definir' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-100">
                                {{ $departamento->unidadEjecutora->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-zinc-100 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-300">
                                    {{ $departamento->empleados_count ?? 0 }} empleados
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <button wire:click="edit({{ $departamento->id }})"
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
                                    <button wire:click="confirmDelete({{ $departamento->id }})"
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
                            <td colspan="6" class="px-6 py-4 text-center">
                                <div class="flex flex-col items-center">
                                    <h3 class="text-md font-medium text-zinc-500 dark:text-zinc-400 mb-1">No hay departamentos</h3>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </x-slot>

                <x-slot name="mobile">
                    @forelse($departamentos as $departamento)
                        <div class="bg-white dark:bg-zinc-800 p-4 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700 mb-4">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <span class="bg-zinc-100 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-300 px-2 py-1 rounded-full text-xs">
                                        ID: {{ $departamento->id }}
                                    </span>
                                </div>
                                <div class="flex space-x-2">
                                    <button wire:click="edit({{ $departamento->id }})"
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
                                    <button wire:click="confirmDelete({{ $departamento->id }})"
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
                            <h3 class="font-semibold text-zinc-900 dark:text-zinc-200 text-lg">{{ $departamento->name }}</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                                {{ $departamento->siglas }}
                            </p>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                                Tipo: 
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $departamento->tipo === 'ADMINISTRATIVO' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' :
        ($departamento->tipo === 'COORDINACIÓN REGIONAL' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
            ($departamento->tipo === 'SECCIÓN ACADÉMICA' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' :
                ($departamento->tipo === 'DEPARTAMENTO ACADÉMICO' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                    ($departamento->tipo === 'COORDINACIÓN ACADÉMICA' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' :
                        'bg-zinc-100 text-zinc-800 dark:bg-zinc-900 dark:text-zinc-200')))) }}">
                                    {{ $departamento->tipo }}
                                </span>
                            </p>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                                Estructura: {{ $departamento->estructura ?? 'Sin definir' }}
                            </p>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                                Unidad Ejecutora: {{ $departamento->unidadEjecutora->name ?? 'N/A' }}
                            </p>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                                Empleados:
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-zinc-100 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-300">
                                    {{ $departamento->empleados_count ?? 0 }} empleados
                                </span>
                            </p>
                        </div>
                    @empty
                        <div class="bg-white dark:bg-zinc-800 p-4 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700 mb-4">
                            <p class="text-center text-zinc-500 dark:text-zinc-400">
                                {{ __('No se encontraron departamentos') }}
                            </p>
                        </div>
                    @endforelse
                </x-slot>
            </x-table>   

            <!-- Paginación -->
            @if($departamentos->hasPages())
                <div class="mt-6">
                    {{ $departamentos->links() }}
                </div>
            @endif
        </div>
    </div>

   @include('livewire.departamento.create')
    @include('livewire.departamento.delete-confirmation')
</div>