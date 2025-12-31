<div>
    <x-dialog-modal wire:model="showDetalleModal" maxWidth="4xl">
        <x-slot name="title">
            Detalle de Recursos de la Requisición
        </x-slot>
        <x-slot name="content">
            <div class="mb-2">
                <div class="text-lg font-semibold text-zinc-700 mb-1">
                    {{ $detalleRequisicion['correlativo'] ?? '' }} {{ $detalleRequisicion['departamento'] ?? '' }}
                </div>
                <div class="text-sm text-zinc-600 mb-2">
                    Presentado: {{ $detalleRequisicion['fecha_presentado'] ?? '' }}
                    Requerido: {{ $detalleRequisicion['fecha_requerido'] ?? '' }}
                    | <span class="font-semibold">Estado: {{ $detalleRequisicion['estado'] ?? '' }}</span>
                </div>
            </div>
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
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-zinc-900 text-white">{{ $detalle['estado'] }}</span>
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
    <div class="bg-white dark:bg-zinc-900 rounded-lg shadow p-4 mb-6">
        <div class="flex flex-row flex-nowrap gap-2 mb-4 items-center w-full">
            <x-input wire:model.live="search" type="text" placeholder="Buscar requisición por correlativo o depto" class="max-w-xs w-full text-sm" />
            <x-select wire:model.live="anio" :options="$anios->map(fn($a) => ['value' => $a, 'text' => $a])->toArray()" id="anio" class="max-w-[110px] w-full text-sm" />
            <x-select wire:model.live="departamento" :options="[['value'=>'Todos','text'=>'Todos']] + $departamentos->map(fn($d) => ['value'=>$d->id,'text'=>$d->name])->toArray()" id="departamento" class="max-w-[150px] w-full text-sm" />
            <x-select wire:model.live="estado" :options="collect($estados)->map(fn($e) => ['value'=>$e,'text'=>$e])->toArray()" id="estado" class="max-w-[120px] w-full text-sm" />
            <x-select wire:model.live="perPage" :options="[
                ['value'=>'10','text'=>'10 por pág'],
                ['value'=>'25','text'=>'25 por pág'],
                ['value'=>'50','text'=>'50 por pág'],
                ['value'=>'100','text'=>'100 por pág'],
            ]" class="max-w-[120px] w-full text-sm" />
        </div>
        @if (session()->has('message'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md" role="alert">
                <p class="font-medium">{{ session('message') }}</p>
            </div>
        @endif
        <x-table sort-field="{{ $sortField }}" sort-direction="{{ $sortDirection }}" :columns="[
            ['key'=>'correlativo','label'=>'Correlativo','sortable'=>true],
            ['key'=>'departamento','label'=>'Departamento','sortable'=>true],
            ['key'=>'descripcion','label'=>'Descripción','sortable'=>true],
            ['key'=>'observacion','label'=>'Observación','sortable'=>true],
            ['key'=>'estado','label'=>'Estado','sortable'=>true],
            ['key'=>'actions','label'=>'Acción']
        ]" empty-message="No se encontraron requisiciones" class="mt-6">
            <x-slot name="desktop">
                @forelse ($requisiciones as $requisicion)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                                {{ $requisicion->correlativo }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-300">{{ $requisicion->departamento->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-300">{{ $requisicion->descripcion }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-300">{{ $requisicion->observacion }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{
                                $requisicion->estado->estado === 'Finalizado' ? 'bg-green-200 text-green-800' :
                                ($requisicion->estado->estado === 'Aprobado' ? 'bg-cyan-200 text-cyan-800' :
                                ($requisicion->estado->estado === 'En Proceso de Compra' ? 'bg-blue-200 text-blue-800' :
                                ($requisicion->estado->estado === 'Rechazado' ? 'bg-red-200 text-red-800' :
                                ($requisicion->estado->estado === 'Recibido' ? 'bg-yellow-200 text-yellow-800' :
                                'bg-zinc-200 text-zinc-800'))))
                            }}">
                                {{ $requisicion->estado->estado ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex gap-2 items-center">
                                <button wire:click="verDetalleRequisicion({{ $requisicion->id }})" title="Ver Detalle" class="p-2 rounded-full hover:bg-blue-100 text-blue-700 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-zinc-500">No se encontraron requisiciones</td>
                    </tr>
                @endforelse
            </x-slot>
        </x-table>
        <div class="mt-4">
            {{ $requisiciones->links() }}
        </div>
    </div>
</div>
