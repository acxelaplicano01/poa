<div class="mx-auto rounded-lg mt-8 sm:mt-6 lg:mt-4 mb-6">
    <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow sm:rounded-lg p-4 sm:p-6">
        <!-- Encabezado -->
        <div class="mb-6 pb-4 border-b border-zinc-200 dark:border-zinc-700">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                <div>
                    <a href="#" onclick="window.history.back()" class="inline-flex items-center text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Volver a actividades
                    </a>
                    <h2 class="text-xl font-semibold text-zinc-800 dark:text-zinc-200">
                        Revisión de Actividad: <span class="text-indigo-600 dark:text-indigo-400">{{ $actividad->nombre ?? '-' }}</span>
                    </h2>
                    <div class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                        <p><span class="font-semibold">Tipo:</span> {{ $actividad->tipo->tipo ?? '-' }}</p>
                        <p><span class="font-semibold">Categoría:</span> {{ $actividad->categoria->categoria ?? '-' }}</p>
                        <p><span class="font-semibold">Estado:</span> <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded text-xs font-bold">{{ $actividad->estado ?? '-' }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md" role="alert">
                <p class="font-medium">{{ session('message') }}</p>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md" role="alert">
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Tabs -->
        <div class="mt-6">
            <div class="border-b border-zinc-200 dark:border-zinc-700">
                <nav class="-mb-px flex space-x-2 sm:space-x-8 overflow-x-auto scrollbar-hide pb-px" aria-label="Tabs">
                    <button wire:click="cambiarTab('tareas')"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-xs sm:text-sm transition-colors duration-200 flex-shrink-0 {{ $tab === 'tareas' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 hover:border-zinc-300 dark:text-zinc-400 dark:hover:text-zinc-300' }}">
                        <span class="flex items-center">Tareas</span>
                    </button>
                    <button wire:click="cambiarTab('indicadores')"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-xs sm:text-sm transition-colors duration-200 flex-shrink-0 {{ $tab === 'indicadores' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 hover:border-zinc-300 dark:text-zinc-400 dark:hover:text-zinc-300' }}">
                        <span class="flex items-center">Indicadores</span>
                    </button>
                    <button wire:click="cambiarTab('planificacion')"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-xs sm:text-sm transition-colors duration-200 flex-shrink-0 {{ $tab === 'planificacion' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 hover:border-zinc-300 dark:text-zinc-400 dark:hover:text-zinc-300' }}">
                        <span class="flex items-center">Planificación</span>
                    </button>
                    <button wire:click="cambiarTab('revisiones')"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-xs sm:text-sm transition-colors duration-200 flex-shrink-0 {{ $tab === 'revisiones' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 hover:border-zinc-300 dark:text-zinc-400 dark:hover:text-zinc-300' }}">
                        <span class="flex items-center">Revisiones</span>
                    </button>
                    <button wire:click="cambiarTab('dictamen')"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-xs sm:text-sm transition-colors duration-200 flex-shrink-0 {{ $tab === 'dictamen' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 hover:border-zinc-300 dark:text-zinc-400 dark:hover:text-zinc-300' }}">
                        <span class="flex items-center">Dictamen</span>
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="mt-6">
                @if($tab === 'tareas')
                    <div>
                        <h2 class="text-lg font-semibold mb-2">Tareas</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-zinc-900 rounded-lg shadow">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2">Tarea</th>
                                        <th class="px-4 py-2">Recursos</th>
                                        <th class="px-4 py-2">Estado</th>
                                        <th class="px-4 py-2">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tareas as $tarea)
                                        <tr>
                                            <td class="px-4 py-2">{{ $tarea->nombre }}</td>
                                            <td class="px-4 py-2">
                                                <a href="#" class="bg-blue-600 text-white px-3 py-1 rounded">Recursos</a>
                                            </td>
                                            <td class="px-4 py-2">
                                                <span class="px-2 py-1 rounded text-xs font-bold {{ $tarea->estado === 'APROBADO' ? 'bg-green-100 text-green-800' : ($tarea->estado === 'RECHAZADO' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                    {{ $tarea->estado }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2">
                                                <span class="inline-flex space-x-2">
                                                    <button wire:click="openRevisionModal({{ $tarea->id }}, 'APROBADO')" class="text-green-600 hover:text-green-800" title="Aprobar">
                                                        &#10003;
                                                    </button>
                                                    <button wire:click="openRevisionModal({{ $tarea->id }}, 'RECHAZADO')" class="text-red-600 hover:text-red-800" title="Rechazar">
                                                        &#10007;
                                                    </button>
                                                    <button wire:click="openRevisionModal({{ $tarea->id }}, 'REVISION')" class="text-yellow-600 hover:text-yellow-800" title="Mandar a revisión">
                                                        &#9888;
                                                    </button>
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @elseif($tab === 'indicadores')
                    <div>
                        <h2 class="text-lg font-semibold mb-2">Indicadores</h2>
                        <ul class="list-disc pl-5">
                            @forelse($indicadores as $indicador)
                                <li class="mb-2">{{ $indicador->nombre ?? $indicador->descripcion ?? '-' }}</li>
                            @empty
                                <li>No hay indicadores registrados.</li>
                            @endforelse
                        </ul>
                    </div>
                @elseif($tab === 'planificacion')
                    <div>
                        <h2 class="text-lg font-semibold mb-2">Planificación</h2>
                        <ul class="list-disc pl-5">
                            @forelse($planificaciones as $plan)
                                <li class="mb-2">{{ $plan->nombre ?? $plan->descripcion ?? '-' }}</li>
                            @empty
                                <li>No hay planificación registrada.</li>
                            @endforelse
                        </ul>
                    </div>
                @elseif($tab === 'revisiones')
                    <div>
                        <h2 class="text-lg font-semibold mb-2">Historial de Revisiones</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-zinc-900 rounded-lg shadow">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2">Fecha</th>
                                        <th class="px-4 py-2">Tipo</th>
                                        <th class="px-4 py-2">Revisión</th>
                                        <th class="px-4 py-2">Corregido</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($revisiones as $rev)
                                        <tr>
                                            <td class="px-4 py-2">{{ $rev->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="px-4 py-2">{{ $rev->tipo }}</td>
                                            <td class="px-4 py-2">{{ $rev->revision }}</td>
                                            <td class="px-4 py-2">{{ $rev->corregido ? 'Sí' : 'No' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No hay revisiones registradas.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @elseif($tab === 'dictamen')
                    <div>
                        <h2 class="text-lg font-semibold mb-2">Dictamen</h2>
                        <div class="mb-4">
                            <span class="font-bold">Estado actual:</span>
                            <span class="bg-green-200 text-green-800 px-2 py-1 rounded">{{ $actividad->estado ?? '-' }}</span>
                        </div>
                        <button class="bg-yellow-500 text-white px-4 py-2 rounded">Volver a Reformular</button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Modal de revisión de tarea -->
        @if($showRevisionModal)
            <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-40">
                <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-lg p-6 w-full max-w-md">
                    <h3 class="text-lg font-semibold mb-4">Comentario de revisión</h3>
                    <textarea wire:model.defer="revisionComentario" rows="4" class="w-full border border-zinc-300 dark:border-zinc-700 rounded p-2 mb-4"></textarea>
                    <div class="flex justify-end space-x-2">
                        <button wire:click="closeRevisionModal" class="px-4 py-2 bg-zinc-200 dark:bg-zinc-700 rounded">Cancelar</button>
                        <button wire:click="guardarRevisionTarea" class="px-4 py-2 bg-indigo-600 text-white rounded">Guardar</button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>