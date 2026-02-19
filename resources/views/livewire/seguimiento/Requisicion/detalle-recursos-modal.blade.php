<!-- Modal de detalle de recursos de la requisición -->
<x-dialog-modal wire:model="showDetalleRecursosModal" maxWidth="4xl">
    <x-slot name="title">
        Detalle de Recursos de la Requisición
    </x-slot>
    <x-slot name="content">

        <!-- Monto total calculado -->
        <div class="mb-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-300 px-4 py-3 rounded-lg flex items-center justify-between"
            role="alert">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <div>
                    <p class="font-semibold text-sm">Monto total de los recursos de esta requisición</p>
                    <p class="text-xs mt-0.5">Este es el monto total acumulado de todos los recursos seleccionados.</p>
                </div>
            </div>
            <div class="text-right">
                <div class="flex items-baseline">
                    <span class="text-3xl font-bold">L
                        {{ number_format(collect($detalleRecursos)->sum('total'), 2) }}</span>
                </div>
                <p class="text-xs mt-0.5">Monto total</p>
            </div>
        </div>

        <!-- Tabla de detalle de recursos -->
        <div class="overflow-x-auto" style="max-height: 400px; overflow-y: auto;">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700 mb-4">
                <thead class="bg-zinc-100 dark:bg-zinc-800">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase w-[25%]">
                            Recurso
                        </th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase w-[30%]">
                            Detalle Técnico
                        </th>
                        <th class="px-3 py-2 text-center text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase w-[10%]">
                            Cantidad
                        </th>
                        <th class="px-3 py-2 text-center text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase w-[15%]">
                            Precio Unitario
                        </th>
                        <th class="px-3 py-2 text-center text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase w-[15%]">
                            Total
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($detalleRecursos as $detalle)
                        <tr>
                            <td class="px-3 py-2 text-left text-sm text-zinc-700 dark:text-zinc-300">
                                {{ $detalle['recurso'] }}
                            </td>
                            <td class="px-3 py-2 text-left text-sm text-zinc-700 dark:text-zinc-300">
                                {{ $detalle['detalle_tecnico'] }}
                            </td>
                            <td class="px-3 py-2 text-center text-sm text-zinc-700 dark:text-zinc-300">
                                {{ $detalle['cantidad'] }}
                            </td>
                            <td class="px-3 py-2 text-center text-sm text-zinc-700 dark:text-zinc-300">
                                L {{ number_format($detalle['precio_unitario'], 2) }}
                            </td>
                            <td class="px-3 py-2 text-center text-sm text-zinc-700 dark:text-zinc-300">
                                L {{ number_format($detalle['total'], 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-3 py-2 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                No hay recursos para mostrar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-slot>
    <x-slot name="footer">
        <x-spinner-button wire:click="cerrarDetalleModal" class="bg-zinc-400 hover:bg-zinc-500">
            Cerrar
        </x-spinner-button>
    </x-slot>
</x-dialog-modal>
