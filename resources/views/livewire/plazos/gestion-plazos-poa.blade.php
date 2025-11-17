<div>
    <div class="mx-auto rounded-lg mt-8 sm:mt-6 lg:mt-4 mb-6">
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow sm:rounded-lg p-4 sm:p-6">
            
            <!-- Encabezado -->
            <div class="mb-6 pb-4 border-b border-zinc-200 dark:border-zinc-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-zinc-800 dark:text-zinc-200">
                        Gestión de Plazos: {{ $poa->name }}
                    </h2>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-2">
                        Año {{ $poa->anio }} - {{ $poa->institucion->nombre }}
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <button wire:click="volver" 
                            class="inline-flex items-center px-4 py-2 bg-zinc-600 hover:bg-zinc-700 text-white font-medium rounded-lg transition-colors duration-150">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Volver
                    </button>
                    <button wire:click="crear" 
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-150">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Plazo Personalizado
                    </button>
                </div>
            </div>

            <!-- Mensajes -->
            @if (session()->has('success'))
                <div class="mb-4 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Sección de Plazos Estándar -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-zinc-800 dark:text-zinc-200 mb-4">Plazos Estándar</h3>
                
                <!-- Tabla Desktop -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    Tipo de Plazo
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    Fecha Inicio
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    Fecha Fin
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    Activo
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                            @foreach($plazosEstandar as $tipo => $plazo)
                                @php
                                    $now = \Carbon\Carbon::now();
                                    // Un plazo está vencido si ya pasó su fecha fin, independientemente del toggle
                                    $esVencido = false;
                                    if ($plazo['existe'] && !empty($plazo['fecha_fin'])) {
                                        $fin = \Carbon\Carbon::parse($plazo['fecha_fin']);
                                        $esVencido = $now->gt($fin);
                                    }
                                @endphp
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $tipo === 'asignacion_nacional' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                                {{ $tipo === 'planificacion' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : '' }}
                                                {{ $tipo === 'asignacion_departamental' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                                {{ $tipo === 'seguimiento' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                                {{ $tipo === 'requerimientos' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}">
                                                {{ $plazo['label'] }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="date" 
                                               wire:model="plazosEstandar.{{ $tipo }}.fecha_inicio"
                                               value="{{ $plazo['fecha_inicio'] }}"
                                               {{ $esVencido ? 'disabled' : '' }}
                                               class="rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm {{ $esVencido ? 'opacity-50 cursor-not-allowed' : '' }}">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="date" 
                                               wire:model="plazosEstandar.{{ $tipo }}.fecha_fin"
                                               value="{{ $plazo['fecha_fin'] }}"
                                               {{ $esVencido ? 'disabled' : '' }}
                                               class="rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm {{ $esVencido ? 'opacity-50 cursor-not-allowed' : '' }}">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($plazo['existe'] && !empty($plazo['fecha_inicio']) && !empty($plazo['fecha_fin']))
                                            @php
                                                $inicio = \Carbon\Carbon::parse($plazo['fecha_inicio']);
                                                $fin = \Carbon\Carbon::parse($plazo['fecha_fin']);
                                            @endphp
                                            @if ($esVencido)
                                                {{-- Si está vencido, mostrar siempre como Vencido --}}
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                                    Vencido
                                                </span>
                                            @elseif ($plazo['activo'])
                                                @if ($now->lt($inicio))
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                                        Próximo
                                                    </span>
                                                @elseif ($now->between($inicio, $fin))
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                                        Vigente
                                                    </span>
                                                @endif
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-300">
                                                    Inactivo
                                                </span>
                                            @endif
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-300">
                                                Sin configurar
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($esVencido)
                                            <div class="flex flex-col items-start">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                                    Desactivado por vencimiento
                                                </span>
                                            </div>
                                        @else
                                            <button wire:click="toggleActivo('{{ $tipo }}')" 
                                                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $plazo['activo'] ? 'bg-indigo-600' : 'bg-zinc-200 dark:bg-zinc-700' }}">
                                                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $plazo['activo'] ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                            </button>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @if(!$esVencido)
                                            <button wire:click="guardarPlazoEstandar('{{ $tipo }}')" 
                                                    class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded transition-colors duration-150">
                                                Guardar
                                            </button>
                                        @else
                                            <span class="text-xs text-zinc-400 dark:text-zinc-500">No editable</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Cards para móvil -->
                <div class="md:hidden space-y-4">
                    @foreach($plazosEstandar as $tipo => $plazo)
                        @php
                            $now = \Carbon\Carbon::now();
                            // Un plazo está vencido si ya pasó su fecha fin, independientemente del toggle
                            $esVencido = false;
                            if ($plazo['existe'] && !empty($plazo['fecha_fin'])) {
                                $fin = \Carbon\Carbon::parse($plazo['fecha_fin']);
                                $esVencido = $now->gt($fin);
                            }
                        @endphp
                        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">
                            <div class="flex justify-between items-start mb-3">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $tipo === 'asignacion_nacional' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                    {{ $tipo === 'planificacion' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : '' }}
                                    {{ $tipo === 'asignacion_departamental' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                    {{ $tipo === 'seguimiento' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                    {{ $tipo === 'requerimientos' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}">
                                    {{ $plazo['label'] }}
                                </span>
                                @if($esVencido)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                        Vencido
                                    </span>
                                @else
                                    <button wire:click="toggleActivo('{{ $tipo }}')" 
                                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $plazo['activo'] ? 'bg-indigo-600' : 'bg-zinc-200 dark:bg-zinc-700' }}">
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $plazo['activo'] ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                    </button>
                                @endif
                            </div>
                            
                            <!-- Estado del plazo -->
                            <div class="mb-3">
                                @if($plazo['existe'] && !empty($plazo['fecha_inicio']) && !empty($plazo['fecha_fin']))
                                    @php
                                        $inicio = \Carbon\Carbon::parse($plazo['fecha_inicio']);
                                        $fin = \Carbon\Carbon::parse($plazo['fecha_fin']);
                                    @endphp
                                    @if ($esVencido)
                                        {{-- Si está vencido, mostrar siempre como Vencido --}}
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">Vencido</span>
                                    @elseif ($plazo['activo'])
                                        @if ($now->lt($inicio))
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">Próximo</span>
                                        @elseif ($now->between($inicio, $fin))
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">Vigente</span>
                                        @endif
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-300">Inactivo</span>
                                    @endif
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-300">Sin configurar</span>
                                @endif
                            </div>
                            
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-1">Fecha Inicio</label>
                                    <input type="date" 
                                           wire:model="plazosEstandar.{{ $tipo }}.fecha_inicio"
                                           value="{{ $plazo['fecha_inicio'] }}"
                                           {{ $esVencido ? 'disabled' : '' }}
                                           class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm {{ $esVencido ? 'opacity-50 cursor-not-allowed' : '' }}">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-1">Fecha Fin</label>
                                    <input type="date" 
                                           wire:model="plazosEstandar.{{ $tipo }}.fecha_fin"
                                           value="{{ $plazo['fecha_fin'] }}"
                                           {{ $esVencido ? 'disabled' : '' }}
                                           class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm {{ $esVencido ? 'opacity-50 cursor-not-allowed' : '' }}">
                                </div>
                                
                                @if(!$esVencido)
                                    <div class="pt-2">
                                        <button wire:click="guardarPlazoEstandar('{{ $tipo }}')" 
                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                                            Guardar
                                        </button>
                                    </div>
                                @else
                                    <div class="pt-3 mt-3 border-t border-zinc-200 dark:border-zinc-700">
                                        <div class="flex items-center justify-center space-x-2 text-red-600 dark:text-red-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="text-xs font-medium">Desactivado por vencimiento de fechas</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Sección de Plazos Personalizados -->
            @if($plazosPersonalizados->count() > 0)
                <div>
                    <h3 class="text-lg font-semibold text-zinc-800 dark:text-zinc-200 mb-4">Plazos Personalizados</h3>
                    
                    <!-- Tabla Desktop -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                            <thead class="bg-zinc-50 dark:bg-zinc-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                        Nombre
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                        Tipo
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                        Período
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                                @foreach($plazosPersonalizados as $plazo)
                                    @php
                                        $now = \Carbon\Carbon::now();
                                        $inicio = \Carbon\Carbon::parse($plazo->fecha_inicio);
                                        $fin = \Carbon\Carbon::parse($plazo->fecha_fin);
                                        $esVencido = $now->gt($fin);
                                    @endphp
                                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $plazo->nombre_plazo }}</div>
                                            @if($plazo->descripcion)
                                                <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">{{ Str::limit($plazo->descripcion, 60) }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $plazo->tipo_plazo === 'asignacion_nacional' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                                {{ $plazo->tipo_plazo === 'planificacion' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : '' }}
                                                {{ $plazo->tipo_plazo === 'asignacion_departamental' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                                {{ $plazo->tipo_plazo === 'seguimiento' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                                {{ $plazo->tipo_plazo === 'requerimientos' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}">
                                                @php
                                                    $tiposLabels = [
                                                        'asignacion_nacional' => 'Asignación Nacional',
                                                        'asignacion_departamental' => 'Asignación Departamental',
                                                        'planificacion' => 'Planificación',
                                                        'requerimientos' => 'Requerimientos',
                                                        'seguimiento' => 'Seguimiento'
                                                    ];
                                                @endphp
                                                {{ $tiposLabels[$plazo->tipo_plazo] ?? $plazo->tipo_plazo }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                            {{ \Carbon\Carbon::parse($plazo->fecha_inicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($plazo->fecha_fin)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($esVencido)
                                                {{-- Si está vencido, mostrar siempre como Vencido --}}
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                                    Vencido
                                                </span>
                                            @elseif ($plazo->activo)
                                                @if ($now->lt($inicio))
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                                        Próximo
                                                    </span>
                                                @elseif ($now->between($inicio, $fin))
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                                        Vigente
                                                    </span>
                                                @endif
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-300">
                                                    Inactivo
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if($esVencido)
                                                <span class="px-3 py-1.5 text-xs font-medium rounded-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                                    Desactivado por vencimiento
                                                </span>
                                            @else
                                                <button wire:click="editar({{ $plazo->id }})" 
                                                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-3">
                                                    Editar
                                                </button>
                                                <button wire:click="confirmDelete({{ $plazo->id }})" 
                                                        class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                                    Eliminar
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Cards para móvil -->
                    <div class="md:hidden space-y-4">
                        @foreach($plazosPersonalizados as $plazo)
                            @php
                                $now = \Carbon\Carbon::now();
                                $inicio = \Carbon\Carbon::parse($plazo->fecha_inicio);
                                $fin = \Carbon\Carbon::parse($plazo->fecha_fin);
                                $esVencido = $now->gt($fin);
                            @endphp
                            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $plazo->nombre_plazo }}</h3>
                                        @if($plazo->descripcion)
                                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">{{ Str::limit($plazo->descripcion, 60) }}</p>
                                        @endif
                                        {{-- Tipo de plazo en móvil --}}
                                        <div class="mt-2">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                {{ $plazo->tipo_plazo === 'asignacion_nacional' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                                {{ $plazo->tipo_plazo === 'planificacion' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : '' }}
                                                {{ $plazo->tipo_plazo === 'asignacion_departamental' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                                {{ $plazo->tipo_plazo === 'seguimiento' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                                {{ $plazo->tipo_plazo === 'requerimientos' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}">
                                                @php
                                                    $tiposLabels = [
                                                        'asignacion_nacional' => 'Asignación Nacional',
                                                        'asignacion_departamental' => 'Asignación Departamental',
                                                        'planificacion' => 'Planificación',
                                                        'requerimientos' => 'Requerimientos',
                                                        'seguimiento' => 'Seguimiento'
                                                    ];
                                                @endphp
                                                {{ $tiposLabels[$plazo->tipo_plazo] ?? $plazo->tipo_plazo }}
                                            </span>
                                        </div>
                                    </div>
                                    @if ($esVencido)
                                        {{-- Si está vencido, mostrar siempre como Vencido --}}
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">Vencido</span>
                                    @elseif ($plazo->activo)
                                        @if ($now->lt($inicio))
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">Próximo</span>
                                        @elseif ($now->between($inicio, $fin))
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">Vigente</span>
                                        @endif
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-300">Inactivo</span>
                                    @endif
                                </div>
                                
                                <div class="text-sm text-zinc-600 dark:text-zinc-400 mb-3">
                                    <p>{{ \Carbon\Carbon::parse($plazo->fecha_inicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($plazo->fecha_fin)->format('d/m/Y') }}</p>
                                </div>

                                @if($esVencido)
                                    <div class="pt-3 border-t border-zinc-200 dark:border-zinc-700">
                                        <div class="flex items-center justify-center space-x-2 text-red-600 dark:text-red-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="text-xs font-medium">Desactivado por vencimiento de fechas</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="pt-3 border-t border-zinc-200 dark:border-zinc-700 flex space-x-4">
                                        <button wire:click="editar({{ $plazo->id }})" 
                                                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 text-sm font-medium">
                                            Editar
                                        </button>
                                        <button wire:click="confirmDelete({{ $plazo->id }})" 
                                                class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 text-sm font-medium">
                                            Eliminar
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

    <!-- Modal Crear/Editar Plazo Personalizado -->
    <x-dialog-modal wire:model="modalOpen" maxWidth="2xl">
        <x-slot name="title">
            {{ $isEditing ? 'Editar Plazo Personalizado' : 'Nuevo Plazo Personalizado' }}
        </x-slot>

        <x-slot name="content">
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">
                Configure un plazo personalizado adicional para este POA
            </p>

            <form class="space-y-4">
                <!-- Tipo de Plazo -->
                <div>
                    <label for="tipo_plazo" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        Tipo de Plazo <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="tipo_plazo" id="tipo_plazo"
                            class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Seleccione un tipo</option>
                        @foreach($tiposPlazosEstandar as $valor => $etiqueta)
                            <option value="{{ $valor }}">{{ $etiqueta }}</option>
                        @endforeach
                    </select>
                    @error('tipo_plazo') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Nombre -->
                <div>
                    <label for="nombre_plazo" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        Nombre Personalizado del Plazo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="nombre_plazo" id="nombre_plazo"
                           class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="Ej: Extensión de Planificación, Plazo Adicional de Seguimiento, etc.">
                    <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                        El nombre personalizado permite crear plazos adicionales del mismo tipo. Ej: "Extensión de Planificación".
                    </p>
                    @error('nombre_plazo') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Fechas -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="fecha_inicio_form" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                            Fecha de Inicio <span class="text-red-500">*</span>
                        </label>
                        <input type="date" wire:model="fecha_inicio_form" id="fecha_inicio_form"
                               class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('fecha_inicio_form') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="fecha_fin_form" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                            Fecha de Fin <span class="text-red-500">*</span>
                        </label>
                        <input type="date" wire:model="fecha_fin_form" id="fecha_fin_form"
                               class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('fecha_fin_form') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Descripción -->
                <div>
                    <label for="descripcion" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        Descripción (Opcional)
                    </label>
                    <textarea wire:model="descripcion" id="descripcion" rows="3"
                              class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                              placeholder="Detalles adicionales sobre este plazo..."></textarea>
                    @error('descripcion') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Estado Activo -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox" wire:model="activo_form" id="activo_form"
                               class="h-4 w-4 rounded border-zinc-300 text-indigo-600 focus:ring-indigo-500 dark:border-zinc-600 dark:bg-zinc-700">
                    </div>
                    <div class="ml-3">
                        <label for="activo_form" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            Activar este plazo
                        </label>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">
                            Los plazos personalizados pueden coexistir activos junto con los plazos estándar.
                        </p>
                    </div>
                </div>
            </form>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('modalOpen', false)">
                Cancelar
            </x-secondary-button>

            <x-button class="ml-3" wire:click="guardar">
                {{ $isEditing ? 'Actualizar' : 'Crear' }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Modal Confirmar Eliminación -->
    <x-confirmation-modal wire:model="modalDelete">
        <x-slot name="title">
            Eliminar Plazo Personalizado
        </x-slot>

        <x-slot name="content">
            ¿Estás seguro de que deseas eliminar este plazo personalizado? Esta acción no se puede deshacer.
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('modalDelete', false)">
                Cancelar
            </x-secondary-button>

            <x-danger-button class="ml-3" wire:click="eliminar">
                Eliminar
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>
