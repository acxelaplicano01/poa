
@props(['module'])

@php
    $moduleConfig = config('rutas.' . $module, []);
    $items = $moduleConfig['items'] ?? [];
    $currentRoute = request()->route() ? request()->route()->getName() : '';
    
    // Verificar si hay ítems configurados
    if (!is_array($items) || empty($items)) {
        \Log::error("No se encontraron ítems de navegación para el módulo: " . $module);
    }
@endphp

<div class="flex items-center">
    @if(is_array($items) && count($items) > 0)
        @foreach($items as $item)
            @php
                // Verificar si el usuario tiene los permisos necesarios para este enlace
                $hasPermission = true;
                
                if (isset($item['permisos']) && !empty($item['permisos'])) {
                    $hasPermission = false;
                    $permissions = is_array($item['permisos']) ? $item['permisos'] : [$item['permisos']];
                    
                    if (count($permissions) > 0) {
                        foreach ($permissions as $permission) {
                            try {
                                if (auth()->check() && auth()->user()->can($permission)) {
                                    $hasPermission = true;
                                    break;
                                }
                            } catch (\Exception $e) {
                                \Log::warning("Error al verificar el permiso '{$permission}': " . $e->getMessage());
                            }
                        }
                    } else {
                        // Si el array de permisos está vacío, permitir acceso
                        $hasPermission = true;
                    }
                }
                
                // Usuarios súper admin siempre tienen acceso
                if (auth()->check() && auth()->user()->hasRole('super-admin')) {
                    $hasPermission = true;
                }
                
                // Para depuración
                if (!$hasPermission && config('app.debug')) {
                    \Log::info("Usuario " . auth()->user()->name . " sin permiso para " . ($item['titulo'] ?? 'enlace sin nombre'));
                }
                
                // Verificar si el ítem está activo
                $isActive = false;
                if (isset($item['routes']) && is_array($item['routes'])) {
                    $isActive = in_array($currentRoute, $item['routes']);
                }
            @endphp
            
            @if($hasPermission && isset($item['route']) && isset($item['titulo']))
                <x-navbar-link 
                    :active="$isActive" 
                    href="{{ route($item['route']) }}"
                >
                    <div class="flex-1 text-sm font-medium leading-none whitespace-nowrap">
                        {{ $item['titulo'] }}
                    </div>
                </x-navbar-link>
            @endif
        @endforeach
    @else
        <!-- Mensaje de depuración -->
        @if(config('app.debug'))
            <span class="text-red-500 text-xs">No hay enlaces configurados para el módulo: {{ $module }}</span>
        @endif
    @endif
</div>