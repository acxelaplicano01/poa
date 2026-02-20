<div>
    @can('planificacion.requisicion.crear')
        {{-- Mensajes de éxito/error --}}
        @if ($successMessage)
            @include('rk.default.notifications.notification-alert', [
                'type' => 'success',
                'dismissible' => true,
                'icon' => true,
                'duration' => 5,
                'slot' => $successMessage,
            ])
        @endif

        @if (session()->has('message'))
            @include('rk.default.notifications.notification-alert', [
                'type' => 'success',
                'dismissible' => true,
                'icon' => true,
                'duration' => 5,
                'slot' => session('message'),
            ])
        @endif

        @if (session()->has('error'))
            @include('rk.default.notifications.notification-alert', [
                'type' => 'error',
                'dismissible' => true,
                'icon' => true,
                'duration' => 8,
                'slot' => session('error'),
            ])
        @endif

        <h2 class="text-lg font-semibold text-zinc-800 dark:text-zinc-200 mb-4">Sumario de Recursos Seleccionados</h2>

        <div class="flex justify-between items-center mb-4">
            <a href="{{ route('requisicion') }}" class="inline-flex items-center text-indigo-600 dark:text-indigo-400 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd" />
                </svg>
                Volver a Requisiciones
            </a>
        </div>

        {{-- Banner del monto total --}}
        <div class="mb-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-300 px-4 py-3 rounded-lg flex items-center justify-between"
            role="alert">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <div>
                    <p class="font-semibold text-sm">Monto total de los recursos seleccionados</p>
                    <p class="text-xs mt-0.5">Este es el monto total acumulado de todos los recursos seleccionados.</p>
                </div>
            </div>
            <div class="text-right">
                <div class="flex items-baseline">
                    <span class="text-3xl font-bold">L
                        {{ number_format(collect($recursosSeleccionados)->sum('total'), 2) }}</span>
                </div>
                <p class="text-xs mt-0.5">Monto total</p>
            </div>
        </div>

        {{-- Botón para abrir la modal de crear requisición --}}
        <div class="flex justify-end mb-6">
            @if ($puedeCrearRequisicion)
                <button wire:click="$set('showCrearRequisicionModal', true)"
                    class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium bg-indigo-600 text-white hover:bg-indigo-700">
                    Crear Requisición
                </button>
            @else
                <div class="relative group">
                    <button disabled
                        class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium 
                   bg-zinc-300 dark:bg-zinc-700 text-zinc-400 cursor-not-allowed opacity-60">
                        Crear Requisición
                    </button>
                    <div
                        class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block
                    bg-zinc-800 text-white text-xs rounded-lg px-3 py-2 shadow-lg z-50 w-64 text-center">
                        {{ $mensajePlazoRequisicion }}
                        <div
                            class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-zinc-800">
                        </div>
                    </div>
                </div>
            @endif

        </div>

        {{-- Tabla de recursos seleccionados --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700 mb-4">
                <thead class="bg-zinc-50 dark:bg-zinc-700">
                    <tr>
                        <th
                            class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase w-[20%]">
                            Recurso</th>
                        <th
                            class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase w-[20%]">
                            Actividad</th>
                        <th
                            class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase w-[20%]">
                            Proceso Compra</th>
                        <th
                            class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase w-[10%]">
                            Unidad</th>
                        <th
                            class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase w-[20%]">
                            Detalles</th>
                        <th
                            class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase w-[10%]">
                            Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recursosSeleccionados as $recurso)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50">
                            <td class="px-3 py-2 text-zinc-900 dark:text-zinc-100">{{ $recurso['nombre'] ?? '-' }}</td>
                            <td class="px-3 py-2 text-zinc-600 dark:text-zinc-400">{{ $recurso['actividad'] ?? '-' }}</td>
                            <td class="px-3 py-2 text-zinc-600 dark:text-zinc-400">{{ $recurso['proceso_compra'] ?? '-' }}
                            </td>
                            <td class="px-3 py-2 text-zinc-600 dark:text-zinc-400">{{ $recurso['unidad_medida'] ?? '-' }}
                            </td>
                            <td class="px-3 py-2 text-zinc-600 dark:text-zinc-400">
                                <div class="text-sm">
                                    <p><span class="font-semibold">Cantidad:</span>
                                        {{ $recurso['cantidad_seleccionada'] ?? '-' }}</p>
                                    <p><span class="font-semibold">Precio Unitario:</span> L
                                        {{ number_format($recurso['precio_unitario'] ?? 0, 2) }}</p>
                                    <p><span class="font-semibold">Total:</span> L
                                        {{ number_format($recurso['total'] ?? 0, 2) }}</p>
                                </div>
                            </td>
                            <td class="px-3 py-2">
                                <div class="flex items-center gap-2">
                                    {{-- Botón eliminar --}}
                                    <button wire:click="quitarRecursoDelSumario({{ $recurso['id'] }})"
                                        class="text-red-600 hover:text-red-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>

                                    {{-- Botón combustible solo si aplica --}}
                                    @if (!empty($recurso['es_combustible']) && $recurso['es_combustible'])
                                        <button wire:click="abrirOrdenCombustibleModal({{ $recurso['id'] }})"
                                            title="{{ !empty($recurso['orden_combustible_creada']) ? 'Editar orden de combustible' : 'Nueva orden de combustible' }}"
                                            class="{{ !empty($recurso['orden_combustible_creada']) ? 'text-green-600 hover:text-green-800 dark:text-green-400' : 'text-blue-600 hover:text-blue-800 dark:text-blue-400' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="h-12 w-12 text-zinc-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                        <p class="text-zinc-500 dark:text-zinc-400 text-lg font-medium">No hay recursos seleccionados en e sumario</p>
                                        <p class="text-zinc-400 dark:text-zinc-500 text-sm mt-2">Agregalos en la secciones de requisiciones</p>
                                    </div>
                                </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Modal para crear requisición --}}
        <x-dialog-modal wire:model="showCrearRequisicionModal" maxWidth="lg">
            <x-slot name="title">
                <h3 class="text-lg font-semibold text-zinc-800 dark:text-zinc-100">
                    Crear Requisición
                </h3>
            </x-slot>
            <x-slot name="content">
                {{-- Banner del monto total --}}
                <div class="mb-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-300 px-4 py-3 rounded-lg flex items-center justify-between"
                    role="alert">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <div>
                            <p class="font-semibold text-sm">Monto total de la requisición</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-3xl font-bold">L
                            {{ number_format(collect($recursosSeleccionados)->sum('total'), 2) }}</span>
                        <p class="text-xs mt-0.5">Monto total</p>
                    </div>
                </div>

                {{-- Campos del formulario --}}
                <div class="space-y-4">
                    <div>
                        <x-label value="Descripción" class="mb-1" />
                        <x-input wire:model="descripcion" type="text" class="w-full" />
                        @error('descripcion')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <x-label value="Fecha Requerida" class="mb-1" />
                        <x-input wire:model="fechaRequerido" type="date" class="w-full" />
                        @error('fechaRequerido')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <x-label value="Observación" class="mb-1" />
                        <x-input wire:model="observacion" type="text" class="w-full" />
                    </div>
                </div>
            </x-slot>
            <x-slot name="footer">
                <x-spinner-secondary-button wire:click="$set('showCrearRequisicionModal', false)" type="button">
                    Cancelar
                </x-spinner-secondary-button>
                <x-spinner-button wire:click="crearRequisicion" type="button">
                    Enviar
                </x-spinner-button>
            </x-slot>
        </x-dialog-modal>

        {{-- Modal orden de combustible --}}
        @include('livewire.seguimiento.Requisicion.orden-combustible')

        {{-- Modal de errores --}}
        @if ($showErrorModal)
            <x-dialog-modal wire:model="showErrorModal" maxWidth="sm">
                <x-slot name="title">Error</x-slot>
                <x-slot name="content">
                    <p class="text-red-600 dark:text-red-400">{{ $errorMessage }}</p>
                </x-slot>
                <x-slot name="footer">
                    <x-button wire:click="closeErrorModal">Cerrar</x-button>
                </x-slot>
            </x-dialog-modal>
        @endif
    @endcan
</div>
