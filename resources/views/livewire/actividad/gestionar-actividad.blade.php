<div>
    <div class="mx-auto rounded-lg mt-8 sm:mt-6 lg:mt-4 mb-6">
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow sm:rounded-lg p-4 sm:p-6">
            
            <!-- Encabezado -->
            <div class="mb-6 pb-4 border-b border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center justify-between">
                    <div>
                        <a href="{{ route('actividades', ['idPoa' => $actividad->idPoa, 'departamento' => $actividad->idDeptartamento]) }}" 
                           class="inline-flex items-center text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Volver a Actividades
                        </a>
                        <h2 class="text-2xl font-bold text-zinc-800 dark:text-zinc-200">
                            Gestionar Actividad
                        </h2>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-2">
                            {{ $actividad->nombre }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Stepper -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    @for ($i = 1; $i <= $totalSteps; $i++)
                        <div class="flex-1 {{ $i < $totalSteps ? 'mr-2' : '' }}">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $currentStep >= $i ? 'bg-indigo-600 text-white' : 'bg-zinc-300 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-400' }} font-semibold cursor-pointer"
                                     wire:click="goToStep({{ $i }})">
                                    {{ $i }}
                                </div>
                                <div class="flex-1 ml-2">
                                    <p class="text-sm font-medium {{ $currentStep >= $i ? 'text-indigo-600 dark:text-indigo-400' : 'text-zinc-500 dark:text-zinc-400' }}">
                                        @if ($i == 1) Indicadores
                                        @elseif ($i == 2) Planificaciones
                                        @elseif ($i == 3) Empleados
                                        @elseif ($i == 4) Tareas
                                        @else Confirmación
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        @if ($i < $totalSteps)
                            <div class="w-8 h-1 {{ $currentStep > $i ? 'bg-indigo-600' : 'bg-zinc-300 dark:bg-zinc-700' }}"></div>
                        @endif
                    @endfor
                </div>
            </div>

            <!-- Mensajes de éxito/error -->
            @if (session()->has('message'))
                <div class="mb-4 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-800 dark:text-green-300 px-4 py-3 rounded">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-800 dark:text-red-300 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Contenido de cada paso -->
            <div class="mt-6">
                @if ($currentStep == 1)
                    @include('livewire.actividad.partials.gestionar-paso-indicadores')
                @elseif ($currentStep == 2)
                    @include('livewire.actividad.partials.gestionar-paso-planificaciones')
                @elseif ($currentStep == 3)
                    @include('livewire.actividad.partials.gestionar-paso-empleados')
                @elseif ($currentStep == 4)
                    @include('livewire.actividad.partials.gestionar-paso-tareas')
                @else
                    @include('livewire.actividad.partials.gestionar-paso-confirmacion')
                @endif
            </div>

            <!-- Botones de navegación -->
            <div class="mt-8 flex justify-between border-t border-zinc-200 dark:border-zinc-700 pt-6">
                <div>
                    @if ($currentStep > 1)
                        <x-button wire:click="previousStep" variant="secondary">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Anterior
                        </x-button>
                    @endif
                </div>

                <div class="flex gap-2">
                    @if ($currentStep < $totalSteps)
                        <x-button wire:click="nextStep">
                            Siguiente
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </x-button>
                    @else
                        <x-button wire:click="enviarARevision" class="bg-green-600 hover:bg-green-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Enviar a Revisión
                        </x-button>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Indicadores -->
    <x-dialog-modal wire:model="showIndicadorModal" max-width="2xl">
        <x-slot name="title">
            Agregar Indicador
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div>
                    <x-label for="nombreIndicador" value="Nombre del Indicador" />
                    <x-input id="nombreIndicador" type="text" class="mt-1 block w-full" wire:model="nuevoIndicador.nombre" />
                    @error('nuevoIndicador.nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-label for="descripcionIndicador" value="Descripción" />
                    <textarea id="descripcionIndicador" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200" rows="3" wire:model="nuevoIndicador.descripcion"></textarea>
                    @error('nuevoIndicador.descripcion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-label for="cantidadPlanificada" value="Cantidad Planificada" />
                    <x-input id="cantidadPlanificada" type="number" min="1" class="mt-1 block w-full" wire:model="nuevoIndicador.cantidadPlanificada" />
                    @error('nuevoIndicador.cantidadPlanificada') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center space-x-4">
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="nuevoIndicador.isCantidad" class="rounded border-zinc-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-zinc-700 dark:text-zinc-300">Es Cantidad</span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" wire:model="nuevoIndicador.isPorcentaje" class="rounded border-zinc-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-zinc-700 dark:text-zinc-300">Es Porcentaje</span>
                    </label>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showIndicadorModal', false)">
                Cancelar
            </x-secondary-button>
            <x-button wire:click="saveIndicador" class="ml-2">
                Guardar Indicador
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Modal Planificación -->
    <x-dialog-modal wire:model="showPlanificacionModal" max-width="2xl">
        <x-slot name="title">
            Agregar Planificación
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div>
                    <x-label for="indicadorPlanificacion" value="Indicador" />
                    <select id="indicadorPlanificacion" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200" wire:model="nuevaPlanificacion.idIndicador">
                        <option value="">Seleccione un indicador</option>
                        @foreach($indicadores as $indicador)
                            @php
                                $totalPlanificado = collect($indicador['planificacions'] ?? [])->sum('cantidad');
                                $disponible = $indicador['cantidadPlanificada'] - $totalPlanificado;
                            @endphp
                            <option value="{{ $indicador['id'] }}">
                                {{ $indicador['nombre'] }} (Disponible: {{ $disponible }} de {{ $indicador['cantidadPlanificada'] }})
                            </option>
                        @endforeach
                    </select>
                    @error('nuevaPlanificacion.idIndicador') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    
                    @if($nuevaPlanificacion['idIndicador'])
                        @php
                            $indicadorSeleccionado = collect($indicadores)->firstWhere('id', $nuevaPlanificacion['idIndicador']);
                            if ($indicadorSeleccionado) {
                                $totalPlanificado = collect($indicadorSeleccionado['planificacions'] ?? [])->sum('cantidad');
                                $disponible = $indicadorSeleccionado['cantidadPlanificada'] - $totalPlanificado;
                            }
                        @endphp
                        @if(isset($disponible))
                            <p class="mt-1 text-sm {{ $disponible > 0 ? 'text-blue-600 dark:text-blue-400' : 'text-red-600 dark:text-red-400' }}">
                                Cantidad disponible: <span class="font-semibold">{{ $disponible }}</span> de {{ $indicadorSeleccionado['cantidadPlanificada'] }}
                            </p>
                        @endif
                    @endif
                </div>

                <div>
                    <x-label for="trimestrePlanificacion" value="Trimestre" />
                    <select id="trimestrePlanificacion" 
                            class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200" 
                            wire:model="nuevaPlanificacion.idTrimestre">
                        <option value="">Seleccione un trimestre</option>
                        @foreach($trimestres as $trimestre)
                            <option value="{{ $trimestre['id'] }}">Trimestre {{ $trimestre['trimestre'] }}</option>
                        @endforeach
                    </select>
                    @error('nuevaPlanificacion.idTrimestre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-label for="cantidadPlanificacion" value="Cantidad" />
                    <x-input id="cantidadPlanificacion" type="number" step="0.01" min="0" class="mt-1 block w-full" wire:model="nuevaPlanificacion.cantidad" />
                    @error('nuevaPlanificacion.cantidad') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-label for="fechaInicio" value="Fecha Inicio" />
                        <x-input id="fechaInicio" type="date" class="mt-1 block w-full" wire:model="nuevaPlanificacion.fechaInicio" />
                        @error('nuevaPlanificacion.fechaInicio') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-label for="fechaFin" value="Fecha Fin" />
                        <x-input id="fechaFin" type="date" class="mt-1 block w-full" wire:model="nuevaPlanificacion.fechaFin" />
                        @error('nuevaPlanificacion.fechaFin') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showPlanificacionModal', false)">
                Cancelar
            </x-secondary-button>
            <x-button wire:click="savePlanificacion" class="ml-2">
                Guardar Planificación
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Modal Empleados -->
    <x-dialog-modal wire:model="showEmpleadoModal" max-width="2xl">
        <x-slot name="title">
            Asignar Empleado
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div>
                    <x-label for="empleadoAsignar" value="Empleado" />
                    <select id="empleadoAsignar" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200" wire:model="nuevoEmpleado.idEmpleado">
                        <option value="">Seleccione un empleado</option>
                        @foreach($empleadosDisponibles as $empleado)
                            <option value="{{ $empleado['id'] }}">{{ $empleado['nombre'] }} {{ $empleado['apellido'] }}</option>
                        @endforeach
                    </select>
                    @error('nuevoEmpleado.idEmpleado') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-label for="descripcionEmpleado" value="Descripción del Rol (Opcional)" />
                    <textarea id="descripcionEmpleado" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200" rows="3" wire:model="nuevoEmpleado.descripcion"></textarea>
                    @error('nuevoEmpleado.descripcion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showEmpleadoModal', false)">
                Cancelar
            </x-secondary-button>
            <x-button wire:click="assignEmpleado" class="ml-2">
                Asignar Empleado
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Modal Tareas -->
    <x-dialog-modal wire:model="showTareaModal" max-width="2xl">
        <x-slot name="title">
            Agregar Tarea
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div>
                    <x-label for="nombreTarea" value="Nombre de la Tarea" />
                    <x-input id="nombreTarea" type="text" class="mt-1 block w-full" wire:model="nuevaTarea.nombre" />
                    @error('nuevaTarea.nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-label for="descripcionTarea" value="Descripción" />
                    <textarea id="descripcionTarea" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200" rows="3" wire:model="nuevaTarea.descripcion"></textarea>
                    @error('nuevaTarea.descripcion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center">
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="nuevaTarea.isPresupuesto" class="rounded border-zinc-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-zinc-700 dark:text-zinc-300">Requiere Presupuesto</span>
                    </label>
                </div>
                
                <div class="p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <p class="text-xs text-blue-800 dark:text-blue-300">
                        La tarea se creará en estado "En Revisión" por defecto.
                    </p>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showTareaModal', false)">
                Cancelar
            </x-secondary-button>
            <x-button wire:click="saveTarea" class="ml-2">
                Guardar Tarea
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Modal: Asignar Empleados a Tarea -->
    <x-dialog-modal wire:model="showAsignarEmpleadoTareaModal" max-width="2xl">
        <x-slot name="title">
            Asignar Empleados a la Tarea
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <!-- Empleados Ya Asignados -->
                @if(!empty($empleadosAsignadosTarea))
                    <div>
                        <h4 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mb-2">Empleados Asignados</h4>
                        <div class="space-y-2">
                            @foreach($empleadosAsignadosTarea as $empleado)
                                <div class="flex items-center justify-between p-3 bg-zinc-50 dark:bg-zinc-700 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                            <span class="text-xs text-indigo-600 dark:text-indigo-300 font-semibold">
                                                {{ strtoupper(substr($empleado['nombre'], 0, 1)) }}{{ strtoupper(substr($empleado['apellido'], 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                {{ $empleado['nombre'] }} {{ $empleado['apellido'] }}
                                            </p>
                                            <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                                {{ $empleado['num_empleado'] }}
                                            </p>
                                        </div>
                                    </div>
                                    <button wire:click="removerEmpleadoDeTarea({{ $empleado['id'] }})"
                                            onclick="return confirm('¿Remover este empleado de la tarea?')"
                                            class="text-red-600 hover:text-red-800 dark:text-red-400 text-sm">
                                        Quitar
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Empleados Disponibles -->
                @if(!empty($empleadosDisponiblesTarea))
                    <div>
                        <h4 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mb-2">Empleados Disponibles</h4>
                        <div class="space-y-2">
                            @foreach($empleadosDisponiblesTarea as $empleado)
                                <div class="flex items-center justify-between p-3 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-600 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-8 w-8 rounded-full bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center">
                                            <span class="text-xs text-zinc-600 dark:text-zinc-300 font-semibold">
                                                {{ strtoupper(substr($empleado['nombre'], 0, 1)) }}{{ strtoupper(substr($empleado['apellido'], 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                {{ $empleado['nombre'] }} {{ $empleado['apellido'] }}
                                            </p>
                                            <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                                {{ $empleado['num_empleado'] }}
                                            </p>
                                        </div>
                                    </div>
                                    <button wire:click="asignarEmpleadoATarea({{ $empleado['id'] }})"
                                            class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 text-sm font-medium">
                                        Asignar
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    @if(empty($empleadosAsignadosTarea))
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 text-center py-4">
                            No hay empleados asignados a la actividad.
                        </p>
                    @else
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 text-center py-4">
                            Todos los empleados de la actividad ya están asignados a esta tarea.
                        </p>
                    @endif
                @endif
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showAsignarEmpleadoTareaModal', false)">
                Cerrar
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Modal: Gestionar Presupuesto de Tarea -->
    <x-dialog-modal wire:model="showPresupuestoModal" max-width="4xl">
        <x-slot name="title">
            Gestionar Presupuesto de la Tarea
        </x-slot>

        <x-slot name="content">
            <div class="space-y-6">
                <!-- Formulario para agregar presupuesto -->
                <div class="bg-zinc-50 dark:bg-zinc-700 p-4 rounded-lg space-y-4">
                    <h4 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">Agregar Recurso</h4>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-label for="recursoPresupuesto" value="Recurso (CUB)" />
                            <select id="recursoPresupuesto" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200 text-sm" wire:model="nuevoPresupuesto.idRecurso">
                                <option value="">Seleccione un recurso</option>
                                @foreach($recursosDisponibles as $recurso)
                                    <option value="{{ $recurso['id'] }}">{{ $recurso['nombre'] }}</option>
                                @endforeach
                            </select>
                            @error('nuevoPresupuesto.idRecurso') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-label for="fuentePresupuesto" value="Fuente de Financiamiento" />
                            <select id="fuentePresupuesto" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200 text-sm" wire:model="nuevoPresupuesto.idfuente">
                                <option value="">Seleccione una fuente</option>
                                @foreach($fuentesFinanciamiento as $fuente)
                                    <option value="{{ $fuente['id'] }}">{{ $fuente['nombre'] }}</option>
                                @endforeach
                            </select>
                            @error('nuevoPresupuesto.idfuente') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <x-label for="detalleTecnico" value="Detalle Técnico" />
                        <textarea id="detalleTecnico" rows="2" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200 text-sm" wire:model="nuevoPresupuesto.detalle_tecnico"></textarea>
                        @error('nuevoPresupuesto.detalle_tecnico') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-4 gap-4">
                        <div>
                            <x-label for="unidadMedida" value="Unidad de Medida" />
                            <select id="unidadMedida" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200 text-sm" wire:model="nuevoPresupuesto.idunidad">
                                <option value="">Seleccione</option>
                                @foreach($unidadesMedida as $unidad)
                                    <option value="{{ $unidad['id'] }}">{{ $unidad['nombre'] }}</option>
                                @endforeach
                            </select>
                            @error('nuevoPresupuesto.idunidad') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-label for="costoUnitario" value="Costo Unitario ($)" />
                            <x-input id="costoUnitario" type="number" step="0.01" min="0" class="mt-1 block w-full text-sm" wire:model.live="nuevoPresupuesto.costounitario" />
                            @error('nuevoPresupuesto.costounitario') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-label for="cantidadPresupuesto" value="Cantidad" />
                            <x-input id="cantidadPresupuesto" type="number" step="0.01" min="0.01" class="mt-1 block w-full text-sm" wire:model.live="nuevoPresupuesto.cantidad" />
                            @error('nuevoPresupuesto.cantidad') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-label for="mesEjecucion" value="Mes de Ejecución" />
                            <select id="mesEjecucion" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200 text-sm" wire:model="nuevoPresupuesto.idMes">
                                <option value="">Seleccione</option>
                                @foreach($meses as $mes)
                                    <option value="{{ $mes['id'] }}">Mes {{ $mes['mes'] }}</option>
                                @endforeach
                            </select>
                            @error('nuevoPresupuesto.idMes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-600">
                        <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Total:</span>
                        <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                            ${{ number_format($nuevoPresupuesto['total'], 2) }}
                        </span>
                    </div>

                    <div class="flex justify-end">
                        <x-button wire:click="savePresupuesto">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Agregar Recurso
                        </x-button>
                    </div>
                </div>

                <!-- Lista de presupuestos -->
                @if(!empty($presupuestosTarea))
                    <div>
                        <h4 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mb-3">Recursos Asignados</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                                <thead class="bg-zinc-50 dark:bg-zinc-700">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Recurso</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Detalle</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Fuente</th>
                                        <th class="px-3 py-2 text-center text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Cantidad</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">C. Unit.</th>
                                        <th class="px-3 py-2 text-center text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Mes</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Total</th>
                                        <th class="px-3 py-2 text-center text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                                    @foreach($presupuestosTarea as $presupuesto)
                                        <tr>
                                            <td class="px-3 py-2 text-xs text-zinc-900 dark:text-zinc-100">
                                                {{ $presupuesto['recurso'] }}
                                            </td>
                                            <td class="px-3 py-2 text-xs text-zinc-600 dark:text-zinc-400">
                                                {{ $presupuesto['detalle_tecnico'] }}
                                            </td>
                                            <td class="px-3 py-2 text-xs text-zinc-900 dark:text-zinc-100">
                                                {{ $presupuesto['fuente']['nombre'] ?? 'N/A' }}
                                            </td>
                                            <td class="px-3 py-2 text-xs text-center text-zinc-900 dark:text-zinc-100">
                                                {{ $presupuesto['cantidad'] }} {{ $presupuesto['unidad_medida']['nombre'] ?? '' }}
                                            </td>
                                            <td class="px-3 py-2 text-xs text-right text-zinc-900 dark:text-zinc-100">
                                                ${{ number_format($presupuesto['costounitario'], 2) }}
                                            </td>
                                            <td class="px-3 py-2 text-xs text-center text-zinc-900 dark:text-zinc-100">
                                                {{ $presupuesto['mes']['mes'] ?? 'N/A' }}
                                            </td>
                                            <td class="px-3 py-2 text-xs text-right font-semibold text-indigo-600 dark:text-indigo-400">
                                                ${{ number_format($presupuesto['total'], 2) }}
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                <button wire:click="deletePresupuesto({{ $presupuesto['id'] }})"
                                                        onclick="return confirm('¿Eliminar este recurso?')"
                                                        class="text-red-600 hover:text-red-800 dark:text-red-400">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-zinc-50 dark:bg-zinc-700">
                                    <tr>
                                        <td colspan="6" class="px-3 py-2 text-right text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                            Total Presupuestado:
                                        </td>
                                        <td class="px-3 py-2 text-right text-sm font-bold text-indigo-600 dark:text-indigo-400">
                                            ${{ number_format(collect($presupuestosTarea)->sum('total'), 2) }}
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 text-center py-4">
                        No se han agregado recursos presupuestarios a esta tarea.
                    </p>
                @endif
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showPresupuestoModal', false)">
                Cerrar
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>

</div>
