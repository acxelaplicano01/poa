<div>
    @can('seguimiento.requisiciones.ver')
        {{-- Banner informativo del plazo de seguimiento --}}
        @if (!$puedeSeguimiento && $mensajePlazoSeguimiento)
            <div class="mb-4 bg-amber-100 dark:bg-amber-900/30 border border-amber-400 dark:border-amber-700 text-amber-800 dark:text-amber-300 px-4 py-3 rounded-lg"
                role="alert">
                <div class="flex items-start justify-between">
                    <div class="flex items-start flex-1">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="font-semibold">Plazo de seguimiento no disponible</p>
                            <p class="text-sm mt-1">{{ $mensajePlazoSeguimiento }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Contador de días restantes para el plazo de seguimiento --}}
        @if ($puedeSeguimiento && $diasRestantes !== null)
            <div class="mb-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-300 px-4 py-3 rounded-lg flex items-center justify-between"
                role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <div>
                        <p class="font-semibold text-sm">Plazo de seguimiento activo</p>
                        <p class="text-xs mt-0.5">Puedes gestionar el seguimiento dentro del plazo establecido.</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="flex items-baseline">
                        <span class="text-3xl font-bold">{{ intval($diasRestantes) }}</span>
                        <span class="text-sm ml-1">{{ $diasRestantes == 1 ? 'día' : 'días' }}</span>
                    </div>
                    <p class="text-xs mt-0.5">{{ $diasRestantes == 1 ? 'restante' : 'restantes' }}</p>
                </div>
            </div>
        @endif

        <x-dialog-modal wire:model="showDetalleModal" maxWidth="4xl">
            <x-slot name="title">
                <div class="flex items-center justify-between w-full">
                    <span>
                        {{ $detalleRequisicion['correlativo'] ?? '' }} {{ $detalleRequisicion['departamento'] ?? '' }}
                    </span>
                    @if (isset($detalleRequisicion['correlativo']))
                        <button
                            wire:click="abrirPdfModal(
            '/requisicion/{{ $detalleRequisicion['correlativo'] }}/pdf',
            '/requisicion/{{ $detalleRequisicion['correlativo'] }}/pdf/download',
            'Requisición {{ $detalleRequisicion['correlativo'] }}'
        )"
                            class="ml-4 bg-red-600 hover:bg-red-700 text-white font-semibold px-2.5 py-1.5 rounded flex items-center gap-1 transition text-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            PDF
                        </button>
                    @endif
                </div>
            </x-slot>
            <x-slot name="content">
                <div class="mb-2">
                    <div class="text-sm text-zinc-600 dark:text-zinc-300 mb-2">
                        Presentado: {{ $detalleRequisicion['fecha_presentado'] ?? '' }}
                        Requerido: {{ $detalleRequisicion['fecha_requerido'] ?? '' }}
                        | <span class="font-semibold px-3 py-1 rounded-full text-xs"
                            @php
$estado = $detalleRequisicion['estado'] ?? '';
                        $color = match($estado) {
                            'Presentado' => 'bg-zinc-200 text-zinc-800 dark:bg-zinc-700 dark:text-zinc-200',
                            'Recibido' => 'bg-cyan-200 text-cyan-800 dark:bg-cyan-700 dark:text-cyan-100',
                            'En Proceso de Compra' => 'bg-yellow-200 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100',
                            'Aprobado' => 'bg-green-200 text-green-800 dark:bg-green-700 dark:text-green-100',
                            'Rechazado' => 'bg-red-200 text-red-800 dark:bg-red-700 dark:text-red-100',
                            default => 'bg-zinc-200 text-zinc-800 dark:bg-zinc-700 dark:text-zinc-200',
                        }; @endphp
                            class="{{ $color }}">Estado: {{ $estado }}
                    </div>
                </div>
                <div
                    class="overflow-x-auto bg-white dark:bg-zinc-900 rounded-lg shadow dark:shadow-lg border border-zinc-200 dark:border-zinc-700 p-4">
                    <div class="max-h-96 overflow-y-auto"> <!-- Add scrollable container -->
                        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700 mb-4">
                            <thead class="bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-semibold">Recurso</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold">Detalle Tecnico</th>
                                    <th class="px-4 py-2 text-center text-xs font-semibold">Cantidad</th>
                                    <th class="px-4 py-2 text-center text-xs font-semibold">Precio unitario</th>
                                    <th class="px-4 py-2 text-center text-xs font-semibold">Total</th>
                                    <th class="px-4 py-2 text-center text-xs font-semibold">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($detalleRecursos as $detalle)
                                    <tr class="bg-white dark:bg-zinc-900">
                                        <td
                                            class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase w-[20%]">
                                            {{ $detalle['recurso'] }}
                                        </td>
                                        <td
                                            class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase w-[25%]">
                                            {{ $detalle['detalle_tecnico'] }}
                                        </td>
                                        <td
                                            class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase w-[25%]">
                                            {{ $detalle['cantidad'] }}
                                        </td>
                                        <td
                                            class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase w-[25%]">
                                            L {{ number_format($detalle['precio_unitario'], 2) }}
                                        </td>
                                        <td
                                            class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase w-[25%]">
                                            L
                                            {{ number_format($detalle['total'], 2) }}
                                        </td>
                                        <td class="px-4 py-2 align-top text-center">
                                            @if (Str::of(strtolower($detalle['recurso'] ?? ''))->contains('gasolina') ||
                                                    Str::of(strtolower($detalle['recurso'] ?? ''))->contains('diesel'))
                                                @php
                                                    $detalleId =
                                                        $detalle['idDetalleRequisicion'] ?? ($detalle['id'] ?? null);
                                                @endphp
                                                @if ($detalleId)
                                                    <button
                                                        wire:click="abrirPdfModal(
                                                    '/orden-combustible/{{ $detalleId }}/pdf',
                                                    '/orden-combustible/{{ $detalleId }}/pdf/download',
                                                    'Orden de Combustible'
                                                )"
                                                        title="Descargar Orden de Combustible"
                                                        class="p-2 rounded-full hover:bg-yellow-100 text-yellow-700 transition">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                            class="w-6 h-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                        </svg>
                                                    </button>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-2 text-center text-zinc-500 dark:text-zinc-400">No
                                            hay
                                            recursos para mostrar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="border-t border-zinc-200 dark:border-zinc-700 pt-3 mt-3">
                        <div class="flex justify-between items-center">
                            <div class="text-right font-semibold text-zinc-900 dark:text-zinc-100">
                                Monto total: L {{ number_format($detalleRequisicion['monto_total'] ?? 0, 2) }}
                            </div>
                            @if (isset($detalleRequisicion['tipo_proceso']))
                                <div class="flex items-center gap-2">
                                    <span class="text-sm text-zinc-600 dark:text-zinc-400">Tipo de proceso sugerido:</span>
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    {{ ($detalleRequisicion['monto_total'] ?? 0) < 10000
                                        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                                        : (($detalleRequisicion['monto_total'] ?? 0) < 50000
                                            ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300'
                                            : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300') }}">
                                        {{ $detalleRequisicion['tipo_proceso']['nombre'] }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </x-slot>
            <x-slot name="footer">
                @php $estado = $detalleRequisicion['estado'] ?? ''; @endphp
                <div class="flex flex-row gap-3 w-full items-center">
                    @if ($estado === 'Presentado' || $estado === 'En Proceso de Compra')
                        <div class="flex-1">
                            <div class="relative">
                                <span
                                    class="absolute inset-y-0 left-0 flex items-center pl-3 text-zinc-400 dark:text-zinc-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                    </svg>
                                </span>
                                <x-input type="text" wire:model.defer="observacionModal" placeholder="Observación"
                                    class="pl-8 pr-2 py-2 border rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-200 bg-white dark:bg-zinc-800 dark:text-zinc-100 dark:border-zinc-700" />
                            </div>
                        </div>
                        <div class="flex flex-row gap-3 items-center justify-end">
                            @can('seguimiento.requisiciones.gestionar-estados')
                                @if ($estado === 'Presentado')
                                    <x-spinner-button wire:click="marcarComoRecibido" loadingTarget="marcarComoRecibido"
                                        class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded transition flex items-center gap-2 dark:bg-green-700 dark:hover:bg-green-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                        </svg>
                                        Recibido
                                    </x-spinner-button>
                                @endif
                                <x-spinner-button wire:click="marcarComoRechazado" loadingTarget="marcarComoRechazado"
                                    class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded transition flex items-center gap-2 dark:bg-red-700 dark:hover:bg-red-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                    Rechazar
                                </x-spinner-button>
                            @endcan
                        </div>
                    @elseif ($estado === 'Recibido')
                        @can('seguimiento.requisiciones.gestionar-estados')
                            <x-spinner-button wire:click="marcarComoAprobado" loadingTarget="marcarComoAprobado"
                                class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded transition flex items-center gap-2 dark:bg-green-700 dark:hover:bg-green-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                Aprobado
                            </x-spinner-button>
                        @endcan
                    @elseif ($estado === 'Aprobado')
                        @can('seguimiento.requisiciones.gestionar-estados')
                            <x-spinner-button wire:click="marcarComoProcesoCompra" loadingTarget="marcarComoProcesoCompra"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-6 py-2 rounded transition flex items-center gap-2 dark:bg-yellow-700 dark:hover:bg-yellow-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                </svg>
                                Proceso de Compra
                            </x-spinner-button>
                        @endcan
                    @endif
                    <x-spinner-button wire:click="cerrarDetalleModal" loadingTarget="cerrarDetalleModal"
                        class="bg-zinc-400 hover:bg-zinc-500 text-white font-semibold px-6 py-2 rounded transition dark:bg-zinc-600 dark:hover:bg-zinc-700">
                        Cancelar
                    </x-spinner-button>
                </div>
            </x-slot>
        </x-dialog-modal>
        <div class="bg-white dark:bg-zinc-900 rounded-lg shadow p-4 mb-6">
            <div class="flex flex-row flex-nowrap gap-2 mb-4 items-center w-full">
                <x-input wire:model.live="search" type="text" placeholder="Buscar requisición por correlativo o depto"
                    class="max-w-xs w-full text-sm" />
                <x-select wire:model.live="anio" :options="$anios->map(fn($a) => ['value' => $a, 'text' => $a])->toArray()" id="anio" class="max-w-[110px] w-full text-sm" />
                <x-select wire:model.live="departamento" :options="[['value' => 'Todos', 'text' => 'Todos']] +
                    $departamentos->map(fn($d) => ['value' => $d->id, 'text' => $d->name])->toArray()" id="departamento"
                    class="max-w-[150px] w-full text-sm" />
                <x-select wire:model.live="estado" :options="collect($estados)->map(fn($e) => ['value' => $e, 'text' => $e])->toArray()" id="estado"
                    class="max-w-[120px] w-full text-sm" />
                <x-select wire:model.live="perPage" :options="[
                    ['value' => '10', 'text' => '10 por pág'],
                    ['value' => '25', 'text' => '25 por pág'],
                    ['value' => '50', 'text' => '50 por pág'],
                    ['value' => '100', 'text' => '100 por pág'],
                ]" class="max-w-[120px] w-full text-sm" />
            </div>
            @if (session()->has('message'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md" role="alert">
                    <p class="font-medium">{{ session('message') }}</p>
                </div>
            @endif
            <x-table sort-field="{{ $sortField }}" sort-direction="{{ $sortDirection }}" :columns="[
                ['key' => 'correlativo', 'label' => 'Correlativo', 'sortable' => true],
                ['key' => 'departamento', 'label' => 'Departamento', 'sortable' => true],
                ['key' => 'descripcion', 'label' => 'Descripción', 'sortable' => true],
                ['key' => 'observacion', 'label' => 'Observación', 'sortable' => true],
                ['key' => 'estado', 'label' => 'Estado', 'sortable' => true],
                ['key' => 'actions', 'label' => 'Acción'],
            ]"
                empty-message="No se encontraron requisiciones" class="mt-6">
                <x-slot name="desktop">
                    @forelse ($requisiciones as $requisicion)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-200 px-3 py-1 rounded-full text-xs font-semibold">
                                    {{ $requisicion->correlativo }}
                                </span>
                            </td>
                            <td
                                class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase w-[40%]">
                                {{ $requisicion->departamento->name ?? '-' }}
                            </td>
                            <td
                                class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase w-[40%]">
                                {{ $requisicion->descripcion }}
                            </td>
                            <td
                                class="px-3 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase w-[40%]">
                                {{ $requisicion->observacion }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $estado = $requisicion->estado->estado ?? '';
                                    $color = match ($estado) {
                                        'Presentado' => 'bg-zinc-200 text-zinc-800 dark:bg-zinc-700 dark:text-zinc-200',
                                        'Recibido' => 'bg-blue-50 text-blue-700 dark:bg-blue-900 dark:text-blue-200',
                                        'En Proceso de Compra'
                                            => 'bg-yellow-200 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100',
                                        'Aprobado'
                                            => 'bg-green-200 text-green-800 dark:bg-green-700 dark:text-green-100',
                                        'Rechazado' => 'bg-red-200 text-red-800 dark:bg-red-700 dark:text-red-100',
                                        default => 'bg-zinc-200 text-zinc-800 dark:bg-zinc-700 dark:text-zinc-200',
                                    };
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $color }}">
                                    {{ $estado }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex gap-2 items-center">
                                    <button wire:click="verDetalleRequisicion({{ $requisicion->id }})"
                                        title="Ver Detalle"
                                        class="p-2 rounded-full hover:bg-blue-100 text-blue-700 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                                        </svg>
                                    </button>
                                    @if (($requisicion->estado->estado ?? '') === 'En Proceso de Compra')
                                        @if ($requisicion->plazoSeguimientoActivo)
                                            <a href="{{ route('entregarecursos', ['requisicionId' => $requisicion->id]) }}"
                                                title="Entrega de Recursos"
                                                class="p-2 rounded-full hover:bg-green-100 text-green-700 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v7.5m2.25-6.466a9.016 9.016 0 0 0-3.461-.203c-.536.072-.974.478-1.021 1.017a4.559 4.559 0 0 0-.018.402c0 .464.336.844.775.994l2.95 1.012c.44.15.775.53.775.994 0 .136-.006.27-.018.402-.047.539-.485.945-1.021 1.017a9.077 9.077 0 0 1-3.461-.203M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                                </svg>
                                            </a>
                                        @else
                                            <div class="relative group">
                                                <span
                                                    class="p-2 rounded-full text-zinc-300 dark:text-zinc-600 cursor-not-allowed inline-flex">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v7.5m2.25-6.466a9.016 9.016 0 0 0-3.461-.203c-.536.072-.974.478-1.021 1.017a4.559 4.559 0 0 0-.018.402c0 .464.336.844.775.994l2.95 1.012c.44.15.775.53.775.994 0 .136-.006.27-.018.402-.047.539-.485.945-1.021 1.017a9.077 9.077 0 0 1-3.461-.203M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                                    </svg>
                                                </span>
                                                <div
                                                    class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block
                        bg-zinc-800 text-white text-xs rounded-lg px-3 py-2 whitespace-nowrap shadow-lg z-50">
                                                    Plazo de seguimiento no activo
                                                    <div
                                                        class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-zinc-800">
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                    @if (($requisicion->estado->estado ?? '') === 'Finalizado')
                                        <button
                                            wire:click="abrirPdfModal(
            '/acta-entrega/{{ $requisicion->id }}/descargar',
            '/acta-entrega/{{ $requisicion->id }}/descargar/download',
            'Acta de Entrega Final'
        )"
                                            title="Ver Acta de Entrega"
                                            class="p-2 rounded-full hover:bg-red-100 text-red-700 transition">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </button>
                                    @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="h-12 w-12 text-zinc-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                        <p class="text-zinc-500 dark:text-zinc-400 text-lg font-medium">No se encontraron requisiciones</p>
                                        <p class="text-zinc-400 dark:text-zinc-500 text-sm mt-2">Intenta cambiar los filtros de búsqueda</p>
                                    </div>
                                </td>
                        </tr>
                    @endforelse
                </x-slot>
            </x-table>
            <div class="mt-4">
                {{ $requisiciones->links() }}
            </div>
        </div>

        @if ($showPdfModal)
            <x-dialog-modal wire:model="showPdfModal" maxWidth="4xl">
                <x-slot name="title">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">
                            {{ $pdfTitle }}
                        </h3>
                        <div class="flex items-center gap-3">
                            <a href="{{ $pdfDownloadUrl }}"
                                class="flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Descargar
                            </a>
                            <button wire:click="cerrarPdfModal"
                                class="p-1 rounded-full hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-500 dark:text-zinc-400 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </x-slot>
                <x-slot name="content">
                    <iframe src="{{ $pdfUrl }}"
                        class="w-full h-[70vh] rounded-lg border border-zinc-200 dark:border-zinc-700"
                        type="application/pdf">
                        <p class="text-center p-6 text-zinc-500">
                            Tu navegador no puede mostrar el PDF.
                            <a href="{{ $pdfDownloadUrl }}" class="text-blue-600 underline">Descárgalo aquí.</a>
                        </p>
                    </iframe>
                </x-slot>
                <x-slot name="footer">
                    <x-spinner-button wire:click="cerrarPdfModal" loadingTarget="cerrarPdfModal"
                        class="bg-zinc-400 hover:bg-zinc-500 text-white font-semibold px-6 py-2 rounded transition dark:bg-zinc-600 dark:hover:bg-zinc-700">
                        Cerrar
                    </x-spinner-button>
                </x-slot>
            </x-dialog-modal>
        @endif
    @endcan
</div>
