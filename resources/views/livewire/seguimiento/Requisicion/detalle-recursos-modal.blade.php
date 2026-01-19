<!-- Modal de detalle de recursos de la requisición -->
<x-dialog-modal wire:model="showDetalleRecursosModal" maxWidth="4xl">
    <x-slot name="title">
        Detalle de Recursos de la Requisición
    </x-slot>
    <x-slot name="content">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700 mb-4">
                <thead class="bg-zinc-100 text-zinc-700">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold">Recurso</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold">Detalle Tecnico</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold">Cantidad</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold">Precio unitario</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold">Total</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold">Estado</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold">Ref. Acta E.</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($detalleRecursos as $detalle)
                        <tr>
                            <td class="px-4 py-2 align-top whitespace-nowrap">{{ $detalle['recurso'] }}</td>
                            <td class="px-4 py-2 align-top">{{ $detalle['detalle_tecnico'] }}</td>
                            <td class="px-4 py-2 align-top text-center">{{ $detalle['cantidad'] }}</td>
                            <td class="px-4 py-2 align-top text-center">L {{ number_format($detalle['precio_unitario'], 2) }}</td>
                            <td class="px-4 py-2 align-top text-center">L {{ number_format($detalle['total'], 2) }}</td>
                            <td class="px-4 py-2 align-top text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-zinc-300 text-zinc-900">{{ $detalle['estado'] }}</span>
                            </td>
                            <td class="px-4 py-2 align-top text-center">{{ $detalle['ref_acta'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-2 text-center text-zinc-500">No hay recursos para mostrar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-slot>
    <x-slot name="footer">
        <button wire:click="cerrarDetalleRecursosModal" class="px-4 py-2 bg-zinc-400 text-white rounded hover:bg-zinc-500">Cerrar</button>
    </x-slot>
</x-dialog-modal>
