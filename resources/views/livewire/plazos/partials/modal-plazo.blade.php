<!-- Modal para crear/editar plazo -->
<x-dialog-modal wire:model="modalOpen" maxWidth="2xl">
    <x-slot name="title">
        {{ $isEditing ? 'Editar Plazo' : 'Nuevo Plazo' }}
    </x-slot>

    <x-slot name="content">
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">
            Configure el período de tiempo para esta actividad del POA
        </p>

        <form class="space-y-4">
            <!-- POA -->
            <div>
                <label for="idPoa" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                    POA <span class="text-red-500">*</span>
                </label>
                <select wire:model="idPoa" id="idPoa"
                        class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Seleccione un POA</option>
                    @foreach($poas as $poa)
                        <option value="{{ $poa->id }}">
                            {{ $poa->anio }} - {{ $poa->name }}
                        </option>
                    @endforeach
                </select>
                @error('idPoa') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <!-- Tipo de Plazo -->
            <div>
                <label for="tipo_plazo" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                    Tipo de Plazo <span class="text-red-500">*</span>
                </label>
                <select wire:model="tipo_plazo" id="tipo_plazo"
                        class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Seleccione un tipo</option>
                    @foreach($tiposPlazos as $tipo)
                        <option value="{{ $tipo['value'] }}">{{ $tipo['label'] }}</option>
                    @endforeach
                </select>
                @error('tipo_plazo') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <!-- Fechas -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="fecha_inicio" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        Fecha de Inicio <span class="text-red-500">*</span>
                    </label>
                    <input type="date" wire:model="fecha_inicio" id="fecha_inicio"
                           class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('fecha_inicio') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="fecha_fin" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        Fecha de Fin <span class="text-red-500">*</span>
                    </label>
                    <input type="date" wire:model="fecha_fin" id="fecha_fin"
                           class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('fecha_fin') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
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
            <div class="flex items-center">
                <input type="checkbox" wire:model="activo" id="activo"
                       class="h-4 w-4 rounded border-zinc-300 text-indigo-600 focus:ring-indigo-500 dark:border-zinc-600 dark:bg-zinc-700">
                <label for="activo" class="ml-2 block text-sm text-zinc-700 dark:text-zinc-300">
                    Activar este plazo (solo puede haber uno activo por tipo y POA)
                </label>
            </div>
        </form>
    </x-slot>

    <x-slot name="footer">
        <x-secondary-button wire:click="$set('modalOpen', false)">
            Cancelar
        </x-secondary-button>

        <x-button class="ml-3" wire:click="guardar">
            {{ $isEditing ? 'Actualizar' : 'Crear' }}
        </x-button>
    </x-slot>
</x-dialog-modal>

