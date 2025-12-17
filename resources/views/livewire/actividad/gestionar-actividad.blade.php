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

            <!-- Comentarios del Supervisor -->
            @if(in_array($actividad->estado, ['REFORMULACION', 'RECHAZADO', 'REVISION']))
                @php
                    $revisionesActividad = $actividad->revisiones()
                        ->whereIn('tipo', ['REVISION', 'DICTAMEN', 'REVISION'])
                        ->orderBy('created_at', 'desc')
                        ->get();
                @endphp
                
                @if($revisionesActividad->isNotEmpty())
                    <div class="mb-6 p-5 rounded-lg {{ $actividad->estado === 'RECHAZADO' ? 'bg-red-50 dark:bg-red-900/20 border-2 border-red-300 dark:border-red-700' : 'bg-orange-50 dark:bg-orange-900/20 border-2 border-orange-300 dark:border-orange-700' }}">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 {{ $actividad->estado === 'RECHAZADO' ? 'text-red-600 dark:text-red-400' : 'text-orange-600 dark:text-orange-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold {{ $actividad->estado === 'RECHAZADO' ? 'text-red-800 dark:text-red-300' : 'text-orange-800 dark:text-orange-300' }} mb-3">
                                    @if($actividad->estado === 'RECHAZADO')
                                        Actividad Rechazada
                                    @else
                                        Se requiere reformulación
                                    @endif
                                </h3>
                                
                                <div class="space-y-3">
                                    @foreach($revisionesActividad as $revision)
                                        <div class="bg-white dark:bg-zinc-800 rounded-lg p-4 shadow-sm">
                                            <div class="flex items-start justify-between mb-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $revision->tipo === 'DICTAMEN' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300' }}">
                                                    @if($revision->tipo === 'DICTAMEN')
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Dictamen Final
                                                    @else
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Mensaje de revisión
                                                    @endif
                                                </span>
                                                <span class="text-xs text-zinc-500 dark:text-zinc-400">
                                                    {{ $revision->created_at->format('d/m/Y H:i') }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-zinc-700 dark:text-zinc-300 whitespace-pre-line">{{ $revision->revision }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            <!-- Stepper Horizontal -->
            <div class="mb-8">
                <div class="flex items-center gap-2">
                    @for ($i = 1; $i <= $totalSteps; $i++)
                        @php
                            $isCompleted = $currentStep > $i;
                            $isActive = $currentStep == $i;
                            $stepLabel = $i == 1 ? 'Indicadores' : ($i == 2 ? 'Planificaciones' : ($i == 3 ? 'Empleados' : ($i == 4 ? 'Tareas' : 'Confirmación')));
                        @endphp
                        
                        <!-- Paso -->
                        <button type="button"
                                wire:click="goToStep({{ $i }})"
                                class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all duration-200 text-sm font-medium whitespace-nowrap
                                        {{ $isActive 
                                            ? 'bg-indigo-600 text-white shadow-md' 
                                            : ($isCompleted
                                                ? 'bg-indigo-600 text-white'
                                                : 'bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-300 dark:hover:bg-zinc-600') }}">
                            <div class="flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold
                                        {{ $isActive || $isCompleted ? 'bg-white/20' : 'bg-white/30' }}">
                                @if ($isCompleted)
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" fill-rule="evenodd" />
                                    </svg>
                                @else
                                    {{ $i }}
                                @endif
                            </div>
                            <span class="hidden sm:inline">{{ $stepLabel }}</span>
                        </button>

                        <!-- Conectador -->
                        @if ($i < $totalSteps)
                            <div class="flex-1 h-1 {{ $currentStep > $i ? 'bg-indigo-600' : 'bg-zinc-300 dark:bg-zinc-700' }} transition-colors duration-200"></div>
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
                        <x-button wire:click="enviarARevision" class="bg-green-600 hover:bg-green-700 {{ !$actividadEnFormulacion ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}" :disabled="!$actividadEnFormulacion">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ !$actividadEnFormulacion ? 'No Editable' : 'Enviar a Revisión' }}
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
                            wire:model.live="nuevaPlanificacion.idTrimestre">
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
                        <x-input id="fechaInicio" type="date" class="mt-1 block w-full" wire:model.live="nuevaPlanificacion.fechaInicio" />
                        @error('nuevaPlanificacion.fechaInicio') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-label for="fechaFin" value="Fecha Fin" />
                        <x-input id="fechaFin" type="date" class="mt-1 block w-full" wire:model.live="nuevaPlanificacion.fechaFin" />
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
                                    <button wire:click="openDeleteEmpleadoTareaModal({{ $empleado['id'] }})"
                                            class="text-red-600 hover:text-red-800 dark:text-red-400 text-sm {{ !$actividadEnFormulacion ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}" :disabled="!$actividadEnFormulacion">
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
                                            class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 text-sm font-medium {{ !$actividadEnFormulacion ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}" :disabled="!$actividadEnFormulacion">
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
                <!-- Información del Techo del Departamento -->
                @if($presupuestoTechoInfo['techoTotal'] > 0 || $presupuestoTechoInfo['presupuestoDisponible'] >= 0)
                    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 border border-indigo-200 dark:border-indigo-700 p-4 rounded-lg">
                        <h4 class="text-sm font-semibold text-indigo-900 dark:text-indigo-100 mb-3">Información de Presupuesto - Departamento: {{ $presupuestoTechoInfo['departamentoNombre'] }} | Fuente: {{ $presupuestoTechoInfo['fuenteIdentificador'] }} - {{ $presupuestoTechoInfo['fuenteNombre'] }}</h4>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-center">
                                <p class="text-xs text-indigo-600 dark:text-indigo-400 font-medium">Techo Total</p>
                                <p class="text-lg font-bold text-indigo-900 dark:text-indigo-100">L {{ number_format($presupuestoTechoInfo['techoTotal'], 2) }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-orange-600 dark:text-orange-400 font-medium">Asignado</p>
                                <p class="text-lg font-bold text-orange-900 dark:text-orange-100">L {{ number_format($presupuestoTechoInfo['presupuestoAsignado'], 2) }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-green-600 dark:text-green-400 font-medium">Disponible</p>
                                <p class="text-lg font-bold text-green-900 dark:text-green-100">L {{ number_format($presupuestoTechoInfo['presupuestoDisponible'], 2) }}</p>
                            </div>
                        </div>
                        @if($presupuestoTechoInfo['presupuestoDisponible'] <= 0)
                            <div class="mt-3 p-2 bg-red-100 dark:bg-red-900/20 border border-red-300 dark:border-red-700 rounded text-sm text-red-700 dark:text-red-300">
                                Presupuesto insuficiente. No hay fondos disponibles en el techo del departamento.
                            </div>
                        @endif
                        <!-- Mensajes de éxito/error -->
                    </div>
                @else
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 p-4 rounded-lg">
                        <p class="text-sm text-yellow-800 dark:text-yellow-300">
                            No hay techo presupuestario asignado a este departamento. Solicita al administrador que asigne un presupuesto al departamento antes de crear presupuestos para tareas.
                        </p>
                    </div>
                @endif
                @if (session()->has('message'))
                    <div
                        class="mb-4 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-800 dark:text-green-300 px-4 py-3 rounded">
                        {{ session('message') }}
                    </div>
                @endif
                
                @if (session()->has('error'))
                    <div
                        class="mb-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-800 dark:text-red-300 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif
                
                <!-- Formulario para agregar presupuesto -->
                <div class="bg-zinc-50 dark:bg-zinc-700 p-4 rounded-lg space-y-4">
                    <h4 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">Agregar Recurso</h4>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-label for="recursoPresupuesto" value="Recurso" />
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
                            <select id="fuentePresupuesto" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200 text-sm" wire:model.live="nuevoPresupuesto.idfuente">
                                <option value="">Seleccione una fuente</option>
                                @foreach($fuentesFinanciamiento as $fuente)
                                    <option value="{{ $fuente['id'] }}">{{ $fuente['identificador'] }} - {{ $fuente['nombre'] }}</option>
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
                            <x-label for="costoUnitario" value="Costo Unitario (L)" />
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
                                    <option value="{{ $mes['id'] }}">{{ $mes['mes'] }}</option>
                                @endforeach
                            </select>
                            @error('nuevoPresupuesto.idMes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-600">
                        <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Total:</span>
                        <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                            L {{ number_format($nuevoPresupuesto['total'], 2) }}
                        </span>
                    </div>

                    <div class="flex justify-end">
                        <x-button wire:click="savePresupuesto" class="{{ !$actividadEnFormulacion ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}" :disabled="!$actividadEnFormulacion">
                            <svg class="w-4 h-4 mr-2"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                                L {{ number_format($presupuesto['costounitario'], 2) }}
                                            </td>
                                            <td class="px-3 py-2 text-xs text-center text-zinc-900 dark:text-zinc-100">
                                                {{ $presupuesto['mes']['mes'] ?? 'N/A' }}
                                            </td>
                                            <td class="px-3 py-2 text-xs text-right font-semibold text-indigo-600 dark:text-indigo-400">
                                                L {{ number_format($presupuesto['total'], 2) }}
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                <button wire:click="openDeletePresupuestoModal({{ $presupuesto['id'] }})"
                                                        class="text-red-600 hover:text-red-800 dark:text-red-400 {{ !$actividadEnFormulacion ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}" :disabled="!$actividadEnFormulacion">
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
                                            L {{ number_format(collect($presupuestosTarea)->sum('total'), 2) }}
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

    <!-- Modal Eliminar Presupuesto -->
    @include('livewire.actividad.delete-presupuesto-modal')
    @include('livewire.actividad.delete-empleado-tarea-modal')

</div>
