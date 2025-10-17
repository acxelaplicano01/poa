<div
    class="mx-auto rounded-lg mt-8 sm:mt-6 lg:mt-4 mb-66 bg-zinc-50 dark:bg-zinc-900 min-h-screen transition-colors duration-200">
    <!-- Header -->
    <div class="mb-6 p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="min-w-0 flex-1">
                <h1 class="text-xl sm:text-2xl font-bold text-zinc-900 dark:text-white truncate"
                    title="Detalle de Estructura: {{ $detalleEstructura['estructura'] }}">
                    Detalle de Estructura: {{ $detalleEstructura['estructura'] }}
                </h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-300 mt-1 truncate"
                    title="{{ $poa->anio }} - {{ $unidadEjecutora->name }}">
                    {{ $poa->anio }} - {{ $unidadEjecutora->name }}
                </p>
            </div>
            <button wire:click="volver"
                class="inline-flex items-center px-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-md shadow-sm text-sm font-medium text-zinc-700 dark:text-zinc-200 bg-white dark:bg-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-zinc-500 dark:focus:ring-offset-zinc-800 transition-all duration-200 self-start sm:self-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </button>
        </div>
    </div>

    <!-- Métricas Generales -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-8 px-4 sm:px-6">
        <div
            class="bg-white dark:bg-zinc-800 overflow-hidden shadow-lg dark:shadow-zinc-900/50 rounded-lg border border-zinc-200 dark:border-zinc-700 hover:shadow-xl dark:hover:shadow-zinc-900/70 transition-all duration-200">
            <div class="p-3 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-6 h-6 sm:w-8 sm:h-8 bg-indigo-100 dark:bg-indigo-900/40 rounded-md flex items-center justify-center ring-1 ring-indigo-200 dark:ring-indigo-800">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-indigo-600 dark:text-indigo-300" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-2 sm:ml-4 min-w-0 flex-1">
                        <dt class="text-xs sm:text-sm font-medium text-zinc-500 dark:text-zinc-300 truncate">
                            Departamentos</dt>
                        <dd class="text-sm sm:text-lg font-semibold text-zinc-900 dark:text-white">
                            {{ $detalleEstructura['cantidad_departamentos'] }}</dd>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="bg-white dark:bg-zinc-800 overflow-hidden shadow-lg dark:shadow-zinc-900/50 rounded-lg border border-zinc-200 dark:border-zinc-700 hover:shadow-xl dark:hover:shadow-zinc-900/70 transition-all duration-200">
            <div class="p-3 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-6 h-6 sm:w-8 sm:h-8 bg-green-100 dark:bg-green-900/40 rounded-md flex items-center justify-center ring-1 ring-green-200 dark:ring-green-800">
                            <p class="text-green-600 dark:text-green-300">L</p>
                        </div>
                    </div>
                    <div class="ml-2 sm:ml-4 min-w-0 flex-1">
                        <dt class="text-xs sm:text-sm font-medium text-zinc-500 dark:text-zinc-300 truncate">Monto Total
                        </dt>
                        <dd class="text-sm sm:text-lg font-semibold text-zinc-900 dark:text-white truncate"
                            title="{{ number_format($detalleEstructura['monto_total_asignado'], 2) }}">
                            <span
                                class="hidden sm:inline"></span>{{ number_format($detalleEstructura['monto_total_asignado'], 0) }}
                        </dd>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="bg-white dark:bg-zinc-800 overflow-hidden shadow-lg dark:shadow-zinc-900/50 rounded-lg border border-zinc-200 dark:border-zinc-700 hover:shadow-xl dark:hover:shadow-zinc-900/70 transition-all duration-200">
            <div class="p-3 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-6 h-6 sm:w-8 sm:h-8 bg-yellow-100 dark:bg-yellow-900/40 rounded-md flex items-center justify-center ring-1 ring-yellow-200 dark:ring-yellow-800">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-600 dark:text-yellow-300" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-2 sm:ml-4 min-w-0 flex-1">
                        <dt class="text-xs sm:text-sm font-medium text-zinc-500 dark:text-zinc-300 truncate">Promedio
                        </dt>
                        <dd class="text-sm sm:text-lg font-semibold text-zinc-900 dark:text-white truncate"
                            title="{{ number_format($detalleEstructura['promedio_por_departamento'], 2) }}">
                            <span
                                class="hidden sm:inline"></span>{{ number_format($detalleEstructura['promedio_por_departamento'], 0) }}
                        </dd>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="bg-white dark:bg-zinc-800 overflow-hidden shadow-lg dark:shadow-zinc-900/50 rounded-lg border border-zinc-200 dark:border-zinc-700 hover:shadow-xl dark:hover:shadow-zinc-900/70 transition-all duration-200">
            <div class="p-3 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-6 h-6 sm:w-8 sm:h-8 bg-purple-100 dark:bg-purple-900/40 rounded-md flex items-center justify-center ring-1 ring-purple-200 dark:ring-purple-800">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600 dark:text-purple-300" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-2 sm:ml-4 min-w-0 flex-1">
                        <dt class="text-xs sm:text-sm font-medium text-zinc-500 dark:text-zinc-300 truncate">Fuentes
                        </dt>
                        <dd class="text-sm sm:text-lg font-semibold text-zinc-900 dark:text-white">
                            {{ $detalleEstructura['fuentes_utilizadas']->count() }}</dd>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="mb-6 px-4 sm:px-6">
        <div class="border-b border-zinc-200 dark:border-zinc-600">
            <nav class="-mb-px flex space-x-8">
                <button wire:click="$set('activeTab', 'departamentos')"
                    class="py-3 px-1 border-b-2 font-medium text-sm transition-all duration-200 {{ ($activeTab ?? 'departamentos') === 'departamentos' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-300 dark:border-indigo-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 hover:border-zinc-300 dark:text-zinc-400 dark:hover:text-zinc-200 dark:hover:border-zinc-500' }}">
                    Departamentos
                </button>
                <button wire:click="$set('activeTab', 'fuentes')"
                    class="py-3 px-1 border-b-2 font-medium text-sm transition-all duration-200 {{ ($activeTab ?? 'departamentos') === 'fuentes' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-300 dark:border-indigo-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 hover:border-zinc-300 dark:text-zinc-400 dark:hover:text-zinc-200 dark:hover:border-zinc-500' }}">
                    Fuentes de Financiamiento
                </button>
            </nav>
        </div>
    </div>

    <!-- Contenido de los Tabs -->
    <div class="px-4 sm:px-6">
        @if(($activeTab ?? 'departamentos') === 'departamentos')
            <!-- Tab Departamentos -->
            <div
                class="bg-white dark:bg-zinc-800 shadow-lg dark:shadow-zinc-900/50 rounded-lg border border-zinc-200 dark:border-zinc-700">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-zinc-900 dark:text-white mb-4">Departamentos de la Estructura</h3>

                    <!-- Vista Desktop/Tablet -->
                    <div class="hidden md:block">
                        <div class="overflow-x-auto -mx-4 sm:mx-0 rounded-lg">
                            <div class="inline-block min-w-full align-middle">
                                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                                    <thead class="bg-zinc-50 dark:bg-zinc-900">
                                        <tr>
                                            <th scope="col"
                                                class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider w-1/4">
                                                Departamento
                                            </th>
                                            <th scope="col"
                                                class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider w-20">
                                                Tipo
                                            </th>
                                            <th scope="col"
                                                class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider w-24">
                                                Monto Asignado
                                            </th>
                                            <th scope="col"
                                                class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider w-24">
                                                Asignaciones
                                            </th>
                                            <th scope="col"
                                                class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                                Fuentes
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                                        @foreach($detalleEstructura['departamentos'] as $departamento)
                                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-all duration-200">
                                                <td class="px-3 sm:px-6 py-4">
                                                    <div class="flex items-center">
                                                        <div class="min-w-0 flex-1">
                                                            <div class="text-sm font-medium text-zinc-900 dark:text-white truncate"
                                                                title="{{ $departamento['nombre'] }}">
                                                                {{ $departamento['nombre'] }}
                                                            </div>
                                                            <div class="text-sm text-zinc-500 dark:text-zinc-300 truncate">
                                                                {{ $departamento['siglas'] }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                                                    <span
                                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                              {{ $departamento['tipo'] === 'ADMINISTRATIVO' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' :
                                                                ($departamento['tipo'] === 'COORDINACIÓN REGIONAL' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                                                ($departamento['tipo'] === 'SECCIÓN ACADÉMICA' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' :
                                                                ($departamento['tipo'] === 'DEPARTAMENTO ACADÉMICO' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                                                ($departamento['tipo'] === 'COORDINACIÓN ACADÉMICA' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' :
                                                                'bg-zinc-100 text-zinc-800 dark:bg-zinc-900 dark:text-zinc-200')))) }}">
                                                        {{ ucfirst($departamento['tipo'] ?? 'N/A') }}
                                                    </span>
                                                </td>
                                                <td
                                                    class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-white">
                                                    <div class="truncate">
                                                        {{ number_format($departamento['monto_asignado'], 2) }}</div>
                                                </td>
                                                <td
                                                    class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-300">
                                                    <div class="text-center">{{ $departamento['cantidad_asignaciones'] }}</div>
                                                    <div class="text-xs text-zinc-400 dark:text-zinc-400">asignación(es)</div>
                                                </td>
                                                <td class="px-3 sm:px-6 py-4">
                                                    <div class="flex flex-wrap gap-1 max-w-xs">
                                                        @foreach($departamento['fuentes'] as $fuente)
                                                            <span
                                                                class="inline-flex px-2 py-1 text-xs font-medium rounded-md bg-zinc-100 text-zinc-800 dark:bg-zinc-700 dark:text-zinc-200 truncate max-w-full ring-1 ring-zinc-200 dark:ring-zinc-600"
                                                                title="{{ $fuente['nombre'] }}: {{ number_format($fuente['monto'], 2) }}">
                                                                {{ Str::limit($fuente['nombre'], 15) }}:
                                                                {{ number_format($fuente['monto'], 0) }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Vista Mobile -->
                    <div class="md:hidden space-y-4">
                        @foreach($detalleEstructura['departamentos'] as $departamento)
                            <div
                                class="bg-zinc-50 dark:bg-zinc-800/50 rounded-lg p-4 border border-zinc-200 dark:border-zinc-600 shadow-sm dark:shadow-zinc-900/20">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-sm font-medium text-zinc-900 dark:text-white truncate"
                                            title="{{ $departamento['nombre'] }}">
                                            {{ $departamento['nombre'] }}
                                        </h4>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-300">{{ $departamento['siglas'] }}</p>
                                    </div>
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ml-2
                                            {{ $departamento['tipo'] === 'operativo' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200' : 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200' }}">
                                        {{ ucfirst($departamento['tipo'] ?? 'N/A') }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <dt class="text-zinc-500 dark:text-zinc-300">Monto Asignado</dt>
                                        <dd class="font-medium text-zinc-900 dark:text-white">
                                            {{ number_format($departamento['monto_asignado'], 2) }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-zinc-500 dark:text-zinc-300">Asignaciones</dt>
                                        <dd class="font-medium text-zinc-900 dark:text-white">
                                            {{ $departamento['cantidad_asignaciones'] }}</dd>
                                    </div>
                                </div>

                                @if($departamento['fuentes']->isNotEmpty())
                                    <div class="mt-3">
                                        <dt class="text-xs text-zinc-500 dark:text-zinc-300 mb-2">Fuentes:</dt>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($departamento['fuentes'] as $fuente)
                                                <span
                                                    class="inline-flex px-2 py-1 text-xs font-medium rounded-md bg-zinc-100 text-zinc-800 dark:bg-zinc-700 dark:text-zinc-200 ring-1 ring-zinc-200 dark:ring-zinc-600"
                                                    title="{{ $fuente['nombre'] }}: {{ number_format($fuente['monto'], 2) }}">
                                                    {{ Str::limit($fuente['nombre'], 12) }}: {{ number_format($fuente['monto'], 0) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <!-- Tab Fuentes -->
            <div
                class="bg-white dark:bg-zinc-800 shadow-lg dark:shadow-zinc-900/50 rounded-lg border border-zinc-200 dark:border-zinc-700">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-zinc-900 dark:text-white mb-4">Fuentes de Financiamiento</h3>

                    <!-- Resumen de fuentes en móviles -->
                    <div
                        class="sm:hidden mb-4 p-3 bg-zinc-50 dark:bg-zinc-800/50 rounded-lg border border-zinc-200 dark:border-zinc-600">
                        <div class="text-sm text-zinc-600 dark:text-zinc-300">
                            Total: {{ $detalleEstructura['fuentes_utilizadas']->count() }} fuente(s) de financiamiento
                        </div>
                    </div>

                    <div class="space-y-4 sm:space-y-6">
                        @foreach($detalleEstructura['fuentes_utilizadas'] as $fuente)
                            <div
                                class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-3 sm:p-4 bg-zinc-50 dark:bg-zinc-800/50 shadow-sm dark:shadow-zinc-900/20">
                                <!-- Header de la fuente -->
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3 gap-2">
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-base sm:text-lg font-medium text-zinc-900 dark:text-white truncate"
                                            title="{{ $fuente['nombre'] }}">
                                            {{ $fuente['nombre'] }}
                                        </h4>
                                    </div>
                                    <div class="text-left sm:text-right flex-shrink-0">
                                        <div class="text-lg sm:text-xl font-semibold text-zinc-900 dark:text-white">
                                            {{ number_format($fuente['monto'], 2) }}</div>
                                        <div class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-300">
                                            {{ $fuente['cantidad_asignaciones'] }} asignación(es)</div>
                                    </div>
                                </div>

                                <!-- Vista Desktop/Tablet -->
                                <div class="hidden sm:block">
                                    <div class="overflow-x-auto -mx-4 sm:mx-0 rounded-lg">
                                        <div class="inline-block min-w-full align-middle">
                                            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                                                <thead class="bg-zinc-100 dark:bg-zinc-900">
                                                    <tr>
                                                        <th scope="col"
                                                            class="px-3 sm:px-4 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                                            Departamento
                                                        </th>
                                                        <th scope="col"
                                                            class="px-3 sm:px-4 py-2 text-right text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                                            Monto
                                                        </th>
                                                        <th scope="col"
                                                            class="px-3 sm:px-4 py-2 text-center text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                                            Fecha
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody
                                                    class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                                                    @foreach($fuente['asignaciones'] as $asignacion)
                                                        <tr
                                                            class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-all duration-200">
                                                            <td class="px-3 sm:px-4 py-2">
                                                                <div class="min-w-0">
                                                                    <div class="text-sm text-zinc-900 dark:text-white truncate"
                                                                        title="{{ $asignacion['departamento'] }} ({{ $asignacion['siglas'] }})">
                                                                        {{ $asignacion['departamento'] }}
                                                                    </div>
                                                                    <div class="text-xs text-zinc-500 dark:text-zinc-300">
                                                                        ({{ $asignacion['siglas'] }})
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="px-3 sm:px-4 py-2 text-right">
                                                                <div
                                                                    class="text-sm font-medium text-zinc-900 dark:text-white whitespace-nowrap">
                                                                    {{ number_format($asignacion['monto'], 2) }}
                                                                </div>
                                                            </td>
                                                            <td class="px-3 sm:px-4 py-2 text-center">
                                                                <div
                                                                    class="text-sm text-zinc-500 dark:text-zinc-300 whitespace-nowrap">
                                                                    {{ $asignacion['fecha_asignacion']->format('d/m/Y') }}
                                                                </div>
                                                                <div class="text-xs text-zinc-400 dark:text-zinc-400">
                                                                    {{ $asignacion['fecha_asignacion']->format('H:i') }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Vista Mobile -->
                                <div class="sm:hidden space-y-2">
                                    @foreach($fuente['asignaciones'] as $asignacion)
                                        <div
                                            class="bg-white dark:bg-zinc-800 rounded-lg p-3 border border-zinc-200 dark:border-zinc-600 shadow-sm dark:shadow-zinc-900/20">
                                            <div class="flex justify-between items-start gap-3">
                                                <div class="min-w-0 flex-1">
                                                    <h5 class="text-sm font-medium text-zinc-900 dark:text-white truncate"
                                                        title="{{ $asignacion['departamento'] }}">
                                                        {{ $asignacion['departamento'] }}
                                                    </h5>
                                                    <p class="text-xs text-zinc-500 dark:text-zinc-300 mt-1">
                                                        ({{ $asignacion['siglas'] }})</p>
                                                </div>
                                                <div class="text-right flex-shrink-0">
                                                    <div
                                                        class="text-sm font-semibold text-zinc-900 dark:text-white whitespace-nowrap">
                                                        {{ number_format($asignacion['monto'], 2) }}
                                                    </div>
                                                    <div class="text-xs text-zinc-500 dark:text-zinc-300 mt-1">
                                                        {{ $asignacion['fecha_asignacion']->format('d/m/Y H:i') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>    
</div>