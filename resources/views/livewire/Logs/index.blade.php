{{-- filepath: c:\Users\acxel\Desktop\Desarrollo\Git Repos\POA\resources\views\admin\logs\index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Visor de Logs del Sistema') }}
            </h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('logs.dashboard') }}" 
                   class="px-4 py-2 text-sm font-medium text-center text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="inline-block w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Dashboard de Actividad
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl">
            <!-- Tarjetas de estadísticas -->
            <div class="grid grid-cols-1 gap-5 mb-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="p-4 transition-shadow shadow-sm hover:shadow-lg bg-white rounded-lg sm:p-6 dark:bg-zinc-900">
                    <div class="flex items-center">
                        <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-zinc-600 truncate dark:text-zinc-400">
                                Total de logs
                            </p>
                            <p class="text-2xl font-semibold text-zinc-700 dark:text-white">
                                {{ number_format($stats['total']) }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="p-4 transition-shadow shadow-sm hover:shadow-lg bg-white rounded-lg sm:p-6 dark:bg-zinc-900">
                    <div class="flex items-center">
                        <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-zinc-600 truncate dark:text-zinc-400">
                                Logs hoy
                            </p>
                            <p class="text-2xl font-semibold text-zinc-700 dark:text-white">
                                {{ number_format($stats['today']) }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="p-4 transition-shadow shadow-sm hover:shadow-lg bg-white rounded-lg sm:p-6 dark:bg-zinc-900">
                    <div class="flex items-center">
                        <div class="p-3 mr-4 text-red-500 bg-red-100 rounded-full dark:bg-red-900 dark:text-red-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-zinc-600 truncate dark:text-zinc-400">
                                Errores registrados
                            </p>
                            <p class="text-2xl font-semibold text-zinc-700 dark:text-white">
                                {{ number_format($stats['errors']) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-4 transition-shadow shadow-sm hover:shadow-lg bg-white rounded-lg sm:p-6 dark:bg-zinc-900">
                    <div class="flex items-center">
                        <div class="p-3 mr-4 text-purple-500 bg-purple-100 rounded-full dark:bg-purple-900 dark:text-purple-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-zinc-600 truncate dark:text-zinc-400">
                                Usuarios activos
                            </p>
                            <p class="text-2xl font-semibold text-zinc-700 dark:text-white">
                                {{ number_format($stats['users_active']) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel de filtros -->
            <div class="p-4 mb-6 bg-white rounded-lg shadow sm:p-6 dark:bg-zinc-900">
                <h3 class="mb-4 text-lg font-medium text-zinc-900 dark:text-white">Filtrar logs</h3>
                <form action="{{ route('logs.index') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4 text-zinc-900 dark:text-zinc-300">
                        <div>
                            <label for="module" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Módulo</label>
                            <select id="module" name="module" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-zinc-700 dark:border-zinc-600">
                                <option value="">Todos los módulos</option>
                                @foreach($modules as $module)
                                    <option value="{{ $module }}" {{ request('module') == $module ? 'selected' : '' }}>
                                        {{ $module }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="action" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Acción</label>
                            <select id="action" name="action" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-zinc-700 dark:border-zinc-600">
                                <option value="">Todas las acciones</option>
                                @foreach($actions as $action)
                                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                        {{ ucfirst($action) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="level" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Nivel</label>
                            <select id="level" name="level" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-zinc-700 dark:border-zinc-600">
                                <option value="">Todos los niveles</option>
                                @foreach($levels as $level)
                                    <option value="{{ $level }}" {{ request('level') == $level ? 'selected' : '' }}>
                                        {{ ucfirst($level) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Usuario</label>
                            <select id="user_id" name="user_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-zinc-700 dark:border-zinc-600">
                                <option value="">Todos los usuarios</option>
                                <option value="null" {{ request('user_id') === 'null' ? 'selected' : '' }}>Sistema</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <label for="date_start" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Fecha inicio</label>
                            <x-input type="date" id="date_start" name="date_start" value="{{ request('date_start') }}"
                                   class="block w-full mt-1"/>
                        </div>
                        
                        <div>
                            <label for="date_end" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Fecha fin</label>
                            <x-input type="date" id="date_end" name="date_end" value="{{ request('date_end') }}"
                                   class="block w-full mt-1"/>
                        </div>
                        
                        <div>
                            <label for="search" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Búsqueda</label>
                            <div class="relative mt-1 rounded-md shadow-sm">
                                <x-input type="text" id="search" name="search" value="{{ request('search') }}" 
                                       class="block w-full pl-3 pr-10"
                                       placeholder="Buscar en descripción..."/>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Aplicar filtros
                        </button>
                        
                        <a href="{{ route('logs.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                            Limpiar filtros
                        </a>
                    </div>
                </form>
            </div>

            <!-- Tabla de logs -->
            <div class="overflow-hidden bg-white shadow sm:rounded-lg dark:bg-zinc-900">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                        <thead class="bg-gray-50 dark:bg-zinc-700">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-xs font-medium tracking-wider text-left text-zinc-500 uppercase dark:text-zinc-300">
                                    ID/Fecha
                                </th>
                                <th scope="col" class="px-4 py-3 text-xs font-medium tracking-wider text-left text-zinc-500 uppercase dark:text-zinc-300">
                                    Usuario
                                </th>
                                <th scope="col" class="px-4 py-3 text-xs font-medium tracking-wider text-left text-zinc-500 uppercase dark:text-zinc-300">
                                    Módulo
                                </th>
                                <th scope="col" class="px-4 py-3 text-xs font-medium tracking-wider text-left text-zinc-500 uppercase dark:text-zinc-300">
                                    Acción
                                </th>
                                <th scope="col" class="px-4 py-3 text-xs font-medium tracking-wider text-left text-zinc-500 uppercase dark:text-zinc-300">
                                    Descripción
                                </th>
                                <th scope="col" class="px-4 py-3 text-xs font-medium tracking-wider text-center text-zinc-500 uppercase dark:text-zinc-300">
                                    Nivel
                                </th>
                                <th scope="col" class="px-4 py-3 text-xs font-medium tracking-wider text-right text-zinc-500 uppercase dark:text-zinc-300">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-zinc-900 dark:divide-zinc-700">
                            @forelse($logs as $log)
                                <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm font-medium text-zinc-900 dark:text-white">#{{ $log->id }}</div>
                                        <div class="text-xs text-zinc-500 dark:text-zinc-400">
                                            {{ $log->created_at->format('d/m/Y H:i:s') }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm text-zinc-900 dark:text-white">{{ $log->user_name }}</div>
                                        <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $log->ip_address }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm text-zinc-900 dark:text-white">{{ $log->module }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-zinc-800 dark:bg-zinc-700 dark:text-zinc-300">
                                            <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                {!! $log->action_icon !!}
                                            </svg>
                                            {{ ucfirst($log->action) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm text-zinc-900 dark:text-white line-clamp-2">
                                            {{ $log->description ?? 'Sin descripción' }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium  {{ match($log->level) {
                                                'emergency' => 'bg-red-100 text-red-800',
                                                'alert'     => 'bg-red-100 text-red-800',
                                                'critical'  => 'bg-red-100 text-red-800',
                                                'error'     => 'bg-red-100 text-red-800',
                                                'warning'   => 'bg-yellow-100 text-yellow-800',
                                                'notice'    => 'bg-blue-100 text-blue-800',
                                                'info'      => 'bg-green-100 text-green-800',
                                                'debug'     => 'bg-gray-100 text-gray-800',
                                                default     => 'bg-gray-100 text-gray-800'
                                            } }}">
                                            {{ ucfirst($log->level) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right text-sm font-medium whitespace-nowrap">
                                        <a href="{{ route('logs.show', $log) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            Ver detalles
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-zinc-500 dark:text-zinc-400">
                                        No se encontraron registros de logs que coincidan con los filtros aplicados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="px-4 py-3 bg-white border-t border-gray-200 dark:bg-zinc-900 dark:border-zinc-700 sm:px-6">
                    {{ $logs->links() }}
                </div>
            </div>

            <!-- Limpiar logs antiguos -->
            @can('admin')
                <div class="mt-6 p-4 bg-white rounded-lg shadow sm:p-6 dark:bg-zinc-900">
                    <h3 class="text-lg font-medium text-zinc-900 dark:text-white">Mantenimiento de logs</h3>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                        Elimina logs antiguos para mantener la base de datos optimizada.
                    </p>
                    <form action="{{ route('logs.cleanup') }}" method="POST" class="mt-5">
                        @csrf
                        <div class="flex items-end gap-4">
                            <div>
                                <label for="days" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                    Eliminar logs más antiguos que
                                </label>
                                <div class="mt-1">
                                    <x-input type="number" min="1" step="1" name="days" id="days" value="30" 
                                           class="block w-24 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-zinc-700 dark:border-zinc-600"/>
                                </div>
                            </div>
                            <div class="pb-px">
                                <span class="text-zinc-700 dark:text-zinc-300">días</span>
                            </div>
                            <div>
                                <x-danger-button type="submit" onclick="return confirm('¿Estás seguro de eliminar los logs antiguos? Esta acción no se puede deshacer.')">
                                    Limpiar logs
                                </x-danger-button>
                            </div>
                        </div>
                    </form>
                </div>
            @endcan
        </div>
    </div>
</x-app-layout>