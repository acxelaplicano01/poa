<div>
    <div class="mx-auto rounded-lg mt-8 sm:mt-6 lg:mt-4 mb-6">
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow sm:rounded-lg p-4 sm:p-6">

            <!-- Encabezado con información del POA -->
            <div class="mb-6 pb-4 border-b border-zinc-200 dark:border-zinc-700">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                    <div>
                        <a href="{{ route('poas') }}" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:underline mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Volver a POAs
                        </a>
                        <h2 class="text-xl font-semibold text-zinc-800 dark:text-zinc-200">
                            Techos Presupuestarios por Departamento
                        </h2>
                        <div class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                            <p>POA: <span class="font-semibold text-zinc-700 dark:text-zinc-300">{{ $poa->anio }}</span></p>
                            <p>Unidad Ejecutora: <span class="font-semibold text-zinc-700 dark:text-zinc-300">{{ $unidadEjecutora->name }}</span></p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row w-full sm:w-auto space-y-2 sm:space-y-0 sm:space-x-3 mt-4 sm:mt-0">
                        <div class="relative w-full sm:w-auto">
                            <x-input wire:model.live="search" type="text" placeholder="Buscar por departamento..."
                                class="w-full pl-10 pr-4 py-2" />
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
                            {{ __('Nuevo Techo Departamental') }}
                        </x-button>
                    </div>
                </div>
            </div>

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

            <!-- Tabla de Techos por Departamento -->
            <div class="mt-6">
                @if($techoDeptos->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                            <thead class="bg-zinc-50 dark:bg-zinc-800">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                        Departamento
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                        POA Departamento
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                        Techo UE / Fuente
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                        Grupo Gasto
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                        Monto
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-800">
                                @foreach($techoDeptos as $techoDepto)
                                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/60">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                            {{ $techoDepto->departamento->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                            {{ $techoDepto->poaDepto->id ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                            @if ($techoDepto->techoUE && $techoDepto->techoUE->fuente)
                                                {{ $techoDepto->techoUE->fuente->nombre }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                            {{ $techoDepto->grupoGasto->nombre ?? 'No definido' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                            {{ number_format($techoDepto->monto, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button wire:click="edit({{ $techoDepto->id }})" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">
                                                Editar
                                            </button>
                                            <button wire:click="confirmDelete({{ $techoDepto->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginación -->
                    <div class="mt-4">
                        {{ $techoDeptos->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="max-w-md mx-auto">
                            <div class="mx-auto h-16 w-16 text-indigo-400 mb-6">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round" 
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-3">
                                No hay techos departamentales asignados
                            </h3>
                            <p class="text-zinc-500 dark:text-zinc-400 mb-8">
                                Empieza asignando techos presupuestarios a los departamentos para gestionar el presupuesto.
                            </p>
                            <div class="flex justify-center">
                                <x-button wire:click="create()" class="bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700">
                                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Crear primer techo departamental
                                </x-button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal para crear/editar techo departamental -->
    <x-modal wire:model="showModal" maxWidth="2xl">
        <div class="px-6 py-4">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">
                {{ $isEditing ? 'Editar Techo Departamental' : 'Crear Nuevo Techo Departamental' }}
            </h3>
            
            <form wire:submit.prevent="save">            
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Departamento -->
                    <div>
                        <x-label for="idDepartamento" value="{{ __('Departamento') }}" class="mb-2" />
                        <x-select 
                            id="idDepartamento" 
                            wire:model.live="idDepartamento"
                            :options="$departamentos->map(fn($depto) => ['value' => $depto->id, 'text' => $depto->name])->prepend(['value' => '', 'text' => 'Seleccione un departamento'])->toArray()"
                            class="mt-1 block w-full"
                        />
                        <x-input-error for="idDepartamento" class="mt-2" />
                    </div>

                    <!-- POA Departamento -->
                    <div>
                        <x-label for="idPoaDepto" value="{{ __('POA Departamento') }}" class="mb-2" />
                        <x-select 
                            id="idPoaDepto" 
                            wire:model="idPoaDepto"
                            :options="$poaDeptos->map(fn($poaDepto) => ['value' => $poaDepto->id, 'text' => 'POA Depto #' . $poaDepto->id])->prepend(['value' => '', 'text' => 'Seleccione un POA Departamento'])->toArray()"
                            class="mt-1 block w-full"
                        />
                        <x-input-error for="idPoaDepto" class="mt-2" />
                    </div>

                    <!-- Techo UE / Fuente -->
                    <div>
                        <x-label for="idTechoUE" value="{{ __('Techo UE / Fuente') }}" class="mb-2" />
                        <x-select 
                            id="idTechoUE" 
                            wire:model="idTechoUE"
                            :options="$techoUes->map(fn($techoUe) => ['value' => $techoUe->id, 'text' => $techoUe->fuente->nombre . ' - ' . number_format($techoUe->monto, 2)])->prepend(['value' => '', 'text' => 'Seleccione un Techo UE'])->toArray()"
                            class="mt-1 block w-full"
                        />
                        <x-input-error for="idTechoUE" class="mt-2" />
                    </div>

                    <!-- Grupo de Gastos (Opcional) -->
                    <div>
                        <x-label for="idGrupo" value="{{ __('Grupo de Gastos (Opcional)') }}" class="mb-2" />
                        <x-select 
                            id="idGrupo" 
                            wire:model="idGrupo"
                            :options="$grupoGastos->map(fn($grupo) => ['value' => $grupo->id, 'text' => $grupo->nombre])->prepend(['value' => '', 'text' => 'Seleccione un Grupo de Gastos (Opcional)'])->toArray()"
                            class="mt-1 block w-full"
                        />
                        <x-input-error for="idGrupo" class="mt-2" />
                    </div>

                    <!-- Monto -->
                    <div class="md:col-span-2">
                        <x-label for="monto" value="{{ __('Monto') }}" class="mb-2" />
                        <x-input 
                            id="monto" 
                            type="number"
                            step="0.01"
                            min="0"
                            wire:model="monto"
                            class="mt-1 block w-full"
                            placeholder="0.00"
                        />
                        <x-input-error for="monto" class="mt-2" />
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end mt-6 space-x-3">
                    <x-secondary-button wire:click="closeModal" type="button">
                        {{ __('Cancelar') }}
                    </x-secondary-button>
                    
                    <x-button type="submit" wire:loading.attr="disabled" class="flex items-center">
                        <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="save">
                            {{ $isEditing ? __('Actualizar') : __('Crear') }}
                        </span>
                        <span wire:loading wire:target="save">
                            {{ $isEditing ? __('Actualizando...') : __('Creando...') }}
                        </span>
                    </x-button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Modal de confirmación de eliminación -->
    <x-confirmation-modal wire:model="showDeleteModal">
        <x-slot name="title">
            Eliminar Techo Departamental
        </x-slot>

        <x-slot name="content">
            ¿Está seguro que desea eliminar este techo departamental? Esta acción no se puede deshacer.
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeDeleteModal">
                {{ __('Cancelar') }}
            </x-secondary-button>

            <x-danger-button class="ml-3" wire:click="delete" wire:loading.attr="disabled">
                {{ __('Eliminar') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>
