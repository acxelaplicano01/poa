<x-dialog-modal wire:model="modalOpen" maxWidth="2xl">
        <x-slot name="title">
            {{ $isEditing ? 'Editar Plazo Personalizado' : 'Nuevo Plazo Personalizado' }}
        </x-slot>

        <x-slot name="content">
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">
                Configure un plazo personalizado adicional para este POA
            </p>

            <form class="space-y-4">
                <!-- Tipo de Plazo -->
                <div>
                    <label for="tipo_plazo" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        Tipo de Plazo <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="tipo_plazo" id="tipo_plazo"
                            class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Seleccione un tipo</option>
                        @foreach($tiposPlazosEstandar as $valor => $etiqueta)
                            <option value="{{ $valor }}">{{ $etiqueta }}</option>
                        @endforeach
                    </select>
                    @error('tipo_plazo') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Nombre -->
                <div>
                    <label for="nombre_plazo" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        Nombre Personalizado del Plazo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="nombre_plazo" id="nombre_plazo"
                           class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="Ej: Extensión de Planificación, Plazo Adicional de Seguimiento, etc.">
                    <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                        El nombre personalizado permite crear plazos adicionales del mismo tipo. Ej: "Extensión de Planificación".
                    </p>
                    @error('nombre_plazo') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Fechas -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="fecha_inicio_form" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                            Fecha de Inicio <span class="text-red-500">*</span>
                        </label>
                        <input type="date" wire:model="fecha_inicio_form" id="fecha_inicio_form"
                               class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('fecha_inicio_form') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="fecha_fin_form" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                            Fecha de Fin <span class="text-red-500">*</span>
                        </label>
                        <input type="date" wire:model="fecha_fin_form" id="fecha_fin_form"
                               class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('fecha_fin_form') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Descripción -->
                <div>
                    <label for="descripcion" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        Descripción (Opcional)
                    </label>
                    <textarea wire:model="descripcion" id="descripcion" rows="3"
                              class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                              placeholder="Detalles adicionales sobre este plazo..."></textarea>
                    @error('descripcion') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Estado Activo -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox" wire:model="activo_form" id="activo_form"
                               class="h-4 w-4 rounded border-zinc-300 text-indigo-600 focus:ring-indigo-500 dark:border-zinc-600 dark:bg-zinc-700">
                    </div>
                    <div class="ml-3">
                        <label for="activo_form" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            Activar este plazo
                        </label>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">
                            Los plazos personalizados pueden coexistir activos junto con los plazos estándar.
                        </p>
                    </div>
                </div>
            </form>
        </x-slot>

        <x-slot name="footer">
            <x-spinner-secondary-button wire:click="closeModal" type="button" loadingTarget="closeModal" loadingText="Cerrando...">
                {{ __('Cancelar') }}
            </x-spinner-secondary-button>

            <x-spinner-button loadingTarget="guardar" loadingText="{{ $isEditing ? 'Actualizando...' : 'Creando...' }}" class="ml-3" wire:click="guardar">
                {{ $isEditing ? 'Actualizar' : 'Crear plazo' }}
            </x-spinner-button>
        </x-slot>
    </x-dialog-modal>