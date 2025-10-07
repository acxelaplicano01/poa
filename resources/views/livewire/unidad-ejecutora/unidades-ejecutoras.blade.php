<div>
    <div class="mx-auto rounded-lg mt-8 sm:mt-6 lg:mt-4 mb-6">
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow sm:rounded-lg p-4 sm:p-6">

            @if (session()->has('message'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md" role="alert">
                    <p class="font-medium">{{ session('message') }}</p>
                </div>
            @endif

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                <h2 class="text-xl font-semibold text-zinc-800 dark:text-zinc-200">{{ __('Administración de Unidades Ejecutoras') }}
                </h2>

                <div class="flex flex-col sm:flex-row w-full sm:w-auto space-y-3 sm:space-y-0 sm:space-x-2">
                    <div class="relative w-full sm:w-auto">
                        <x-input wire:model.live="search" type="text" placeholder="Buscar unidades ejecutoras..."
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
                    <x-spinner-button wire:click="create()" loadingTarget="create()" :loadingText="__('Abriendo...')">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('Nueva Unidad Ejecutora') }}
                    </x-spinner-button>
                </div>
            </div>

            <x-table
                sort-field="{{ $sortField }}"
                sort-direction="{{ $sortDirection }}"
                :columns="[
                    ['key' => 'id', 'label' => 'ID', 'sortable' => true],
                    ['key' => 'name', 'label' => 'Nombre', 'sortable' => true],
                    ['key' => 'descripcion', 'label' => 'Descripción', 'sortable' => false],
                    ['key' => 'estructura', 'label' => 'Estructura', 'sortable' => true],
                    ['key' => 'institucion', 'label' => 'Institución', 'sortable' => false],
                    ['key' => 'actions', 'label' => 'Acciones', 'sortable' => false]
                ]"
                empty-message="{{ __('No se encontraron unidades ejecutoras')}}"
                class="mt-6"
            >
                <x-slot name="desktop">
                    @forelse($unidadesEjecutoras as $unidadEjecutora)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-300">
                            {{ $unidadEjecutora->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-300">
                            {{ $unidadEjecutora->name }}
                        </td>
                        <td class="px-6 py-4 text-zinc-900 dark:text-zinc-300 max-w-md truncate">
                            {{ $unidadEjecutora->descripcion }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ $unidadEjecutora->estructura }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-300">
                            {{ $unidadEjecutora->institucion->nombre ?? 'Sin institución' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button wire:click="edit({{ $unidadEjecutora->id }})"
                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <button wire:click="confirmDelete({{ $unidadEjecutora->id }})"
                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-zinc-500 dark:text-zinc-400">
                            {{ __('No se encontraron unidades ejecutoras') }}
                        </td>
                    </tr>
                    @endforelse
                </x-slot>
            </x-table>

            @if($unidadesEjecutoras->hasPages())
                <div class="mt-4">
                    {{ $unidadesEjecutoras->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal de Crear/Editar -->
    <x-modal wire:model="isModalOpen" max-width="2xl">
        <x-slot name="title">
            {{ $unidadEjecutoraId ? __('Editar Unidad Ejecutora') : __('Nueva Unidad Ejecutora') }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <x-label for="name" value="{{ __('Nombre') }}" />
                    <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" autocomplete="name" />
                    <x-input-error for="name" class="mt-2" />
                </div>

                <div>
                    <x-label for="descripcion" value="{{ __('Descripción') }}" />
                    <x-textarea id="descripcion" class="mt-1 block w-full" wire:model="descripcion" rows="3" />
                    <x-input-error for="descripcion" class="mt-2" />
                </div>

                <div>
                    <x-label for="estructura" value="{{ __('Estructura') }}" />
                    <x-input id="estructura" type="text" class="mt-1 block w-full" wire:model="estructura" 
                        placeholder="Ej: 0-00-00-00" />
                    <x-input-error for="estructura" class="mt-2" />
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                        Código de estructura organizacional (formato: 0-00-00-00)
                    </p>
                </div>

                <div>
                    <x-label for="idInstitucion" value="{{ __('Institución') }}" />
                    <select 
                        id="idInstitucion" 
                        wire:model="idInstitucion"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    >
                        <option value="">Selecciona una institución</option>
                        @foreach($instituciones as $institucion)
                            <option value="{{ $institucion->id }}">{{ $institucion->nombre }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="idInstitucion" class="mt-2" />
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal">
                {{ __('Cancelar') }}
            </x-secondary-button>

            <x-spinner-button wire:click="store" loadingTarget="store" :loadingText="__('Guardando...')" class="ml-3">
                {{ $unidadEjecutoraId ? __('Actualizar') : __('Crear') }}
            </x-spinner-button>
        </x-slot>
    </x-modal>

    <!-- Modal de Confirmación de Eliminación -->
    <x-confirmation-modal wire:model="showDeleteModal">
        <x-slot name="title">
            {{ __('Eliminar Unidad Ejecutora') }}
        </x-slot>

        <x-slot name="content">
            @if($unidadEjecutoraToDelete)
                {{ __('¿Estás seguro de que deseas eliminar la Unidad Ejecutora') }} "<strong>{{ $unidadEjecutoraToDelete->name }}</strong>"?
                <br><br>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">
                    Esta acción no se puede deshacer. Se verificará que no tenga empleados, departamentos o techos presupuestarios asociados.
                </p>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeDeleteModal">
                {{ __('Cancelar') }}
            </x-secondary-button>

            <x-danger-button class="ml-3" wire:click="delete">
                {{ __('Eliminar') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    <!-- Modal de Error -->
    <x-dialog-modal wire:model="showErrorModal">
        <x-slot name="title">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-red-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ __('Error') }}
            </div>
        </x-slot>

        <x-slot name="content">
            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                {{ $errorMessage }}
            </p>
        </x-slot>

        <x-slot name="footer">
            <x-button wire:click="closeErrorModal">
                {{ __('Entendido') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>