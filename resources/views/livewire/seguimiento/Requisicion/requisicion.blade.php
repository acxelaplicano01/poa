<div>
    <div class="bg-white dark:bg-zinc-900 rounded-lg shadow p-4 mb-6">
        <form wire:submit.prevent="buscar" class="flex flex-col md:flex-row md:items-center gap-4">
            <div class="flex-1">
                <x-input
                    wire:model.defer="busqueda"
                    type="text"
                    placeholder="Buscar por correlativo o departamento"
                    class="w-full"
                />
            </div>
            <div>
                <x-select
                    wire:model.defer="estado"
                    :options="[
                        ['value' => 0, 'text' => 'Todos'],
                        ['value' => 1, 'text' => 'Presentado'],
                        ['value' => 2, 'text' => 'Recibido'],
                        ['value' => 3, 'text' => 'En Proceso'],
                        ['value' => 4, 'text' => 'Aprobado'],
                        ['value' => 5, 'text' => 'Rechazado'],
                        ['value' => 6, 'text' => 'Finalizado'],
                    ]"
                    class="w-full"
                />
            </div>
            <div>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-800 dark:border-indigo-700 dark:text-white dark:hover:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-700 dark:focus:bg-indigo-900 active:bg-zinc-900 dark:active:bg-indigo-800 focus:outline-none focus:ring-2 dark:focus:ring-indigo-500 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-indigo-800 transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed w-full md:w-auto">
                    Buscar
                </button>
            </div>
        </form>
    </div>

    <div class="mx-auto rounded-lg mt-8 sm:mt-6 lg:mt-4 mb-6">
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow sm:rounded-lg p-4 sm:p-6">
            @if (session()->has('message'))
                @include('rk.default.notifications.notification-alert', [
                    'type' => 'success',
                    'dismissible' => true,
                    'icon' => true,
                    'duration' => 5,
                    'slot' => session('message')
                ])
            @endif

            @if (session()->has('error'))
                @include('rk.default.notifications.notification-alert', [
                    'type' => 'error',
                    'dismissible' => true,
                    'icon' => true,
                    'duration' => 8,
                    'slot' => session('error')
                ])
            @endif

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                <h2 class="text-xl font-semibold text-zinc-800 dark:text-zinc-200">{{ __('Detalle de Requisición') }}</h2>
                <div class="flex flex-col sm:flex-row w-full sm:w-auto space-y-3 sm:space-y-0 sm:space-x-2">
                    <x-spinner-button wire:click="addDetalle()" loadingTarget="addDetalle()" :loadingText="__('Abriendo...')">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('Nuevo Detalle') }}
                    </x-spinner-button>
                </div>
            </div>

            <x-table
                :columns="[
                    ['key' => 'recurso', 'label' => 'Recurso'],
                    ['key' => 'detalle_tecnico', 'label' => 'Detalle Técnico'],
                    ['key' => 'act_tarea', 'label' => 'Act./Tarea'],
                    ['key' => 'cantidad', 'label' => 'Cantidad'],
                    ['key' => 'precio_unitario', 'label' => 'Precio unitario'],
                    ['key' => 'total', 'label' => 'Total'],
                    ['key' => 'actions', 'label' => 'Acciones'],
                    ['key' => 'total', 'label' => 'Total'],
                    ['key' => 'actions', 'label' => 'Acciones'],
                ]"
                empty-message="{{ __('No hay detalles de requisición.') }}"
                class="mt-6"
            >
                <x-slot name="desktop">
                    @forelse ($detalleRequisiciones as $detalle)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-300">
                                {{ $detalle->recurso->nombre ?? '' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-300">
                                {{ $detalle->presupuesto->detalle_tecnico ?? '' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-300">
                                {{ $detalle->cantidad }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-300">
                                L {{ number_format($detalle->presupuesto->costounitario ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-300">
                                L {{ number_format(($detalle->cantidad ?? 0) * ($detalle->presupuesto->costounitario ?? 0), 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button wire:click="editDetalle({{ $detalle->id }})"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 cursor-pointer"
                                        title="Editar">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDeleteDetalle({{ $detalle->id }})"
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 cursor-pointer"
                                        title="Eliminar">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-zinc-500 dark:text-zinc-400">
                                {{ __('No hay detalles de requisición.') }}
                            </td>
                        </tr>
                    @endforelse
                </x-slot>

                <x-slot name="mobile">
                    @forelse ($detalleRequisiciones as $detalle)
                        <div class="bg-white dark:bg-zinc-800 p-4 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700 mb-4">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <span class="bg-zinc-100 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-300 px-2 py-1 rounded-full text-xs">
                                        ID: {{ $detalle->id }}
                                    </span>
                                </div>
                                <div class="flex space-x-2">
                                    <button wire:click="editDetalle({{ $detalle->id }})"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDeleteDetalle({{ $detalle->id }})"
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <h3 class="font-semibold text-zinc-900 dark:text-zinc-200 text-lg mb-2">{{ $detalle->recurso->nombre ?? '' }}</h3>
                            <div class="text-zinc-600 dark:text-zinc-400 text-sm mb-1">
                                <span class="font-semibold">Detalle Técnico:</span> {{ $detalle->presupuesto->detalle_tecnico ?? '' }}
                            </div>
                            <div class="text-zinc-600 dark:text-zinc-400 text-sm mb-1">
                                <span class="font-semibold">Cantidad:</span> {{ $detalle->cantidad }}
                            </div>
                            <div class="text-zinc-600 dark:text-zinc-400 text-sm mb-1">
                                <span class="font-semibold">Precio unitario:</span> L {{ number_format($detalle->presupuesto->costounitario ?? 0, 2) }}
                            </div>
                            <div class="text-zinc-600 dark:text-zinc-400 text-sm">
                                <span class="font-semibold">Total:</span> L {{ number_format(($detalle->cantidad ?? 0) * ($detalle->presupuesto->costounitario ?? 0), 2) }}
                            </div>
                        </div>
                    @empty
                        <div class="bg-white dark:bg-zinc-800 p-4 rounded-lg shadow text-center text-zinc-500 dark:text-zinc-400">
                            {{__('No hay detalles de requisición.') }}
                        </div>
                    @endforelse
                </x-slot>

                <x-slot name="footer">
                    <!-- Si necesitas paginación para detalles, agrégala aquí -->
                </x-slot>
            </x-table>
        </div>
    </div>

    <!-- Modal para crear/editar requisición -->
    @include('livewire.seguimiento.Requisicion.create')

    <!-- Modal de confirmación para eliminar -->
    @include('livewire.seguimiento.Requisicion.delete-confirmation')

    <!-- Modal de errores -->
    <x-error-modal 
        :show="$showErrorModal" 
        :message="$errorMessage"
        wire:click="closeErrorModal"
    />
</div>
