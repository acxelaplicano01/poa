<!-- Paso 3: Empleados Encargados -->
<div>
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-zinc-800 dark:text-zinc-200">
            Empleados Encargados de la Actividad
        </h3>
        @if(!empty($empleadosDisponibles))
            <x-button wire:click="openEmpleadoModal">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Asignar Empleado
            </x-button>
        @endif
    </div>

    @if (empty($empleadosAsignados))
        <div class="text-center py-12 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
            <svg class="mx-auto h-12 w-12 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">Sin empleados asignados</h3>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                Asigna empleados responsables para esta actividad.
            </p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($empleadosAsignados as $empleado)
                <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                    <span class="text-indigo-600 dark:text-indigo-300 font-semibold">
                                        {{ strtoupper(substr($empleado['nombre'], 0, 1)) }}{{ strtoupper(substr($empleado['apellido'], 0, 1)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $empleado['nombre'] }} {{ $empleado['apellido'] }}
                                </p>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                    {{ $empleado['num_empleado'] }}
                                </p>
                                @if($empleado['pivot']['descripcion'])
                                    <p class="text-xs text-zinc-600 dark:text-zinc-400 mt-1">
                                        {{ $empleado['pivot']['descripcion'] }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        @if(auth()->user()->idEmpleado !== $empleado['id'])
                            <button wire:click="removeEmpleado({{ $empleado['id'] }})"
                                    onclick="return confirm('¿Está seguro de remover este empleado?')"
                                    class="text-red-600 hover:text-red-800 dark:text-red-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                Tú
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if(empty($empleadosDisponibles))
        <div class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
            <p class="text-sm text-yellow-800 dark:text-yellow-300">
                <strong>Nota:</strong> Todos los empleados del departamento ya han sido asignados a esta actividad.
            </p>
        </div>
    @else
        <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
            <p class="text-sm text-blue-800 dark:text-blue-300">
                <strong>Nota:</strong> Asigna los empleados que serán responsables de ejecutar y dar seguimiento a esta actividad.
            </p>
        </div>
    @endif
</div>
