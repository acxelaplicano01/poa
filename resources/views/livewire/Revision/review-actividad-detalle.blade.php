<div>
    <div class="mx-auto rounded-lg mt-8 sm:mt-6 lg:mt-4 mb-6">
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow sm:rounded-lg p-4 sm:p-6">

            <!-- Encabezado -->
            <div class="mb-6 pb-4 border-b border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center justify-between">
                    <div>
                        <a href="{{ route('revisiones') }}" wire:navigate
                            class="inline-flex items-center text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                            Volver a Revisiones
                        </a>
                        <h2 class="text-2xl font-bold text-zinc-800 dark:text-zinc-200">
                            Revisión de Actividad
                        </h2>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-2">
                            {{ $actividad->nombre }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                                    {{ $actividad->estado === 'REVISION' ? 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300' :
    ($actividad->estado === 'APROBADO' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' :
        ($actividad->estado === 'RECHAZADO' ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' :
            'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300')) }}">
                            {{ $actividad->estado }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Stepper Horizontal -->
            <div class="mb-8">
                <div class="flex items-center gap-2 overflow-x-auto pb-2">
                    @php
                        $tabs = [
                            ['key' => 'informacion', 'label' => 'Información'],
                            ['key' => 'indicadores', 'label' => 'Indicadores'],
                            ['key' => 'planificaciones', 'label' => 'Planificaciones'],
                            ['key' => 'tareas', 'label' => 'Tareas'],
                            ['key' => 'revisiones', 'label' => 'Revisiones', 'badge' => count($revisiones)],
                            ['key' => 'dictamen', 'label' => 'Dictamen'],
                        ];
                    @endphp

                    @foreach($tabs as $index => $tab)
                                    @php
                                        $isActive = $activeTab === $tab['key'];
                                    @endphp

                                    <!-- Paso -->
                                    <button type="button" wire:click="$set('activeTab', '{{ $tab['key'] }}')" class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all duration-200 text-sm font-medium whitespace-nowrap
                                                        {{ $isActive
                        ? 'bg-indigo-600 text-white shadow-md'
                        : 'bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-300 dark:hover:bg-zinc-600' }}">
                                        <div class="flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold
                                                        {{ $isActive ? 'bg-white/20' : 'bg-white/30' }}">
                                            {{ $index + 1 }}
                                        </div>
                                        <span class="hidden sm:inline">{{ $tab['label'] }}</span>
                                        @if(isset($tab['badge']) && $tab['badge'] > 0)
                                            <span
                                                class="ml-1 inline-flex items-center justify-center w-5 h-5 text-xs font-bold rounded-full bg-red-500 text-white">
                                                {{ $tab['badge'] }}
                                            </span>
                                        @endif
                                    </button>

                                    <!-- Conectador -->
                                    @if ($index < count($tabs) - 1)
                                        <div class="flex-1 h-1 bg-zinc-300 dark:bg-zinc-700 transition-colors duration-200 min-w-2"></div>
                                    @endif
                    @endforeach
                </div>
            </div>

            <!-- Mensajes de éxito/error -->
            @if (session()->has('message'))
                <div
                    class="mb-4 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-800 dark:text-green-300 px-4 py-3 rounded">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div
                    class="mb-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-800 dark:text-red-300 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Contenido de Tabs -->
            <div class="space-y-6">
                <!-- Tab: Información -->
                @if($activeTab === 'informacion')
                    <div>
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Información General</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-zinc-50 dark:bg-zinc-800 p-4 rounded-lg">
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">Nombre</p>
                                <p class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $actividad->nombre }}</p>
                            </div>
                            <div class="bg-zinc-50 dark:bg-zinc-800 p-4 rounded-lg">
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">Tipo</p>
                                <p class="font-semibold text-zinc-900 dark:text-zinc-100">
                                    {{ $actividad->tipo->tipo ?? 'N/A' }}</p>
                            </div>
                            <div class="bg-zinc-50 dark:bg-zinc-800 p-4 rounded-lg">
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">Categoría</p>
                                <p class="font-semibold text-zinc-900 dark:text-zinc-100">
                                    {{ $actividad->categoria->categoria ?? 'N/A' }}</p>
                            </div>
                            <div class="bg-zinc-50 dark:bg-zinc-800 p-4 rounded-lg">
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">Resultado</p>
                                <p class="font-semibold text-zinc-900 dark:text-zinc-100">
                                    {{ $actividad->resultado->nombre ?? 'Sin vincular' }}</p>
                            </div>
                        </div>
                        <div class="mt-4 bg-zinc-50 dark:bg-zinc-800 p-4 rounded-lg">
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">Descripción</p>
                            <p class="font-semibold text-zinc-900 dark:text-zinc-100 mt-1">{{ $actividad->descripcion }}</p>
                        </div>
                    </div>
                @endif

                <!-- Tab: Indicadores -->
                @if($activeTab === 'indicadores')
                    <div>
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Indicadores</h3>
                        @if($actividad->indicadores->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                                    <thead class="bg-zinc-50 dark:bg-zinc-700">
                                        <tr>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                                Nombre</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                                Descripción</th>
                                            <th
                                                class="px-4 py-3 text-center text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                                Meta</th>
                                            <th
                                                class="px-4 py-3 text-center text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                                Tipo</th>
                                            @if($actividad->estado !== 'APROBADO' && $actividad->estado !== 'RECHAZADO')
                                                <th
                                                    class="px-4 py-3 text-center text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                                    Acción</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                                        @foreach($actividad->indicadores as $indicador)
                                            @php
                                                // Verificar si hay comentarios para este indicador
                                                $comentariosIndicador = $actividad->revisiones()
                                                    ->where('tipo', 'INDICADOR')
                                                    ->where('idElemento', $indicador->id)
                                                    ->orderBy('created_at', 'desc')
                                                    ->get();
                                                $ultimoComentarioIndicador = $comentariosIndicador->first();
                                            @endphp
                                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50">
                                                <td class="px-4 py-3 text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                    {{ $indicador->nombre }}
                                                </td>
                                                <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">
                                                    {{ $indicador->descripcion }}
                                                </td>
                                                <td
                                                    class="px-4 py-3 text-sm text-center font-semibold text-zinc-900 dark:text-zinc-100">
                                                    {{ $indicador->cantidadPlanificada }}
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <div class="flex justify-center gap-1">
                                                        @if($indicador->isCantidad)
                                                            <span
                                                                class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded text-xs">Cantidad</span>
                                                        @endif
                                                        @if($indicador->isPorcentaje)
                                                            <span
                                                                class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded text-xs">Porcentaje</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                @if($actividad->estado !== 'APROBADO' && $actividad->estado !== 'RECHAZADO')
                                                    <td class="px-4 py-3 text-center">
                                                        <button wire:click="abrirComentarioModal('INDICADOR', {{ $indicador->id }})"
                                                            class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-md transition cursor-pointer">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                            Rechazar
                                                        </button>
                                                        @if($ultimoComentarioIndicador)
                                                            @if(!$ultimoComentarioIndicador->corregido)
                                                                <span
                                                                    class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300">
                                                                    Sin Corregir
                                                                </span>
                                                            @else
                                                                <span
                                                                    class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                                                    ✓ Corregido
                                                                </span>
                                                            @endif
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                            {{-- Fila de comentarios --}}
                                            @if($comentariosIndicador->isNotEmpty())
                                                <tr>
                                                    <td colspan="{{ $actividad->estado !== 'APROBADO' && $actividad->estado !== 'RECHAZADO' ? '5' : '4' }}"
                                                        class="px-4 py-2 bg-purple-50 dark:bg-purple-900/20">
                                                        <div class="text-xs text-purple-700 dark:text-purple-300">
                                                            <span class="font-semibold">Comentario:</span>
                                                            {{ $ultimoComentarioIndicador->revision }}
                                                            <span
                                                                class="text-purple-600 dark:text-purple-400 ml-2">{{ $ultimoComentarioIndicador->created_at->format('d/m/Y H:i') }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-zinc-500 dark:text-zinc-400 text-center py-8">No hay indicadores registrados</p>
                        @endif
                    </div>
                @endif

                <!-- Tab: Planificaciones -->
                @if($activeTab === 'planificaciones')
                    <div>
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Planificaciones por
                            Trimestre</h3>
                        @if($actividad->indicadores->count() > 0)
                            <div class="space-y-6">
                                @foreach($actividad->indicadores as $indicador)
                                    @if($indicador->planificacions->count() > 0)
                                        <div class="bg-zinc-50 dark:bg-zinc-800 p-4 rounded-lg border-l-4 border-blue-500">
                                            <div class="mb-4">
                                                <div class="flex justify-between items-start">
                                                    <div class="flex-1">
                                                        <h4 class="font-semibold text-zinc-900 dark:text-zinc-100">
                                                            {{ $indicador->nombre }}</h4>
                                                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">
                                                            {{ $indicador->descripcion }}</p>
                                                    </div>
                                                    <div class="ml-4 flex items-center gap-2">
                                                        <div class="text-right">
                                                            <p class="text-xs text-zinc-500 dark:text-zinc-400">Meta</p>
                                                            <p class="text-lg font-bold text-zinc-900 dark:text-zinc-100">
                                                                {{ $indicador->cantidadPlanificada }}</p>
                                                        </div>
                                                        <div class="flex flex-col gap-1">
                                                            @if($indicador->isCantidad)
                                                                <span
                                                                    class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded text-xs whitespace-nowrap">Cantidad</span>
                                                            @endif
                                                            @if($indicador->isPorcentaje)
                                                                <span
                                                                    class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded text-xs whitespace-nowrap">Porcentaje</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @php
                                                $planificacionesPorTrimestre = $indicador->planificacions->groupBy(function ($p) {
                                                    return $p->mes->trimestre->trimestre ?? 'N/A';
                                                })->sortKeys();
                                            @endphp

                                            <div class="space-y-3">
                                                @foreach($planificacionesPorTrimestre as $trimestreNombre => $planificaciones)
                                                    <div class="bg-white dark:bg-zinc-700 p-3 rounded">
                                                        <div class="flex items-center gap-2 mb-2">
                                                            <svg class="w-4 h-4 text-zinc-600 dark:text-zinc-400" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                            <p class="text-sm font-bold text-zinc-700 dark:text-zinc-300">
                                                                {{ $trimestreNombre }}</p>
                                                        </div>
                                                        <div class="overflow-x-auto">
                                                            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-600">
                                                                <thead class="bg-zinc-100 dark:bg-zinc-600">
                                                                    <tr>
                                                                        <th
                                                                            class="px-2 py-1 text-left text-xs font-medium text-zinc-600 dark:text-zinc-300">
                                                                            Mes</th>
                                                                        <th
                                                                            class="px-2 py-1 text-center text-xs font-medium text-zinc-600 dark:text-zinc-300">
                                                                            Cantidad</th>
                                                                        <th
                                                                            class="px-2 py-1 text-left text-xs font-medium text-zinc-600 dark:text-zinc-300">
                                                                            Fecha Inicio</th>
                                                                        <th
                                                                            class="px-2 py-1 text-left text-xs font-medium text-zinc-600 dark:text-zinc-300">
                                                                            Fecha Fin</th>
                                                                        @if($actividad->estado !== 'APROBADO' && $actividad->estado !== 'RECHAZADO')
                                                                            <th
                                                                                class="px-2 py-1 text-center text-xs font-medium text-zinc-600 dark:text-zinc-300">
                                                                                Acción</th>
                                                                        @endif
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-600">
                                                                    @foreach($planificaciones as $plan)
                                                                        @php
                                                                            // Verificar si hay comentarios para esta planificación específica
                                                                            $comentariosPlan = $actividad->revisiones()
                                                                                ->where('tipo', 'PLANIFICACION')
                                                                                ->where('idElemento', $plan->id)
                                                                                ->orderBy('created_at', 'desc')
                                                                                ->get();
                                                                            $ultimoComentario = $comentariosPlan->first();
                                                                        @endphp
                                                                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-600/50">
                                                                            <td class="px-2 py-1 text-sm text-zinc-800 dark:text-zinc-200">
                                                                                {{ $plan->mes->mes ?? 'N/A' }}</td>
                                                                            <td
                                                                                class="px-2 py-1 text-sm text-center text-zinc-800 dark:text-zinc-200 font-semibold">
                                                                                {{ $plan->cantidad }}</td>
                                                                            <td class="px-2 py-1 text-sm text-zinc-600 dark:text-zinc-400">
                                                                                {{ \Carbon\Carbon::parse($plan->fechaInicio)->format('d/m/Y') }}
                                                                            </td>
                                                                            <td class="px-2 py-1 text-sm text-zinc-600 dark:text-zinc-400">
                                                                                {{ \Carbon\Carbon::parse($plan->fechaFin)->format('d/m/Y') }}
                                                                            </td>
                                                                            @if($actividad->estado !== 'APROBADO' && $actividad->estado !== 'RECHAZADO')
                                                                                <td class="px-2 py-1 text-center">
                                                                                    <button
                                                                                        wire:click="abrirComentarioModal('PLANIFICACION', {{ $plan->id }})"
                                                                                        class="inline-flex items-center px-2 py-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded transition cursor-pointer"
                                                                                        title="Rechazar planificación">
                                                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                                                            viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                                        </svg>
                                                                                        Rechazar
                                                                                    </button>
                                                                                    @if($ultimoComentario)
                                                                                        @if(!$ultimoComentario->corregido)
                                                                                            <span
                                                                                                class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300">
                                                                                                Sin Corregir
                                                                                            </span>
                                                                                        @else
                                                                                            <span
                                                                                                class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                                                                                ✓ Corregido
                                                                                            </span>
                                                                                        @endif
                                                                                    @endif
                                                                                </td>
                                                                            @endif
                                                                        </tr>
                                                                        {{-- Fila de comentarios --}}
                                                                        @if($comentariosPlan->isNotEmpty())
                                                                            <tr>
                                                                                <td colspan="{{ $actividad->estado !== 'APROBADO' && $actividad->estado !== 'RECHAZADO' ? '5' : '4' }}"
                                                                                    class="px-2 py-2 bg-purple-50 dark:bg-purple-900/20">
                                                                                    <div class="text-xs text-purple-700 dark:text-purple-300">
                                                                                        <span class="font-semibold">Comentario:</span>
                                                                                        {{ $ultimoComentario->revision }}
                                                                                        <span
                                                                                            class="text-purple-600 dark:text-purple-400 ml-2">{{ $ultimoComentario->created_at->format('d/m/Y H:i') }}</span>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        @endif
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <p class="text-zinc-500 dark:text-zinc-400 text-center py-8">No hay planificaciones registradas</p>
                        @endif
                    </div>
                @endif

                <!-- Tab: Tareas -->
                @if($activeTab === 'tareas')
                    <div>
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Tareas</h3>

                        @php
                            $tareasConPresupuesto = $actividad->tareas->filter(fn($t) => $t->presupuestos->count() > 0);
                            $tareasSinPresupuesto = $actividad->tareas->filter(fn($t) => $t->presupuestos->count() === 0);
                        @endphp

                        <!-- Tareas con Presupuesto -->
                        @if($tareasConPresupuesto->count() > 0)
                                    <div class="mb-8">
                                        <h4 class="text-md font-semibold text-green-700 dark:text-green-400 mb-4 flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    fill-rule="evenodd" />
                                            </svg>
                                            Tareas con Presupuesto ({{ $tareasConPresupuesto->count() }})
                                        </h4>
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                                                <thead class="bg-zinc-50 dark:bg-zinc-700">
                                                    <tr>
                                                        <th
                                                            class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                                            #</th>
                                                        <th
                                                            class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                                            Tarea</th>
                                                        <th
                                                            class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                                            Descripción</th>
                                                        <th
                                                            class="px-4 py-3 text-center text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                                            Asignados</th>
                                                        <th
                                                            class="px-4 py-3 text-center text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                                            Estado</th>
                                                        <th
                                                            class="px-4 py-3 text-center text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                                            Total</th>
                                                        <th
                                                            class="px-4 py-3 text-center text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                                            Recursos</th>
                                                        <th
                                                            class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                                            Veredicto</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                                                    @foreach($tareasConPresupuesto as $tarea)
                                                        @php
                                                            // Verificar si hay comentarios para esta tarea específica
                                                            $comentariosTarea = $actividad->revisiones()
                                                                ->where('tipo', 'TAREA')
                                                                ->where('idElemento', $tarea->id)
                                                                ->orderBy('created_at', 'desc')
                                                                ->get();
                                                            $ultimoComentarioTarea = $comentariosTarea->first();
                                                        @endphp
                                                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50">
                                                            <td class="px-4 py-3 text-sm">
                                                                <span
                                                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-zinc-100 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-200">
                                                                    {{ $tarea->correlativo ?? 'N/A' }}
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-3">
                                                                <div class="flex flex-col">
                                                                    <span
                                                                        class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $tarea->nombre }}</span>
                                                                    <span
                                                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 mt-1 w-fit">Con
                                                                        Presupuesto</span>
                                                                </div>
                                                            </td>
                                                            <td
                                                                class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400 max-w-xs truncate">
                                                                {{ $tarea->descripcion }}</td>
                                                            <td class="px-4 py-3">
                                                                @if($tarea->empleados->count() > 0)
                                                                    <div class="flex items-center justify-center">
                                                                        <div class="flex -space-x-2">
                                                                            @foreach($tarea->empleados->slice(0, 3) as $empleado)
                                                                                <div class="h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center border-2 border-white dark:border-zinc-800"
                                                                                    title="{{ $empleado->nombre }} {{ $empleado->apellido }}">
                                                                                    <span
                                                                                        class="text-xs text-indigo-600 dark:text-indigo-300 font-semibold">{{ strtoupper(substr($empleado->nombre, 0, 1)) }}</span>
                                                                                </div>
                                                                            @endforeach
                                                                            @if($tarea->empleados->count() > 3)
                                                                                <div
                                                                                    class="h-8 w-8 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center border-2 border-white dark:border-zinc-800">
                                                                                    <span
                                                                                        class="text-xs text-zinc-600 dark:text-zinc-300 font-semibold">+{{ $tarea->empleados->count() - 3 }}</span>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div class="text-center text-xs text-zinc-400">Sin asignar</div>
                                                                @endif
                                                            </td>
                                                            <td class="px-4 py-3 text-center">
                                                                @php
                                                                    $tareaCorregida = $ultimoComentarioTarea && $ultimoComentarioTarea->corregido;
                                                                @endphp

                                                                @if($tarea->estado === 'APROBADO')
                                                                    <span
                                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                                                        APROBADO
                                                                    </span>
                                                                @elseif($tarea->estado === 'RECHAZADO')
                                                                    <div class="flex flex-col items-center gap-1">
                                                                        <span
                                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                                                            RECHAZADO
                                                                        </span>
                                                                    </div>
                                                                @else
                                                                    <span
                                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300">
                                                                        EN REVISIÓN
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td
                                                                class="px-4 py-3 text-right text-sm font-semibold text-green-700 dark:text-green-400">
                                                                L. {{ number_format($tarea->presupuestos->sum('total') ?? 0, 2) }}
                                                            </td>
                                                            <td class="px-4 py-3 text-center">
                                                                <button wire:click="openTareaModal({{ $tarea->id }})"
                                                                    class="inline-flex items-center p-1.5 text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 cursor-pointer"
                                                                    title="Ver detalles de presupuesto">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                                    </svg>
                                                                </button>
                                                            </td>
                                                            <td class="px-4 py-3">
                                                                <div class="flex gap-2 justify-center">
                                                                    <button wire:click="aprobarTarea({{ $tarea->id }})"
                                                                        class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-md transition cursor-pointer">
                                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd"
                                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                                clip-rule="evenodd" />
                                                                        </svg>
                                                                        Aceptar
                                                                    </button>
                                                                    <button wire:click="abrirModalRechazo({{ $tarea->id }})"
                                                                        class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-md transition cursor-pointer">
                                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd"
                                                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                                clip-rule="evenodd" />
                                                                        </svg>
                                                                        Rechazar
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        {{-- Fila de comentarios --}}
                                                        @if($comentariosTarea->isNotEmpty())
                                                            <tr>
                                                                <td colspan="8" class="px-4 py-2 bg-purple-50 dark:bg-purple-900/20">
                                                                    <div class="text-xs text-purple-700 dark:text-purple-300">
                                                                        <span class="font-semibold">Comentario:</span> {{ $ultimoComentarioTarea->revision }}
                                                                        <span class="text-purple-600 dark:text-purple-400 ml-2">{{ $ultimoComentarioTarea->created_at->format('d/m/Y H:i') }}</span>
                                                                        @if($ultimoComentarioTarea->corregido)
                                                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                                                                ✓ Corregido
                                                                            </span>
                                                                        @else
                                                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300">
                                                                                Sin Corregir
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                                <tfoot class="bg-zinc-50 dark:bg-zinc-700">
                                                    <tr>
                                                        <td colspan="7"
                                                            class="px-4 py-3 text-right text-sm font-bold text-zinc-900 dark:text-zinc-100">
                                                            Total Presupuesto:</td>
                                                        <td
                                                            class="px-4 py-3 text-right text-sm font-bold text-green-700 dark:text-green-400">
                                                            L. {{ number_format($tareasConPresupuesto->sum(function ($t) {
                                                            return $t->presupuestos->sum('total'); }) ?? 0, 2) }}
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                        @endif

                        <!-- Tareas sin Presupuesto -->
                        @if($tareasSinPresupuesto->count() > 0)
                            <div>
                                <h4 class="text-md font-semibold text-gray-700 dark:text-gray-400 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            fill-rule="evenodd" />
                                    </svg>
                                    Tareas sin Presupuesto ({{ $tareasSinPresupuesto->count() }})
                                </h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                                        <thead class="bg-zinc-50 dark:bg-zinc-700">
                                            <tr>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                                    #</th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                                    Tarea</th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                                    Descripción</th>
                                                <th
                                                    class="px-4 py-3 text-center text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                                    Asignados</th>
                                                <th
                                                    class="px-4 py-3 text-center text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                                    Estado</th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                                    Veredicto</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                                            @foreach($tareasSinPresupuesto as $tarea)
                                                @php
                                                    // Verificar si hay comentarios para esta tarea específica
                                                    $comentariosTarea = $actividad->revisiones()
                                                        ->where('tipo', 'TAREA')
                                                        ->where('idElemento', $tarea->id)
                                                        ->orderBy('created_at', 'desc')
                                                        ->get();
                                                    $ultimoComentarioTarea = $comentariosTarea->first();
                                                @endphp
                                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50">
                                                    <td class="px-4 py-3 text-sm">
                                                        <span
                                                            class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-zinc-100 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-200">
                                                            {{ $tarea->correlativo ?? 'N/A' }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex flex-col">
                                                            <span
                                                                class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $tarea->nombre }}</span>
                                                            <span
                                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 mt-1 w-fit">Sin
                                                                Presupuesto</span>
                                                        </div>
                                                    </td>
                                                    <td
                                                        class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400 max-w-xs truncate">
                                                        {{ $tarea->descripcion }}</td>
                                                    <td class="px-4 py-3">
                                                        @if($tarea->empleados->count() > 0)
                                                            <div class="flex items-center justify-center">
                                                                <div class="flex -space-x-2">
                                                                    @foreach($tarea->empleados->slice(0, 3) as $empleado)
                                                                        <div class="h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center border-2 border-white dark:border-zinc-800"
                                                                            title="{{ $empleado->nombre }} {{ $empleado->apellido }}">
                                                                            <span
                                                                                class="text-xs text-indigo-600 dark:text-indigo-300 font-semibold">{{ strtoupper(substr($empleado->nombre, 0, 1)) }}</span>
                                                                        </div>
                                                                    @endforeach
                                                                    @if($tarea->empleados->count() > 3)
                                                                        <div
                                                                            class="h-8 w-8 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center border-2 border-white dark:border-zinc-800">
                                                                            <span
                                                                                class="text-xs text-zinc-600 dark:text-zinc-300 font-semibold">+{{ $tarea->empleados->count() - 3 }}</span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="text-center text-xs text-zinc-400">Sin asignar</div>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-3 text-center">
                                                        @php
                                                            $tareaCorregida = $ultimoComentarioTarea && $ultimoComentarioTarea->corregido;
                                                        @endphp

                                                        @if($tarea->estado === 'APROBADO')
                                                            <span
                                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                                                APROBADO
                                                            </span>
                                                        @elseif($tarea->estado === 'RECHAZADO')
                                                            <div class="flex flex-col items-center gap-1">
                                                                <span
                                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                                                    RECHAZADO
                                                                </span>
                                                            </div>
                                                        @else
                                                            <span
                                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300">
                                                                EN REVISIÓN
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex gap-2 justify-center">
                                                            <button wire:click="aprobarTarea({{ $tarea->id }})"
                                                                class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-md transition cursor-pointer">
                                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd"
                                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                                Aceptar
                                                            </button>
                                                            <button wire:click="abrirModalRechazo({{ $tarea->id }})"
                                                                class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-md transition cursor-pointer">
                                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd"
                                                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                                Rechazar
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                {{-- Fila de comentarios --}}
                                                @if($comentariosTarea->isNotEmpty())
                                                    <tr>
                                                        <td colspan="6" class="px-4 py-2 bg-purple-50 dark:bg-purple-900/20">
                                                            <div class="text-xs text-purple-700 dark:text-purple-300">
                                                                <span class="font-semibold">Comentario:</span> {{ $ultimoComentarioTarea->revision }}
                                                                <span class="text-purple-600 dark:text-purple-400 ml-2">{{ $ultimoComentarioTarea->created_at->format('d/m/Y H:i') }}</span>
                                                                @if($ultimoComentarioTarea->corregido)
                                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                                                        ✓ Corregido
                                                                    </span>
                                                                @else
                                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300">
                                                                        Sin Corregir
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        @if($tareasConPresupuesto->count() === 0 && $tareasSinPresupuesto->count() === 0)
                            <p class="text-zinc-500 dark:text-zinc-400 text-center py-8">No hay tareas registradas</p>
                        @endif
                    </div>
                @endif

                <!-- Tab: Revisiones -->
                @if($activeTab === 'revisiones')
                    <div>
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Historial de Revisiones</h3>
                        @if(count($revisiones) > 0)
                            <div class="space-y-4">
                                @foreach($revisiones as $revision)
                                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 p-4 rounded">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <p class="font-semibold text-zinc-900 dark:text-zinc-100">
                                                        {{ $revision['tipo'] }}</p>
                                                    @if(isset($revision['elemento_nombre']) && $revision['elemento_nombre'])
                                                        <span class="text-sm text-zinc-600 dark:text-zinc-400">-</span>
                                                        <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                                            {{ $revision['elemento_nombre'] }}</p>
                                                    @endif
                                                    @if($revision['tipo'] === 'TAREA' || $revision['tipo'] === 'INDICADOR' || $revision['tipo'] === 'PLANIFICACION')
                                                        @if($revision['corregido'])
                                                            <span
                                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                                                ✓ Corregida
                                                            </span>
                                                        @else
                                                            <span
                                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300">
                                                                Sin Corregir
                                                            </span>
                                                        @endif
                                                    @endif
                                                </div>
                                                <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-2">{{ $revision['revision'] }}
                                                </p>
                                            </div>
                                            <span class="text-xs text-zinc-500 dark:text-zinc-400 whitespace-nowrap ml-2">
                                                {{ \Carbon\Carbon::parse($revision['created_at'])->format('d/m/Y H:i') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-zinc-500 dark:text-zinc-400 text-center py-8">No hay revisiones registradas</p>
                        @endif
                    </div>
                @endif

                <!-- Tab: Dictamen -->
                @if($activeTab === 'dictamen')
                    <div>
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Emitir Dictamen</h3>

                        @if($actividad->estado === 'REVISION' || $actividad->estado === 'REFORMULACION')
                            <div class="space-y-4">
                                <!-- Opción 1: Enviar a Reformulación -->
                                @if(!$showFormDictamen)
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                        <button wire:click="$set('showFormRevision', true)"
                                            class="p-4 border-2 border-yellow-300 dark:border-yellow-700 rounded-lg hover:bg-yellow-50 dark:hover:bg-yellow-900/20 transition-colors cursor-pointer">
                                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400 mx-auto mb-2" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4v2m0 6v2M7.08 6.47A9.96 9.96 0 0112 1a9.96 9.96 0 014.92 5.47" />
                                            </svg>
                                            <h4 class="font-semibold text-yellow-900 dark:text-yellow-100">Enviar a Reformular</h4>
                                            <p class="text-sm text-yellow-700 dark:text-yellow-400 mt-1">Solicitar cambios al
                                                usuario</p>
                                        </button>

                                        <button wire:click="$set('showFormDictamen', true)"
                                            class="p-4 border-2 border-indigo-300 dark:border-indigo-700 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors cursor-pointer">
                                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400 mx-auto mb-2" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4M7 12a5 5 0 1110 0 5 5 0 01-10 0z" />
                                            </svg>
                                            <h4 class="font-semibold text-indigo-900 dark:text-indigo-100">Emitir Dictamen Final
                                            </h4>
                                            <p class="text-sm text-indigo-700 dark:text-indigo-400 mt-1">Aceptar o rechazar
                                                actividad</p>
                                        </button>
                                    </div>

                                    <!-- Formulario de Revisión -->
                                    @if($showFormRevision)
                                        <div
                                            class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-300 dark:border-yellow-700 p-4 rounded-lg">
                                            <h4 class="font-semibold text-yellow-900 dark:text-yellow-100 mb-3">Comentarios para
                                                Reformulación</h4>
                                            <textarea wire:model="comentarioRevision"
                                                class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200"
                                                rows="4" placeholder="Indique qué debe reformular el usuario..."></textarea>
                                            @error('comentarioRevision') <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror

                                            <div class="mt-4 flex gap-2">
                                                <button wire:click="enviarParaReformulacion"
                                                    class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg font-medium cursor-pointer">
                                                    Enviar a Reformular
                                                </button>
                                                <button wire:click="$set('showFormRevision', false)"
                                                    class="px-4 py-2 bg-zinc-400 hover:bg-zinc-500 text-white rounded-lg font-medium cursor-pointer">
                                                    Cancelar
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                <!-- Formulario de Dictamen -->
                                @if($showFormDictamen)
                                    <div
                                        class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-300 dark:border-indigo-700 p-4 rounded-lg">
                                        <h4 class="font-semibold text-indigo-900 dark:text-indigo-100 mb-4">Dictamen Final</h4>

                                        <div class="mb-4">
                                            <label
                                                class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Decisión</label>
                                            <div class="flex gap-4">
                                                <label class="flex items-center">
                                                    <input type="radio" wire:model="tipoDictamen" value="aceptar" class="rounded">
                                                    <span class="ml-2 text-green-700 dark:text-green-300 font-medium">Aceptar</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="radio" wire:model="tipoDictamen" value="rechazar" class="rounded">
                                                    <span class="ml-2 text-red-700 dark:text-red-300 font-medium">Rechazar</span>
                                                </label>
                                            </div>
                                            @error('tipoDictamen') <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label
                                                class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Comentarios</label>
                                            <textarea wire:model="comentarioDictamen"
                                                class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200"
                                                rows="4" placeholder="Justifique su decisión..."></textarea>
                                            @error('comentarioDictamen') <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="flex gap-2">
                                            <button wire:click="emitirDictamen"
                                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium cursor-pointer">
                                                Emitir Dictamen
                                            </button>
                                            <button wire:click="$set('showFormDictamen', false)"
                                                class="px-4 py-2 bg-zinc-400 hover:bg-zinc-500 text-white rounded-lg font-medium cursor-pointer">
                                                Cancelar
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg text-center">
                                <p class="text-zinc-600 dark:text-zinc-400">
                                    @if($actividad->estado === 'APROBADO')
                                        Esta actividad ha sido <span
                                            class="font-semibold text-green-700 dark:text-green-400">aprobada</span>
                                    @elseif($actividad->estado === 'RECHAZADO')
                                        Esta actividad ha sido <span
                                            class="font-semibold text-red-700 dark:text-red-400">rechazada</span>
                                    @else
                                        Esta actividad no está en estado de revisión
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    @include('livewire.Revision.modal-detalle-tarea')

    <!-- Modal de Comentarios -->
    <x-dialog-modal wire:model="showComentarioModal" maxWidth="lg">
        <x-slot name="title">
            Rechazar {{ $tipoComentario === 'TAREA' ? 'Tarea' : 'Indicador' }}
        </x-slot>

        <x-slot name="content">
            <div class="mb-4">
                <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4">
                    Por favor, explique al usuario por qué este
                    {{ $tipoComentario === 'TAREA' ? 'tarea' : 'indicador' }} debe ser corregido.
                </p>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                    Motivo del rechazo <span class="text-red-500">*</span>
                </label>
                <textarea wire:model="textoComentario"
                    class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-200"
                    rows="5" placeholder="Describa qué debe corregir el usuario..."></textarea>
                @error('textoComentario') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" wire:model="requiereCorreccion"
                        class="rounded border-zinc-300 dark:border-zinc-600 text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2 text-sm font-medium text-zinc-700 dark:text-zinc-300">
                        Requiere corrección del usuario
                    </span>
                </label>
                <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                    Si está marcado, el usuario deberá marcar como corregido. Si no, el rechazo es solo informativo.
                </p>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="cerrarComentarioModal">
                Cancelar
            </x-secondary-button>

            <x-button class="ml-3 bg-red-600 hover:bg-red-700" wire:click="enviarComentario">
                Rechazar {{ $tipoComentario === 'TAREA' ? 'Tarea' : 'Indicador' }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Modal de Rechazo de Tarea -->
    <x-dialog-modal wire:model="showRechazarTareaModal" maxWidth="lg">
        <x-slot name="title">
            Rechazar Tarea
        </x-slot>

        <x-slot name="content">
            <div class="mb-4">
                <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4">
                    Por favor, explique al usuario por qué esta tarea debe ser corregida.
                </p>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                    Motivo del rechazo <span class="text-red-500">*</span>
                </label>
                <textarea wire:model="comentarioRechazo"
                    class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-200"
                    rows="5" placeholder="Describa qué debe corregir el usuario..."></textarea>
                @error('comentarioRechazo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" wire:model="requiereCorreccion"
                        class="rounded border-zinc-300 dark:border-zinc-600 text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2 text-sm font-medium text-zinc-700 dark:text-zinc-300">
                        Requiere corrección del usuario
                    </span>
                </label>
                <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                    Si está marcado, el usuario deberá marcar la tarea como corregida. Si no, el rechazo es solo
                    informativo.
                </p>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="cerrarModalRechazo">
                Cancelar
            </x-secondary-button>

            <x-button class="ml-3 bg-red-600 hover:bg-red-700" wire:click="rechazarTarea">
                Rechazar Tarea
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>