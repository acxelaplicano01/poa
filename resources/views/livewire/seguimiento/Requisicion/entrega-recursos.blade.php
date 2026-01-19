<div class="container mx-auto py-8">
    {{-- Mensajes de éxito y error --}}
    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md" role="alert">
            <p class="font-medium">{{ session('message') }}</p>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md" role="alert">
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    @endif

    @error('general')
        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md" role="alert">
            <p class="font-medium">{{ $message }}</p>
        </div>
    @enderror

    {{-- Modal de confirmación para finalizar requisición --}}
    <x-dialog-modal wire:model="showConfirmFinalizarModal" maxWidth="md">
        <x-slot name="title">
            <div class="flex items-center">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 dark:bg-yellow-900 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">
                        Confirmar Finalización
                    </h3>
                </div>
            </div>
        </x-slot>

        <x-slot name="content">
            <div class="mt-2">
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">
                    ¿Está seguro de que desea finalizar esta requisición?
                </p>
                <div class="mt-2 p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 flex items-center gap-3">
                    <svg class="h-8 w-8 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <div>
                        <span class="text-sm font-semibold text-zinc-800 dark:text-zinc-100 block">
                            {{ $detalleRequisicion['correlativo'] ?? '-' }}
                        </span>
                        <span class="text-xs text-zinc-600 dark:text-zinc-400">
                            {{ $detalleRequisicion['departamento'] ?? '-' }}
                        </span>
                    </div>
                </div>

                <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700 dark:text-yellow-200">
                                Esta acción no se puede deshacer. Una vez finalizada, no podrá registrar más ejecuciones en esta requisición.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-spinner-secondary-button wire:click="cerrarModalFinalizar" loadingTarget="cerrarModalFinalizar">
                Cancelar
            </x-spinner-secondary-button>

            <x-spinner-button class="ml-3" wire:click="confirmarFinalizarRequisicion" loadingTarget="confirmarFinalizarRequisicion">
                Finalizar Requisición
            </x-spinner-button>
        </x-slot>
    </x-dialog-modal>

    {{-- Modal para actualizar ejecución --}}
    <x-dialog-modal wire:model="showEjecucionModal" maxWidth="4xl">
        <x-slot name="title">
            <div class="flex flex-col gap-1">
                <span class="text-lg font-bold text-zinc-900 dark:text-zinc-100">Ejecución de Recurso</span>
                <div class="text-sm text-zinc-600 dark:text-zinc-400">
                    <span class="font-semibold">Correlativo:</span> {{ $detalleRequisicion['correlativo'] ?? '-' }}
                </div>
                <div class="text-sm text-zinc-600 dark:text-zinc-400">
                    <span class="font-semibold">Departamento:</span> {{ $detalleRequisicion['departamento'] ?? '-' }}
                </div>
            </div>
        </x-slot>
        <x-slot name="content">
            <div class="space-y-4">
                {{-- Mostrar errores generales en el modal --}}
                @error('general')
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md" role="alert">
                        <p class="font-medium">{{ $message }}</p>
                    </div>
                @enderror

                {{-- Información del recurso --}}
                <div class="bg-zinc-50 dark:bg-zinc-800 rounded-lg p-4 border border-zinc-200 dark:border-zinc-700">
                    <div class="grid grid-cols-1 gap-2">
                        <div class="text-sm">
                            <span class="font-semibold text-zinc-700 dark:text-zinc-300">Recurso requerido:</span> 
                            <span class="text-zinc-900 dark:text-zinc-100">{{ $recursoSeleccionado['recurso'] ?? '-' }}</span>
                        </div>
                        <div class="text-sm">
                            <span class="font-semibold text-zinc-700 dark:text-zinc-300">Detalle Técnico:</span> 
                            <span class="text-zinc-600 dark:text-zinc-400">{{ $recursoSeleccionado['detalle_tecnico'] ?? '-' }}</span>
                        </div>
                        
                    </div>
                </div>

                {{-- Monto requerido --}}
                <div class="grid grid-cols-3 gap-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                    <div>
                        <span class="text-xs text-blue-600 dark:text-blue-400 font-semibold block mb-1">Cantidad requerida</span>
                        <div class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ number_format($recursoSeleccionado['cantidad'] ?? 0, 2) }}</div>
                    </div>
                    <div>
                        <span class="text-xs text-blue-600 dark:text-blue-400 font-semibold block mb-1">Costo unitario</span>
                        <div class="text-2xl font-bold text-blue-700 dark:text-blue-300">
                            L {{ number_format(($recursoSeleccionado['cantidad'] ?? 0) > 0 ? ($recursoSeleccionado['monto_requerido'] ?? 0) / $recursoSeleccionado['cantidad'] : 0, 2) }}
                        </div>
                    </div>
                    <div>
                        <span class="text-xs text-purple-600 dark:text-purple-400 font-semibold block mb-1">Total requerido</span>
                        <div class="text-2xl font-bold text-purple-700 dark:text-purple-300">L {{ number_format($recursoSeleccionado['monto_requerido'] ?? 0, 2) }}</div>
                    </div>
                </div>

                {{-- Formulario de ejecución --}}
                <div class="bg-white dark:bg-zinc-900 rounded-lg p-4 border-2 border-green-200 dark:border-green-800">
                    <h3 class="text-sm font-semibold text-green-700 dark:text-green-300 mb-4">Datos de ejecución</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                                Cantidad ejecutada: <span class="text-red-500">*</span>
                            </label>
                            <input type="number" step="0.01" min="0" wire:model.live="cantidadEjecutada" 
                                class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-zinc-800 dark:text-zinc-100" 
                                placeholder="0.00" />
                            @error('cantidadEjecutada') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                                Monto unitario ejecutado: <span class="text-red-500">*</span>
                            </label>
                            <input type="number" step="0.01" min="0" wire:model.live="montoUnitarioEjecutado" 
                                class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-zinc-800 dark:text-zinc-100" 
                                placeholder="0.00" />
                            @error('montoUnitarioEjecutado') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Factura:</label>
                            <input type="text" wire:model.defer="factura"
                                class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-zinc-800 dark:text-zinc-100" />
                            @error('factura') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                                Fecha de ejecución: <span class="text-red-500">*</span>
                            </label>
                            <input type="date" wire:model.defer="fechaEjecucion"
                                class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-zinc-800 dark:text-zinc-100" />
                            @error('fechaEjecucion') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                {{-- Observación --}}
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Observación:</label>
                    <textarea wire:model.defer="observacionEjecucion" rows="3"
                        class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-800 dark:text-zinc-100"></textarea>
                    @error('observacionEjecucion') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Total ejecutado --}}
                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 border-2 border-green-300 dark:border-green-700">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-green-700 dark:text-green-300 font-semibold">Total ejecutado:</span>
                        <div class="text-3xl font-bold text-green-700 dark:text-green-300">
                            L {{ number_format((floatval($cantidadEjecutada ?? 0) * floatval($montoUnitarioEjecutado ?? 0)), 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <div class="flex gap-3 justify-end w-full">
                <button wire:click="cerrarModal" 
                    class="bg-zinc-400 hover:bg-zinc-500 text-white font-semibold px-6 py-2 rounded-lg transition dark:bg-zinc-600 dark:hover:bg-zinc-700">
                    Cancelar
                </button>
                <button wire:click="actualizarEjecucion"
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg transition flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    Guardar ejecución
                </button>
            </div>
        </x-slot>
    </x-dialog-modal>

    {{-- Modal para agregar observación a la ejecución --}}
    <x-dialog-modal wire:model.live="showObservacionEjecucionModal" maxWidth="md">
        <x-slot name="title">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span class="text-lg font-bold text-zinc-900 dark:text-zinc-100">Observación de Ejecución</span>
            </div>
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div class="text-sm text-zinc-600 dark:text-zinc-400">
                    <p class="mb-2">Agregue una observación para esta ejecución presupuestaria.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Observación:</label>
                    <textarea wire:model.defer="observacionEjecucionPresupuestaria" rows="4"
                        class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-800 dark:text-zinc-100"
                        placeholder="Escriba su observación aquí..."></textarea>
                    @error('observacionEjecucionPresupuestaria') 
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> 
                    @enderror
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-spinner-secondary-button wire:click="cerrarModalObservacionEjecucion" loadingTarget="cerrarModalObservacionEjecucion">
                Cancelar
            </x-spinner-secondary-button>

            <x-spinner-button class="ml-3" wire:click="guardarObservacionEjecucion" loadingTarget="guardarObservacionEjecucion">
                Guardar Observación
            </x-spinner-button>
        </x-slot>
    </x-dialog-modal>

    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold text-zinc-800 dark:text-zinc-100">Entrega de Recursos</h1>
            <div class="flex gap-2">
                @if(($requisicion->estado->estado ?? '') === 'Finalizado')
                    <a href="{{ route('acta-entrega.pdf', $requisicionId) }}" 
                        target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium bg-green-600 text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Descargar Acta PDF
                    </a>
                @endif
                <a href="{{ route('administrar-requisiciones') }}" 
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium bg-zinc-600 text-white hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    Regresar
                </a>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white dark:bg-zinc-900 rounded-lg shadow p-4">
            <div>
                <div class="text-sm text-zinc-600 dark:text-zinc-300 mb-1"><b>Correlativo:</b> {{ $detalleRequisicion['correlativo'] ?? '-' }}</div>
                <div class="text-sm text-zinc-600 dark:text-zinc-300 mb-1"><b>Departamento:</b> {{ $detalleRequisicion['departamento'] ?? '-' }}</div>
                <div class="text-sm text-zinc-600 dark:text-zinc-300 mb-1 flex items-center gap-2">
                    <span><b>Observación Ejecución:</b> {{ $detalleRequisicion['observacion_ejecucion'] ?? 'Sin Observación' }}</span>
                    <button wire:click="abrirModalObservacionEjecucion" 
                        title="Editar observación"
                        class="inline-flex items-center p-1 rounded-md text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </button>
                </div>
            </div>
            <div>
                <div class="text-sm text-zinc-600 dark:text-zinc-300 mb-1"><b>Estado:</b> {{ $detalleRequisicion['estado'] ?? '-' }}</div>
                <div class="text-sm text-zinc-600 dark:text-zinc-300 mb-1"><b>Fecha presentado:</b> {{ $detalleRequisicion['fecha_presentado'] ?? '-' }}</div>
                <div class="text-sm text-zinc-600 dark:text-zinc-300 mb-1"><b>Fecha requerido:</b> {{ $detalleRequisicion['fecha_requerido'] ?? '-' }}</div>
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto bg-white dark:bg-zinc-900 rounded-lg shadow border border-zinc-200 dark:border-zinc-700 p-4">
        {{-- Botón para finalizar requisición --}}
        <div class="mb-4 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-zinc-800 dark:text-zinc-100">Recursos de la Requisición</h2>
            @if(($requisicion->estado->estado ?? '') !== 'Finalizado')
                <x-spinner-button wire:click="abrirModalFinalizar" loadingTarget="abrirModalFinalizar" :loadingText="__('Abriendo...')"
                    class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    {{ __('Finalizar Requisición') }}
                </x-spinner-button>
            @else
                <div class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium bg-zinc-400 text-white cursor-not-allowed">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                    {{ __('Requisición Finalizada') }}
                </div>
            @endif
        </div>

        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700 mb-4">
            <thead class="bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-semibold">Recurso requerido</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold">Detalle Técnico</th>
                    <th class="px-4 py-2 text-center text-xs font-semibold">Observación</th>
                    <th class="px-4 py-2 text-center text-xs font-semibold">Factura</th>
                    <th class="px-4 py-2 text-center text-xs font-semibold">Fecha de ejecución</th>
                    <th class="px-4 py-2 text-center text-xs font-semibold">Requerido</th>
                    <th class="px-4 py-2 text-center text-xs font-semibold">Ejecutado</th>
                    <th class="px-4 py-2 text-center text-xs font-semibold">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                @foreach($recursosParaEntregar as $recurso)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50">
                        <td class="px-4 py-2 align-top text-sm whitespace-nowrap text-zinc-900 dark:text-zinc-100">{{ $recurso['recurso'] }}</td>
                        <td class="px-4 py-2 align-top text-sm text-zinc-600 dark:text-zinc-400">{{ $recurso['detalle_tecnico'] }}</td>
                        <td class="px-4 py-2 align-top text-center text-sm text-zinc-600 dark:text-zinc-400">{{ $recurso['observacion'] ?? '-' }}</td>
                        <td class="px-4 py-2 align-top text-center text-sm text-zinc-600 dark:text-zinc-400">{{ $recurso['factura'] ?? '-' }}</td>
                        <td class="px-4 py-2 align-top text-center text-sm text-zinc-600 dark:text-zinc-400">{{ $recurso['fecha_ejecucion'] ?? '-' }}</td>
                        <td class="px-4 py-2 align-top text-sm">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs font-semibold px-3 py-1 rounded-full">Cantidad</span>
                                    <span class="font-bold text-sm text-zinc-900 dark:text-zinc-100">{{ $recurso['cantidad'] }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="inline-block bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-xs font-semibold px-3 py-1 rounded-full">Costo unitario</span>
                                    <span class="font-bold text-sm text-zinc-900 dark:text-zinc-100">L {{ number_format($recurso['monto_requerido'] / max($recurso['cantidad'],1), 2) }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="inline-block bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 text-xs font-semibold px-3 py-1 rounded-full">Total</span>
                                    <span class="font-bold text-sm text-zinc-900 dark:text-zinc-100">L {{ number_format($recurso['monto_requerido'] ?? 0, 2) }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-2 align-top text-sm">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs font-semibold px-3 py-1 rounded-full">Cantidad</span>
                                    <span class="font-bold text-sm text-zinc-900 dark:text-zinc-100">{{ $recurso['entregado'] ?? 0 }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="inline-block bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-xs font-semibold px-3 py-1 rounded-full">Costo unitario</span>
                                    <span class="font-bold text-sm text-zinc-900 dark:text-zinc-100">L {{ number_format($recurso['monto_ejecutado'] / max($recurso['entregado'],1), 2) }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="inline-block bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 text-xs font-semibold px-3 py-1 rounded-full">Total</span>
                                    <span class="font-bold text-sm text-zinc-900 dark:text-zinc-100">L {{ number_format($recurso['monto_ejecutado'] ?? 0, 2) }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-2 align-top text-sm">
                            @if(($requisicion->estado->estado ?? '') !== 'Finalizado')
                                <button wire:click="abrirModalEjecucion({{ $recurso['id'] }})" 
                                    title="Registrar ejecución"
                                    class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                    <svg class="w-5 h-5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                    Ejecutar
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

