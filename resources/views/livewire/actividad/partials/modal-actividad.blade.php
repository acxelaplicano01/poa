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
                            {{-- Nombre --}}
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Nombre de la Actividad *</label>
                                <x-input wire:model="nombre" type="text" class="w-full" />
                                @error('nombre') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Descripción --}}
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Descripción *</label>
                                <textarea wire:model="descripcion" rows="4" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
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
                                <textarea wire:model="resultadoActividad" rows="2" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            </div>

                            {{-- Población Objetivo --}}
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Población Objetivo</label>
                                <x-input wire:model="poblacion_objetivo" type="text" class="w-full" />
                            </div>

                            {{-- Medio de Verificación --}}
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Medio de Verificación</label>
                                <x-input wire:model="medio_verificacion" type="text" class="w-full" />
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
</x-dialog-modal>
