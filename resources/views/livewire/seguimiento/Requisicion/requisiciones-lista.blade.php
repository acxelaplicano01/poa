<div>
    @include('livewire.seguimiento.Requisicion.edit-requisicion')
    @include('livewire.seguimiento.Requisicion.detalle-recursos-modal')
    <div class="mx-auto rounded-lg mt-8 sm:mt-6 lg:mt-4 mb-6">
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow sm:rounded-lg p-4 sm:p-6">
            @if (session()->has('message'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md" role="alert">
                    <p class="font-medium">{{ session('message') }}</p>
                </div>
            @endif
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                                    <div class="flex flex-row items-end gap-4 w-full sm:w-auto">
                                        <div class="relative w-56">
                                            <x-input wire:model.live="search" type="text" placeholder="Buscar requisiciones..." class="w-full pl-10 pr-4 py-2"/>
                                            <div class="absolute left-3 top-2.5">
                                                <svg class="h-5 w-5 text-zinc-500 dark:text-zinc-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex flex-col">
                                            <select id="estadoFiltro" wire:model.live="estadoFiltro" class="form-select rounded border-zinc-300 dark:bg-zinc-800 dark:text-zinc-200">
                                                <option value="Todos">Todos</option>
                                                <option value="Presentado">Presentado</option>
                                                <option value="Recibido">Recibido</option>
                                                <option value="En Proceso">En Proceso</option>
                                                <option value="Aprobado">Aprobado</option>
                                                <option value="Rechazado">Rechazado</option>
                                                <option value="Finalizado">Finalizado</option>
                                            </select>
                                        </div>
                                    </div>
            </div>
            <x-table
                sort-field="{{ $sortField ?? '' }}"
                sort-direction="{{ $sortDirection ?? '' }}"
                :columns="[
                    ['key' => 'correlativo', 'label' => 'Correlativo', 'sortable' => true],
                    ['key' => 'departamento', 'label' => 'Departamento', 'sortable' => true],
                    ['key' => 'descripcion', 'label' => 'Descripción', 'sortable' => true],
                    ['key' => 'observacion', 'label' => 'Observación', 'sortable' => true],
                    ['key' => 'estado', 'label' => 'Estado', 'sortable' => true],
                    ['key' => 'actions', 'label' => 'Acciones'],
                ]"
                empty-message="{{ __('No se encontraron requisiciones')}}"
                class="mt-6"
            >
                <x-slot name="desktop">
                    @forelse ($requisiciones as $requisicion)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-300">
                                {{ $requisicion->correlativo }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-300">
                                {{ $requisicion->departamento ? $requisicion->departamento->name : '-' }}
                            </td>
                            </td>
                            <td class="px-6 py-4 text-zinc-900 dark:text-zinc-300 max-w-md truncate">
                                {{ $requisicion->descripcion }}
                            </td>
                            <td class="px-6 py-4 text-zinc-900 dark:text-zinc-300 max-w-md truncate">
                                {{ $requisicion->observacion ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-300">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $requisicion->estado->estado == 'Presentado' ? 'bg-yellow-200 text-yellow-800' : 'bg-blue-200 text-blue-800' }}">
                                    {{ $requisicion->estado->estado ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    @if(($requisicion->estado->estado ?? '') === 'Presentado')
                                        <button wire:click="edit({{ $requisicion->id }})"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 cursor-pointer"
                                            title="Editar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDelete({{ $requisicion->id }})"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 cursor-pointer"
                                            title="Eliminar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    @endif
                                    <button wire:click="verDetalleRecursos({{ $requisicion->id }})"
                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 cursor-pointer"
                                        title="Ver Detalle de Recursos">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-zinc-500 dark:text-zinc-400">
                                {{ __('No se encontraron requisiciones')}}
                            </td>
                        </tr>
                    @endforelse
                </x-slot>
                <x-slot name="mobile">
                    @forelse ($requisiciones as $requisicion)
                        <div class="bg-white dark:bg-zinc-800 p-4 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700 mb-4">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <span class="bg-zinc-100 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-300 px-2 py-1 rounded-full text-xs">
                                        ID: {{ $requisicion->id }}
                                    </span>
                                </div>
                                <div class="flex space-x-2">
                                    <button wire:click="edit({{ $requisicion->id }})"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $requisicion->id }})"
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <button wire:click="verRecursosRequisicion({{ $requisicion->id }})"
                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-3A2.25 2.25 0 0 0 8.25 5.25V9m10.5 0v10.125c0 1.24-1.01 2.25-2.25 2.25H7.5a2.25 2.25 0 0 1-2.25-2.25V9m13.5 0H3.75" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <h3 class="font-semibold text-zinc-900 dark:text-zinc-200 text-lg mb-2">{{ $requisicion->correlativo }}</h3>
                            <p class="text-zinc-600 dark:text-zinc-400 text-sm line-clamp-3">
                                <strong>Descripción:</strong> {{ $requisicion->descripcion ?: 'Sin descripción' }}<br>
                                <strong>Observación:</strong> {{ $requisicion->observacion ?: '-' }}<br>
                                <strong>Departamento:</strong>
                                {{ $requisicion->departamento ? $requisicion->departamento->name : '-' }}
                            </p>
                        </div>
                    @empty
                        <div class="bg-white dark:bg-zinc-800 p-4 rounded-lg shadow text-center text-zinc-500 dark:text-zinc-400">
                            {{__('No se encontraron requisiciones')}}
                        </div>
                    @endforelse
                </x-slot>
                <x-slot name="footer">
                    {{ $requisiciones->links() }}
                </x-slot>
            </x-table>
        </div>
    </div>
    @include('livewire.seguimiento.Requisicion.delete-confirmation')
    <x-error-modal 
        :show="$showErrorModal" 
        :message="$errorMessage"
        wire:click="hideError"
    />
</div>