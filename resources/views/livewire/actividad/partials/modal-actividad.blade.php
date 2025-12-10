{{-- Modal Crear/Editar con Pasos --}}
<x-dialog-modal wire:model="modalOpen" maxWidth="4xl">
    <x-slot name="title">
        {{ $actividadId ? 'Editar Actividad' : 'Nueva Actividad' }}
    </x-slot>

    <x-slot name="content">
        <form wire:submit.prevent="guardar" id="form-actividad">
                <div class="space-y-6">

                    {{-- Indicador de pasos --}}
                    <div class="mb-6">
                        <div class="flex items-center justify-between">
                            @for($i = 1; $i <= $totalSteps; $i++)
                                <div class="flex items-center {{ $i < $totalSteps ? 'flex-1' : '' }}">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $currentStep >= $i ? 'bg-indigo-600 dark:bg-indigo-500 text-white' : 'bg-zinc-200 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-400' }} font-semibold">
                                        {{ $i }}
                                    </div>
                                    <div class="ml-2">
                                        <p class="text-sm font-medium {{ $currentStep >= $i ? 'text-indigo-600 dark:text-indigo-400' : 'text-zinc-500 dark:text-zinc-400' }}">
                                            @if($i == 1) Datos de Actividad
                                            @elseif($i == 2) Vinculación PEI
                                            @endif
                                        </p>
                                    </div>
                                    @if($i < $totalSteps)
                                        <div class="flex-1 h-0.5 mx-4 {{ $currentStep > $i ? 'bg-indigo-600 dark:bg-indigo-500' : 'bg-zinc-200 dark:bg-zinc-700' }}"></div>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>

                    {{-- Paso 1: Datos de la Actividad --}}
                    @if($currentStep == 1)
                        <div class="space-y-4">
                            {{-- Mensaje de éxito IA --}}
                            @if(session()->has('ia_success'))
                                <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg flex items-start">
                                    <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm">{{ session('ia_success') }}</span>
                                </div>
                            @endif

                            {{-- Mensaje de error IA --}}
                            @if(session()->has('error'))
                                <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg flex items-start">
                                    <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm">{{ session('error') }}</span>
                                </div>
                            @endif

                            {{-- Nombre --}}
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Nombre de la Actividad *</label>
                                <div class="flex gap-2">
                                    <textarea wire:model="nombre" placeholder="Ejemplo: Capacitación docente en metodologías activas de enseñanza..." rows="2" class="flex-1 block rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                    @if(!$actividadId)
                                        <x-spinner-button 
                                            wire:click="generarConIA" 
                                            loadingTarget="generarConIA" 
                                            :loadingText="__('Generando...')"
                                            class="bg-purple-600 hover:bg-purple-700 focus:ring-purple-500 whitespace-nowrap"
                                            :disabled="$generandoConIA"
                                        >
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                            Generar con IA
                                        </x-spinner-button>
                                    @endif
                                </div>
                                @error('nombre') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Descripción --}}
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Descripción *</label>
                                <textarea wire:model="descripcion" placeholder="Describe la actividad a realizar" rows="4" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                @error('descripcion') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Tipo de Actividad --}}
                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Tipo de Actividad *</label>
                                    <select wire:model="idTipo" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Seleccione un tipo</option>
                                        @foreach($tiposActividad as $tipo)
                                            <option value="{{ $tipo->id }}">{{ $tipo->tipo }}</option>
                                        @endforeach
                                    </select>
                                    @error('idTipo') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Categoría --}}
                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Categoría</label>
                                    <select wire:model="idCategoria" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Seleccione una categoría</option>
                                        @foreach($categorias as $categoria)
                                            <option value="{{ $categoria->id }}">{{ $categoria->categoria }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Resultado de la Actividad --}}
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Resultado Esperado</label>
                                <textarea wire:model="resultadoActividad" placeholder="Indica resultados de esta actividad" rows="2" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            </div>

                            {{-- Población Objetivo --}}
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Población Objetivo</label>
                                <textarea wire:model="poblacion_objetivo" placeholder="Indica la población objetivo" rows="2" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            </div>

                            {{-- Medio de Verificación --}}
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Medio de Verificación</label>
                                <textarea wire:model="medio_verificacion" placeholder="Indica los medios de verificación" rows="2" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            </div>
                        </div>
                    @endif

                    {{-- Paso 2: Vinculación con PEI --}}
                    @if($currentStep == 2)
                        <div class="space-y-4">
                            <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg p-4 mb-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <h4 class="text-sm font-medium text-indigo-900 dark:text-indigo-300">Vinculación con PEI</h4>
                                        <p class="text-xs text-indigo-700 dark:text-indigo-400 mt-1">
                                            Vincule esta actividad con una dimensión y resultado del Plan Estratégico Institucional (PEI)
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Dimensión --}}
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Dimensión *</label>
                                <select wire:model.live="idDimension" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Seleccione una dimensión</option>
                                    @foreach($dimensiones as $dimension)
                                        <option value="{{ $dimension->id }}">{{ $dimension->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('idDimension') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Resultado --}}
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Resultado *</label>
                                <select wire:model="idResultado" 
                                    class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    {{ !$idDimension ? 'disabled' : '' }}>
                                    <option value="">{{ $idDimension ? 'Seleccione un resultado' : 'Primero seleccione una dimensión' }}</option>
                                    @foreach($resultadosPorDimension as $resultado)
                                        <option value="{{ $resultado->id }}">{{ $resultado->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('idResultado') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                
                                @if($idDimension && count($resultadosPorDimension) == 0)
                                    <p class="mt-1 text-xs text-amber-600 dark:text-amber-400">
                                        No hay resultados disponibles para esta dimensión
                                    </p>
                                @endif
                            </div>

                            {{-- Preview de vinculación --}}
                            @if($idResultado)
                                @php
                                    $resultadoSeleccionado = collect($resultadosPorDimension)->firstWhere('id', $idResultado);
                                @endphp
                                @if($resultadoSeleccionado)
                                    <div class="mt-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                                        <h5 class="text-sm font-medium text-green-900 dark:text-green-300 mb-2">Vinculación Seleccionada</h5>
                                        <div class="text-xs space-y-1">
                                            <p><span class="font-medium text-green-700 dark:text-green-400">Dimensión:</span> 
                                                <span class="text-green-900 dark:text-green-200">{{ $resultadoSeleccionado->dimension->nombre ?? 'N/A' }}</span>
                                            </p>
                                            <p><span class="font-medium text-green-700 dark:text-green-400">Resultado:</span> 
                                                <span class="text-green-900 dark:text-green-200">{{ $resultadoSeleccionado->nombre }}</span>
                                            </p>
                                            @if($resultadoSeleccionado->descripcion)
                                                <p class="text-green-700 dark:text-green-400 mt-2">{{ $resultadoSeleccionado->descripcion }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endif
                            {{-- Mensajes --}}
                            @if (session()->has('message'))
                                <div class="mb-4 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded relative" role="alert">
                                    <span class="block sm:inline">{{ session('message') }}</span>
                                </div>
                            @endif

                            @if (session()->has('error'))
                                <div class="mb-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded relative" role="alert">
                                    <span class="block sm:inline">{{ session('error') }}</span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </form>
    </x-slot>

    <x-slot name="footer">
        <div class="flex justify-between w-full">
            <div>
                @if($currentStep > 1)
                    <x-secondary-button wire:click="previousStep">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Anterior
                    </x-secondary-button>
                @endif
            </div>

            <div class="flex gap-2">
                <x-secondary-button wire:click="$set('modalOpen', false)">
                    Cancelar
                </x-secondary-button>

                @if($currentStep < $totalSteps)
                    <x-button wire:click="nextStep">
                        Siguiente
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </x-button>
                @else
                    <x-button type="submit" form="form-actividad" class="bg-green-600 hover:bg-green-700 focus:ring-green-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ $actividadId ? 'Actualizar' : 'Crear' }} Actividad
                    </x-button>
                @endif
            </div>
        </div>
    </x-slot>
    


<!-- <x-dialog-modal wire:model="modalOpen" maxWidth="4xl">
    <x-slot name="title">
        <div class="flex items-center justify-between">
            <span>{{ $actividadId ? 'Editar Actividad' : 'Nueva Actividad' }}</span>
            
            {{-- Botón toggle IA (solo al crear) --}}
            @if(!$actividadId)
                <button 
                    type="button"
                    wire:click="toggleIA"
                    class="cursor-pointer inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ $usarIA ? 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' : 'bg-purple-600 text-white hover:bg-purple-700' }}"
                >
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    {{ $usarIA ? 'Modo Manual' : 'Generar con IA' }}
                </button>
            @endif
        </div>
    </x-slot>

    <x-slot name="content">
        {{-- Panel de Generación con IA --}}
        @if($usarIA && !$actividadId)
            <div class="bg-gradient-to-br from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 rounded-lg p-6 border-2 border-purple-200 dark:border-purple-800">
                <div class="flex items-start space-x-4 mb-6">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-purple-900 dark:text-purple-100 mb-2">
                            Asistente de IA para Actividades
                        </h3>
                        <p class="text-sm text-purple-700 dark:text-purple-300">
                            Ingresa el nombre de la actividad y la IA generará automáticamente la descripción, resultado esperado, población objetivo y medio de verificación.
                        </p>
                    </div>
                </div>

                {{-- Mensaje de éxito IA --}}
                @if(session()->has('ia_success'))
                    <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg mb-4 flex items-start">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm">{{ session('ia_success') }}</span>
                    </div>
                @endif

                {{-- Mensaje de error IA --}}
                @if(session()->has('error'))
                    <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-4 flex items-start">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm">{{ session('error') }}</span>
                    </div>
                @endif

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-purple-900 dark:text-purple-100 mb-2">
                            Nombre de la Actividad *
                        </label>
                        <x-textarea 
                            wire:model="nombreParaIA" 
                            rows="2" 
                            class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Ejemplo: Capacitación docente en metodologías activas de enseñanza..."
                            :disabled="$generandoConIA"
                        />
                        @error('nombreParaIA') 
                            <span class="text-red-600 dark:text-red-400 text-xs mt-1">{{ $message }}</span> 
                        @enderror
                        <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">
                            Tip: Sé específico y claro. Mientras más detalle, mejor será el resultado.
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <x-spinner-button 
                            wire:click="generarConIA" 
                            loadingTarget="generarConIA" 
                            :loadingText="__('Generando...')"
                            class="flex-1 inline-flex items-center justify-center px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="$generandoConIA"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Generar Actividad
                        </x-spinner-button>
                        
                        <x-spinner-secondary-button 
                            loadingTarget="cancelarIA"
                            loadingText="Cerrando..."
                            type="button"
                            wire:click="cancelarIA"
                        >
                            Cancelar
                        </x-spinner-secondary-button>
                    </div>
                </div>
            </div>
        @else
            <form wire:submit.prevent="guardar" id="form-actividad">
                <div class="space-y-6">

                    {{-- Indicador de pasos --}}
                    <div class="mb-6">
                        <div class="flex items-center justify-between">
                            @for($i = 1; $i <= $totalSteps; $i++)
                                <div class="flex items-center {{ $i < $totalSteps ? 'flex-1' : '' }}">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $currentStep >= $i ? 'bg-indigo-600 dark:bg-indigo-500 text-white' : 'bg-zinc-200 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-400' }} font-semibold">
                                        {{ $i }}
                                    </div>
                                    <div class="ml-2">
                                        <p class="text-sm font-medium {{ $currentStep >= $i ? 'text-indigo-600 dark:text-indigo-400' : 'text-zinc-500 dark:text-zinc-400' }}">
                                            @if($i == 1) Datos de Actividad
                                            @elseif($i == 2) Vinculación PEI
                                            @endif
                                        </p>
                                    </div>
                                    @if($i < $totalSteps)
                                        <div class="flex-1 h-0.5 mx-4 {{ $currentStep > $i ? 'bg-indigo-600 dark:bg-indigo-500' : 'bg-zinc-200 dark:bg-zinc-700' }}"></div>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>

                    {{-- Paso 1: Datos de la Actividad --}}
                    @if($currentStep == 1)
                        <div class="space-y-4">
                            {{-- Nombre --}}
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Nombre de la Actividad *</label>
                                <textarea wire:model="nombre" placeholder="Ejemplo: Capacitación docente en metodologías activas de enseñanza..." rows="2" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                @error('nombre') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Descripción --}}
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Descripción *</label>
                                <textarea wire:model="descripcion" placeholder="Describe la actividad a realizar" rows="4" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                @error('descripcion') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Tipo de Actividad --}}
                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Tipo de Actividad *</label>
                                    <select wire:model="idTipo" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Seleccione un tipo</option>
                                        @foreach($tiposActividad as $tipo)
                                            <option value="{{ $tipo->id }}">{{ $tipo->tipo }}</option>
                                        @endforeach
                                    </select>
                                    @error('idTipo') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Categoría --}}
                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Categoría</label>
                                    <select wire:model="idCategoria" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Seleccione una categoría</option>
                                        @foreach($categorias as $categoria)
                                            <option value="{{ $categoria->id }}">{{ $categoria->categoria }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Resultado de la Actividad --}}
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Resultado Esperado</label>
                                <textarea wire:model="resultadoActividad" placeholder="Indica resultados de esta actividad" rows="2" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            </div>

                            {{-- Población Objetivo --}}
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Población Objetivo</label>
                                <textarea wire:model="poblacion_objetivo" placeholder="Indica la población objetivo" rows="2" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            </div>

                            {{-- Medio de Verificación --}}
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Medio de Verificación</label>
                                <textarea wire:model="medio_verificacion" placeholder="Indica los medios de verificación" rows="2" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            </div>
                        </div>
                    @endif

                    {{-- Paso 2: Vinculación con PEI --}}
                    @if($currentStep == 2)
                        <div class="space-y-4">
                            <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg p-4 mb-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <h4 class="text-sm font-medium text-indigo-900 dark:text-indigo-300">Vinculación con PEI</h4>
                                        <p class="text-xs text-indigo-700 dark:text-indigo-400 mt-1">
                                            Vincule esta actividad con una dimensión y resultado del Plan Estratégico Institucional (PEI)
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Dimensión --}}
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Dimensión *</label>
                                <select wire:model.live="idDimension" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Seleccione una dimensión</option>
                                    @foreach($dimensiones as $dimension)
                                        <option value="{{ $dimension->id }}">{{ $dimension->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('idDimension') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Resultado --}}
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Resultado *</label>
                                <select wire:model="idResultado" 
                                    class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    {{ !$idDimension ? 'disabled' : '' }}>
                                    <option value="">{{ $idDimension ? 'Seleccione un resultado' : 'Primero seleccione una dimensión' }}</option>
                                    @foreach($resultadosPorDimension as $resultado)
                                        <option value="{{ $resultado->id }}">{{ $resultado->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('idResultado') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                
                                @if($idDimension && count($resultadosPorDimension) == 0)
                                    <p class="mt-1 text-xs text-amber-600 dark:text-amber-400">
                                        No hay resultados disponibles para esta dimensión
                                    </p>
                                @endif
                            </div>

                            {{-- Preview de vinculación --}}
                            @if($idResultado)
                                @php
                                    $resultadoSeleccionado = collect($resultadosPorDimension)->firstWhere('id', $idResultado);
                                @endphp
                                @if($resultadoSeleccionado)
                                    <div class="mt-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                                        <h5 class="text-sm font-medium text-green-900 dark:text-green-300 mb-2">Vinculación Seleccionada</h5>
                                        <div class="text-xs space-y-1">
                                            <p><span class="font-medium text-green-700 dark:text-green-400">Dimensión:</span> 
                                                <span class="text-green-900 dark:text-green-200">{{ $resultadoSeleccionado->dimension->nombre ?? 'N/A' }}</span>
                                            </p>
                                            <p><span class="font-medium text-green-700 dark:text-green-400">Resultado:</span> 
                                                <span class="text-green-900 dark:text-green-200">{{ $resultadoSeleccionado->nombre }}</span>
                                            </p>
                                            @if($resultadoSeleccionado->descripcion)
                                                <p class="text-green-700 dark:text-green-400 mt-2">{{ $resultadoSeleccionado->descripcion }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endif
                </div>
            </form>
        @endif
    </x-slot>

    <x-slot name="footer">
        @if(!$usarIA || $actividadId)
            <div class="flex justify-between w-full">
            <div>
                @if($currentStep > 1)
                    <x-secondary-button wire:click="previousStep">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Anterior
                    </x-secondary-button>
                @endif
            </div>

            <div class="flex gap-2">
                <x-secondary-button wire:click="$set('modalOpen', false)">
                    Cancelar
                </x-secondary-button>

                @if($currentStep < $totalSteps)
                    <x-button wire:click="nextStep">
                        Siguiente
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </x-button>
                @else
                    <x-button type="submit" form="form-actividad" class="bg-green-600 hover:bg-green-700 focus:ring-green-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ $actividadId ? 'Actualizar' : 'Crear' }} Actividad
                    </x-button>
                @endif
            </div>
        </div>
        @endif
    </x-slot>
</x-dialog-modal> -->


</x-dialog-modal>

