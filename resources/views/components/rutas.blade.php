
@props(['module'])

@php
    $moduleConfig = config('rutas.' . $module, []);
    $items = $moduleConfig['items'] ?? [];
    $currentRoute = request()->route() ? request()->route()->getName() : '';
    
    // Verificar acceso general al módulo 
    $hasModuleAccess = true;
    $user = auth()->user();
    
    if ($user && !$user->hasRole('super-admin')) {
        $permiso = "acceso-{$module}";
        $hasModuleAccess = $user->can($permiso);
    }
    
    // Verificar si hay ítems configurados
    if (!is_array($items) || empty($items)) {
        \Log::error("No se encontraron ítems de navegación para el módulo: " . $module);
    }
    
    // Filtrar los ítems para mostrar solo los que tienen acceso o son always_visible
    $filteredItems = [];
    
    if (is_array($items)) {
        foreach ($items as $item) {
            // Si el ítem es always_visible, se muestra independientemente del permiso general
            $alwaysVisible = isset($item['always_visible']) && $item['always_visible'];
            
            // Verificar acceso al ítem específico
            $hasItemPermission = true;
            
            if (isset($item['permisos']) && !empty($item['permisos']) && !$user->hasRole('super-admin')) {
                $hasItemPermission = false;
                $permissions = is_array($item['permisos']) ? $item['permisos'] : [$item['permisos']];
                
                if (count($permissions) > 0) {
                    foreach ($permissions as $permission) {
                        try {
                            if ($user->can($permission)) {
                                $hasItemPermission = true;
                                break;
                            }
                        } catch (\Exception $e) {
                            \Log::warning("Error al verificar el permiso '{$permission}': " . $e->getMessage());
                        }
                    }
                }
            }
            
            // Incluir el ítem si:
            // - Es always_visible, o
            // - El usuario tiene acceso al módulo Y tiene permiso para el ítem
            if ($alwaysVisible || ($hasModuleAccess && $hasItemPermission)) {
                $filteredItems[] = $item;
            } elseif (config('app.debug')) {
                \Log::info("Usuario " . ($user ? $user->name : 'anónimo') . " sin permiso para " . ($item['titulo'] ?? 'enlace sin nombre'));
            }
        }
    }
@endphp

<div class="flex items-center">
    @if(count($filteredItems) > 0)
        @foreach($filteredItems as $item)
            @php
                // Verificar si el ítem está activo
                $isActive = false;
                if (isset($item['routes']) && is_array($item['routes'])) {
                    $isActive = in_array($currentRoute, $item['routes']);
                }
            @endphp
            
            <x-navbar-link 
                :active="$isActive" 
                href="{{ route($item['route']) }}"
            >
                <div class="flex-1 text-sm font-medium leading-none whitespace-nowrap">
                    {{ $item['titulo'] }}
                </div>
            </x-navbar-link>
        @endforeach
    @else
        <!-- Mensaje de depuración -->
        @if(config('app.debug'))
            <span class="text-red-500 text-xs">No hay enlaces configurados o accesibles para el módulo: {{ $module }}</span>
        @endif
    @endif
</div>