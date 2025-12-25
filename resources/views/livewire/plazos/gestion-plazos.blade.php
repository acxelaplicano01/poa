<div>
    <div class="mx-auto rounded-lg mt-8 sm:mt-6 lg:mt-4 mb-6">
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow sm:rounded-lg p-4 sm:p-6">
            
            <!-- Encabezado -->
            <div class="mb-6 pb-4 border-b border-zinc-200 dark:border-zinc-700 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-zinc-800 dark:text-zinc-200">
                        Gestión de Plazos POA
                    </h2>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-2">
                        Configura los períodos de tiempo para cada fase del POA
                    </p>
                </div>
                <div class="space-x-2">
                    <button wire:click="volver" 
                            class="inline-flex items-center px-4 py-2 bg-zinc-200 dark:bg-zinc-700 hover:bg-zinc-300 dark:hover:bg-zinc-600 text-zinc-800 dark:text-zinc-200 font-medium rounded-lg transition-colors duration-150">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Volver
                    </button>
                    @can('consola.plazos.crear')
                <button wire:click="crear" 
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-150">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nuevo Plazo
                </button>
                @endcan
            </div>

            <!-- Mensajes -->
            @if (session()->has('message'))
                @include('rk.default.notifications.notification-alert', [
                    'type' => 'success',
                    'dismissible' => true,
                    'icon' => true,
                    'duration' => 5,
                    'slot' => session('message')
                ])
            @endif

            @if (session()->has('error'))
                @include('rk.default.notifications.notification-alert', [
                    'type' => 'error',
                    'dismissible' => true,
                    'icon' => true,
                    'duration' => 8,
                    'slot' => session('error')
                ])
            @endif

            <!-- Filtros -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">POA</label>
                    <select wire:model.live="filtroPoa" 
                            class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Todos los POAs</option>
                        @foreach($poas as $poa)
                            <option value="{{ $poa->id }}">{{ $poa->anio }} - {{ $poa->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Tipo de Plazo</label>
                    <select wire:model.live="filtroTipo" 
                            class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Todos los tipos</option>
                        @foreach($tiposPlazos as $tipo)
                            <option value="{{ $tipo['value'] }}">{{ $tipo['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Estado</label>
                    <select wire:model.live="filtroEstado" 
                            class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Todos los estados</option>
                        <option value="vigente">Vigente</option>
                        <option value="proximo">Próximo</option>
                        <option value="vencido">Vencido</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
            </div>

            <!-- Tabla de plazos (Desktop) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">POA</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Período</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Activo</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                        @forelse($plazos as $plazo)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($plazo->poa)
                                        <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $plazo->poa->anio }}</div>
                                        <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $plazo->poa->name }}</div>
                                    @else
                                        <div class="text-sm font-medium text-red-600 dark:text-red-400">POA no disponible</div>
                                        <div class="text-xs text-zinc-500 dark:text-zinc-400">Sin POA asociado</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <span class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $plazo->tipo_plazo_label }}</span>
                                        @if($plazo->nombre_plazo)
                                            <span class="ml-2 px-2 py-0.5 text-xs font-medium rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300">
                                                Personalizado
                                            </span>
                                        @endif
                                    </div>
                                    @if(!$plazo->nombre_plazo)
                                        <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">
                                            Plazo estándar
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-zinc-900 dark:text-zinc-100">
                                        {{ $plazo->fecha_inicio->format('d/m/Y') }} - {{ $plazo->fecha_fin->format('d/m/Y') }}
                                    </div>
                                    @if($plazo->estaVigente())
                                        <div class="text-xs text-indigo-600 dark:text-indigo-400">
                                            {{ $plazo->diasRestantes() }} días restantes
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($plazo->estado === 'vigente')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                            Vigente
                                        </span>
                                    @elseif($plazo->estado === 'proximo')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                            Próximo
                                        </span>
                                    @elseif($plazo->estado === 'vencido')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                            Vencido
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-300">
                                            Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button wire:click="toggleActivo({{ $plazo->id }})" 
                                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $plazo->activo ? 'bg-indigo-600' : 'bg-zinc-200 dark:bg-zinc-700' }}">
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $plazo->activo ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    @can('consola.plazos.editar')
                                    <button wire:click="editar({{ $plazo->id }})" 
                                            class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                        Editar
                                    </button>
                                    @endcan
                                    @can('consola.plazos.eliminar')
                                    <button wire:click="confirmDelete({{ $plazo->id }})" 
                                            class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                        Eliminar
                                    </button>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-zinc-500 dark:text-zinc-400">
                                    No se encontraron plazos configurados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Cards para móvil -->
            <div class="md:hidden space-y-4">
                @forelse($plazos as $plazo)
                    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $plazo->poa->anio }} - {{ $plazo->poa->name }}</h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $plazo->tipo_plazo_label }}</p>
                                    @if($plazo->nombre_plazo)
                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300">
                                            Personalizado
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @if($plazo->estado === 'vigente')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                    Vigente
                                </span>
                            @elseif($plazo->estado === 'proximo')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                    Próximo
                                </span>
                            @elseif($plazo->estado === 'vencido')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                    Vencido
                                </span>
                            @endif
                        </div>
                        
                        <div class="text-sm text-zinc-600 dark:text-zinc-400 mb-3">
                            <p>{{ $plazo->fecha_inicio->format('d/m/Y') }} - {{ $plazo->fecha_fin->format('d/m/Y') }}</p>
                            @if($plazo->estaVigente())
                                <p class="text-indigo-600 dark:text-indigo-400">{{ $plazo->diasRestantes() }} días restantes</p>
                            @endif
                        </div>

                        <div class="flex justify-between items-center pt-3 border-t border-zinc-200 dark:border-zinc-700">
                            <button wire:click="toggleActivo({{ $plazo->id }})" 
                                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $plazo->activo ? 'bg-indigo-600' : 'bg-zinc-200 dark:bg-zinc-700' }}">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $plazo->activo ? 'translate-x-6' : 'translate-x-1' }}"></span>
                            </button>
                            
                            <div class="space-x-2">
                                @can('consola.plazos.editar')
                                <button wire:click="editar({{ $plazo->id }})" 
                                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 text-sm font-medium">
                                    Editar
                                </button>
                                @endcan
                                @can('consola.plazos.eliminar')
                                <button wire:click="confirmDelete({{ $plazo->id }})" 
                                        class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 text-sm font-medium">
                                    Eliminar
                                </button>
                                @endcan
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-zinc-500 dark:text-zinc-400">
                        No se encontraron plazos configurados
                    </div>
                @endforelse
            </div>

            <!-- Paginación -->
            <div class="mt-6">
                {{ $plazos->links() }}
            </div>

        </div>
    </div>

    <!-- Incluir modales -->
    @include('livewire.plazos.partials.modal-plazo')
    @include('livewire.plazos.partials.modal-delete')
</div>

