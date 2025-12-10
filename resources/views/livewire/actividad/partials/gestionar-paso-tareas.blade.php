<!-- Paso 4: Tareas -->
<div>
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-zinc-800 dark:text-zinc-200">
            Tareas de la Actividad
        </h3>
        <x-button wire:click="openTareaModal">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Agregar Tarea
        </x-button>
    </div>

    @if (empty($tareas))
        <div class="text-center py-12 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
            <svg class="mx-auto h-12 w-12 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">Sin tareas</h3>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                Comienza agregando tareas específicas para esta actividad.
            </p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                            #
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                            Tarea
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                            Descripción
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                            Estado
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                            Asignados
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                    @foreach($tareas as $tarea)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50">
                            <td class="px-4 py-3 text-sm">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-zinc-100 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-200">
                                    {{ $tarea['correlativo'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                        {{ $tarea['nombre'] }}
                                    </span>
                                    <div class="flex items-center space-x-1 mt-1">
                                        @if($tarea['isPresupuesto'])
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                                                Con Presupuesto
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400 max-w-xs truncate">
                                {{ $tarea['descripcion'] }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($tarea['estado'] == 'REVISION')
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
                                        Revisión
                                    </span>
                                @elseif($tarea['estado'] == 'APROBADO')
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                        Aprobado
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                        Rechazado
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if(!empty($tarea['empleados']))
                                    <div class="flex items-center justify-center">
                                        <div class="flex -space-x-2">
                                            @foreach(array_slice($tarea['empleados'], 0, 3) as $empleado)
                                                <div class="h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center border-2 border-white dark:border-zinc-800"
                                                     title="{{ $empleado['nombre'] }} {{ $empleado['apellido'] }}">
                                                    <span class="text-xs text-indigo-600 dark:text-indigo-300 font-semibold">
                                                        {{ strtoupper(substr($empleado['nombre'], 0, 1)) }}
                                                    </span>
                                                </div>
                                            @endforeach
                                            @if(count($tarea['empleados']) > 3)
                                                <div class="h-8 w-8 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center border-2 border-white dark:border-zinc-800">
                                                    <span class="text-xs text-zinc-600 dark:text-zinc-300 font-semibold">
                                                        +{{ count($tarea['empleados']) - 3 }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center text-xs text-zinc-400">Sin asignar</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center space-x-2">
                                    @if($tarea['isPresupuesto'])
                                        <button wire:click="openPresupuestoModal({{ $tarea['id'] }})"
                                                class="inline-flex items-center p-1.5 text-green-600 hover:text-green-800 dark:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 rounded"
                                                title="Gestionar presupuesto">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                    @endif
                                    
                                    <button wire:click="openAsignarEmpleadoTareaModal({{ $tarea['id'] }})"
                                            class="inline-flex items-center p-1.5 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded"
                                            title="Asignar empleados">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </button>
                                    <button wire:click="editTarea({{ $tarea['id'] }})"
                                            class="inline-flex items-center p-1.5 text-blue-600 hover:text-blue-800 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded"
                                            title="Editar tarea">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button wire:click="openDeleteTareaModal({{ $tarea['id'] }})"
                                            class="inline-flex items-center p-1.5 text-red-600 hover:text-red-800 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded"
                                            title="Eliminar tarea">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
        <p class="text-sm text-blue-800 dark:text-blue-300">
            <strong>Nota:</strong> Las tareas son las acciones específicas que se deben realizar para completar esta actividad.
        </p>
    </div>

    @include('livewire.actividad.delete-confirmation-tarea')

</div>
