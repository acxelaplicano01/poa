@props(['module' => 'configuracion'])

@php
    $links = config('rutas.' . $module);
    
    // Verificar si $links es un array
    if (!is_array($links)) {
        $links = [];
        \Log::error("No se encontró configuración de navegación para el módulo: " . $module);
    }
@endphp

@if(count($links) > 0)
    @foreach($links as $link)
        @php
            // Verificar si el usuario tiene los permisos necesarios para este enlace
            $hasPermission = true;
            
            if (isset($link['permissions']) && !empty($link['permissions'])) {
                $hasPermission = false;
                $permissions = is_array($link['permissions']) ? $link['permissions'] : [$link['permissions']];
                
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
                \Log::info("Usuario " . auth()->user()->name . " sin permiso para " . ($link['name'] ?? 'enlace sin nombre'));
            }
        @endphp
        
        @if($hasPermission && isset($link['route']) && isset($link['name']))
            <x-navbar-link 
                :active="request()->routeIs($link['route'])" 
                href="{{ route($link['route']) }}"
            >
                <div class="flex-1 text-sm font-medium leading-none whitespace-nowrap">
                    {{ __($link['translation_key'] ?? $link['name']) }}
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