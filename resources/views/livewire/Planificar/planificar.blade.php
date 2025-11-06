<div>
    <div class="mx-auto rounded-lg mt-8 sm:mt-6 lg:mt-4 mb-6">
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow sm:rounded-lg p-4 sm:p-6">
            
            <!-- Encabezado -->
            <div class="mb-6 pb-4 border-b border-zinc-200 dark:border-zinc-700">
                <h2 class="text-2xl font-bold text-zinc-800 dark:text-zinc-200">
                    Planificación - Historial de Asignaciones POA
                </h2>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-2">
                    Revisa todas tus asignaciones presupuestarias por departamento
                </p>
            </div>

            @if($departamentosUsuario->isEmpty())
                <!-- Sin departamentos asignados -->
                <div class="text-center py-12">
                    <div class="max-w-md mx-auto">
                        <div class="mx-auto h-24 w-24 text-zinc-400 mb-6">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round" 
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-3">
                            No tienes departamentos asignados
                        </h3>
                        <p class="text-zinc-500 dark:text-zinc-400">
                            Contacta con el administrador para que te asigne a un departamento.
                        </p>
                    </div>
                </div>
            @else
                <!-- Selector de Departamento (si tiene más de uno) -->
                @if($mostrarSelector)
                    <div class="mb-6">
                        <label for="departamento" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                            Selecciona un Departamento
                        </label>
                        <select wire:model.live="departamentoSeleccionado" 
                                id="departamento"
                                class="w-full sm:w-auto min-w-[300px] rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($departamentosUsuario as $depto)
                                <option value="{{ $depto->id }}">
                                    {{ $depto->name }} - {{ $depto->unidadEjecutora->name ?? 'Sin UE' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <!-- Información del departamento único -->
                    <div class="mb-6 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg p-4">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h6m-6 4h6m-6 4h6" />
                            </svg>
                            <div>
                                <h3 class="font-semibold text-indigo-900 dark:text-indigo-100">
                                    {{ $departamentosUsuario->first()->name }}
                                </h3>
                                <p class="text-sm text-indigo-700 dark:text-indigo-300">
                                    {{ $departamentosUsuario->first()->unidadEjecutora->name ?? 'Sin Unidad Ejecutora' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Historial de POAs -->
                @if($poasHistorial->isEmpty())
                    <div class="text-center py-12 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                        <div class="max-w-md mx-auto">
                            <div class="mx-auto h-20 w-20 text-zinc-400 mb-6">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round" 
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-3">
                                No hay asignaciones POA para este departamento
                            </h3>
                            <p class="text-zinc-500 dark:text-zinc-400">
                                Este departamento aún no tiene techos presupuestarios asignados en ningún POA.
                            </p>
                        </div>
                    </div>
                @else
                    <!-- Grid de tarjetas de POAs -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($poasHistorial as $poaData)
                            <div class="bg-white dark:bg-zinc-800 border-2 {{ $poaData['isActual'] ? 'border-indigo-500 dark:border-indigo-400' : 'border-zinc-200 dark:border-zinc-700' }} rounded-lg overflow-hidden hover:shadow-lg transition-all duration-200">
                                <!-- Header de la tarjeta -->
                                <div class="bg-gradient-to-r {{ $poaData['isActual'] ? 'from-indigo-500 to-indigo-600' : 'from-zinc-500 to-zinc-600' }} p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="text-white font-bold text-lg">
                                                {{ $poaData['anio'] }}
                                            </h3>
                                            @if($poaData['isActual'])
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-white text-indigo-600 mt-1">
                                                    Actual
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-zinc-700 text-white mt-1">
                                                    Historial
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <svg class="h-8 w-8 text-white opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contenido de la tarjeta -->
                                <div class="p-4 space-y-3">
                                    <!-- Información general -->
                                    <div class="border-b border-zinc-200 dark:border-zinc-700 pb-3">
                                        <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                            {{ $poaData['nombre'] }}
                                        </p>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                                            {{ $poaData['departamento']->name }}
                                        </p>
                                    </div>

                                    <!-- Monto total -->
                                    <div class="bg-zinc-50 dark:bg-zinc-900 rounded-lg p-3">
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs font-medium text-zinc-600 dark:text-zinc-400">
                                                Presupuesto Total:
                                            </span>
                                            <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                                                {{ number_format($poaData['montoTotal'], 2) }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Fuentes de financiamiento -->
                                    <div class="space-y-2">
                                        <p class="text-xs font-medium text-zinc-600 dark:text-zinc-400">
                                            Fuentes ({{ $poaData['cantidadFuentes'] }}):
                                        </p>
                                        <div class="space-y-1 max-h-32 overflow-y-auto">
                                            @foreach($poaData['fuentes'] as $fuente)
                                                <div class="flex justify-between items-center text-xs bg-zinc-100 dark:bg-zinc-700 rounded px-2 py-1">
                                                    <span class="text-zinc-700 dark:text-zinc-300 truncate flex-1">
                                                        {{ $fuente['identificador'] }} - {{ $fuente['fuente'] }}
                                                    </span>
                                                    <span class="font-medium text-zinc-900 dark:text-zinc-100 ml-2">
                                                        {{ number_format($fuente['monto'], 2) }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Botón de acción -->
                                    <div class="pt-2">
                                        <button wire:click="seleccionarPoa({{ $poaData['idPoa'] }})"
                                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white {{ $poaData['isActual'] ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-zinc-600 hover:bg-zinc-700' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150">
                                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            {{ $poaData['isActual'] ? 'Planificar' : 'Ver Detalle' }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Resumen estadístico -->
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="h-8 w-8 text-indigo-600 dark:text-indigo-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-indigo-900 dark:text-indigo-100">Total POAs</p>
                                    <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $poasHistorial->count() }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="h-8 w-8 text-green-600 dark:text-green-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-green-900 dark:text-green-100">Presupuesto Total</p>
                                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($poasHistorial->sum('montoTotal'), 2) }}</p>
                                </div>
                            </div>
                        </div>
                        
                       <!-- <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="h-8 w-8 text-blue-600 dark:text-blue-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-blue-900 dark:text-blue-100">Fuentes Activas</p>
                                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $poasHistorial->sum('cantidadFuentes') }}</p>
                                </div>
                            </div>
                        </div> -->
                    </div>
                @endif
            @endif

        </div>
    </div>
</div>