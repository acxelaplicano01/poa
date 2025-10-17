@props(['separator' => '/'])

@php
    // Obtener la ruta actual
    $currentRoute = request()->route() ? request()->route()->getName() : '';
    
    // Inicializar variables
    $breadcrumbItems = [];
    $moduleKey = null;
    $itemKey = null;
    $moduleIcon = null;
    
    // Buscar la ruta actual en la configuración
    foreach (config('rutas') as $mk => $moduleData) {
        if (isset($moduleData['items'])) {
            foreach ($moduleData['items'] as $ik => $item) {
                // Verificar si es una ruta del ítem
                if (isset($item['routes']) && is_array($item['routes']) && in_array($currentRoute, $item['routes'])) {
                    $moduleKey = $mk;
                    $itemKey = $ik;
                    $moduleIcon = $moduleData['icono'] ?? null;
                    break 2;
                }
            }
        }
    }
    
    // Detectar rutas anidadas (como roles.create, roles.edit, etc.)
    $isNestedRoute = false;
    $parentRoute = null;
    $actionLabel = null;
    
    if (!$moduleKey && strpos($currentRoute, '.') !== false) {
        $routeParts = explode('.', $currentRoute);
        $actionLabels = [
            'create' => 'Crear',
            'edit' => 'Editar',
            'show' => 'Ver',
            'delete' => 'Eliminar',
        ];
        
        // Si la última parte es una acción conocida
        $lastPart = end($routeParts);
        if (isset($actionLabels[$lastPart])) {
            $isNestedRoute = true;
            $actionLabel = $actionLabels[$lastPart];
            
            // Construir la ruta padre (quitar la última parte)
            $parentRouteParts = array_slice($routeParts, 0, -1);
            $parentRoute = implode('.', $parentRouteParts);
            
            // Buscar la ruta padre en la configuración
            $found = false;
            foreach (config('rutas') as $mk => $moduleData) {
                if ($found) break;
                if (isset($moduleData['items'])) {
                    foreach ($moduleData['items'] as $ik => $item) {
                        if (isset($item['routes']) && is_array($item['routes']) && in_array($parentRoute, $item['routes'])) {
                            $moduleKey = $mk;
                            $itemKey = $ik;
                            $moduleIcon = $moduleData['icono'] ?? null;
                            $found = true;
                            break;
                        }
                        // También verificar si la ruta padre coincide directamente con la ruta del item
                        if (isset($item['route']) && $item['route'] === $parentRoute) {
                            $moduleKey = $mk;
                            $itemKey = $ik;
                            $moduleIcon = $moduleData['icono'] ?? null;
                            $found = true;
                            break;
                        }
                    }
                }
            }
        }
    }
    
    // Si encontramos el módulo y el ítem, construir la ruta de breadcrumb
    if ($moduleKey && isset(config('rutas')[$moduleKey])) {
        $module = config('rutas')[$moduleKey];
        
        // Añadir el módulo al breadcrumb
        $breadcrumbItems[] = [
            'label' => $module['breadcrumb_label'] ?? $module['titulo'],
            'url' => isset($module['route']) ? route($module['route']) : null,
            'icon' => $module['icono'] ?? null
        ];
        
        // Si tenemos un ítem específico
        if ($itemKey !== null && isset($module['items'][$itemKey])) {
            $item = $module['items'][$itemKey];
            
            // Si el ítem tiene un padre en la jerarquía del breadcrumb
            if (isset($item['parent_breadcrumb'])) {
                // Buscar el ítem padre en el mismo módulo
                foreach ($module['items'] as $parentItem) {
                    if (isset($parentItem['route']) && $parentItem['route'] === $item['parent_breadcrumb']) {
                        $breadcrumbItems[] = [
                            'label' => $parentItem['titulo'],
                            'url' => route($parentItem['route']),
                            'icon' => $parentItem['icono'] ?? null
                        ];
                        break;
                    }
                }
            }
            
            // Añadir el ítem principal
            $itemUrl = null;
            if ($isNestedRoute && isset($item['route'])) {
                // Si es una ruta anidada, el ítem principal debe tener enlace a su lista
                $itemUrl = route($item['route']);
            } elseif (!$isNestedRoute && isset($item['route'])) {
                // Si no es una ruta anidada, no tiene enlace (es la página actual)
                $itemUrl = null;
            }
            
            $breadcrumbItems[] = [
                'label' => $item['titulo'],
                'url' => $itemUrl,
                'icon' => $item['icono'] ?? null
            ];
            
            // Si es una ruta anidada, añadir la acción
            if ($isNestedRoute && $actionLabel) {
                $breadcrumbItems[] = [
                    'label' => $actionLabel . ' ' . $item['titulo'],
                    'url' => null, // La acción actual no tiene enlace
                    'icon' => null
                ];
            }
        }
    } else {
        // Fallback: construir breadcrumb basado en la estructura de la ruta
        $routeParts = explode('.', $currentRoute);
        
        if (count($routeParts) >= 2) {
            $actionLabels = [
                'create' => 'Crear',
                'edit' => 'Editar',
                'show' => 'Ver',
                'delete' => 'Eliminar',
                'index' => 'Lista',
            ];
            
            // Primer nivel (ej: configuracion, planificacion)
            if (count($routeParts) >= 1) {
                $breadcrumbItems[] = [
                    'label' => ucfirst($routeParts[0]),
                    'url' => null,
                    'icon' => null
                ];
            }
            
            // Segundo nivel (ej: roles, usuarios)
            if (count($routeParts) >= 2) {
                $secondLevel = $routeParts[1];
                $lastPart = end($routeParts);
                
                // Si el último elemento es una acción y hay al menos 3 partes
                if (count($routeParts) >= 3 && isset($actionLabels[$lastPart])) {
                    // El segundo nivel tiene enlace
                    $breadcrumbItems[] = [
                        'label' => ucfirst($secondLevel),
                        'url' => null, // Se podría construir la ruta base si fuera necesario
                        'icon' => null
                    ];
                    
                    // Añadir la acción
                    $breadcrumbItems[] = [
                        'label' => $actionLabels[$lastPart] . ' ' . ucfirst($secondLevel),
                        'url' => null,
                        'icon' => null
                    ];
                } else {
                    // El segundo nivel es la página actual
                    $breadcrumbItems[] = [
                        'label' => ucfirst($secondLevel),
                        'url' => null,
                        'icon' => null
                    ];
                }
            }
        } elseif (!empty($routeParts[0])) {
            $breadcrumbItems[] = [
                'label' => ucfirst($routeParts[0]),
                'url' => null,
                'icon' => null
            ];
        }
    }
@endphp

@if(count($breadcrumbItems) > 0)
<nav {{ $attributes->merge(['class' => 'flex items-center py-3']) }} aria-label="Breadcrumb">
    <ol class="inline-flex items-center flex-wrap gap-1 md:gap-2">
        <!-- Elementos del breadcrumb con el icono del módulo en el primer elemento -->
        @foreach($breadcrumbItems as $index => $item)
            @if($loop->first)
                <li class="inline-flex items-center">
                    @if(isset($item['url']) && $item['url'])
                        <a href="{{ $item['url'] }}" class="inline-flex items-center text-sm font-medium text-zinc-700 hover:text-indigo-600 dark:text-zinc-400 dark:hover:text-white">
                            @if(isset($item['icon']) && $item['icon'])
                                <svg class="w-4 h-4 mr-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    {!! $item['icon'] !!}
                                </svg>
                            @endif
                            {{ $item['label'] }}
                        </a>
                    @else
                        <span class="inline-flex items-center text-sm font-medium text-zinc-500 dark:text-zinc-500">
                            @if(isset($item['icon']) && $item['icon'])
                                <svg class="w-4 h-4 mr-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    {!! $item['icon'] !!}
                                </svg>
                            @endif
                            {{ $item['label'] }}
                        </span>
                    @endif
                </li>
            @else
                <!-- Separador entre elementos -->
                <li class="flex items-center">
                    <span class="mx-1 text-sm text-zinc-500 dark:text-zinc-500">{{ $separator }}</span>
                </li>
                
                <li class="inline-flex items-center" aria-current="{{ $loop->last ? 'page' : 'false' }}">
                    @if($loop->last || !isset($item['url']) || !$item['url'])
                        <!-- Último elemento (actual) o elemento sin URL -->
                        <span class="text-sm font-medium text-zinc-500 dark:text-zinc-500">
                            @if(isset($item['icon']) && $item['icon'])
                                <svg class="w-4 h-4 mr-1.5 inline" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    {!! $item['icon'] !!}
                                </svg>
                            @endif
                            {{ $item['label'] }}
                        </span>
                    @else
                        <!-- Elemento con enlace -->
                        <a href="{{ $item['url'] }}" class="inline-flex items-center text-sm font-medium text-zinc-700 hover:text-indigo-600 dark:text-zinc-400 dark:hover:text-white">
                            @if(isset($item['icon']) && $item['icon'])
                                <svg class="w-4 h-4 mr-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    {!! $item['icon'] !!}
                                </svg>
                            @endif
                            {{ $item['label'] }}
                        </a>
                    @endif
                </li>
            @endif
        @endforeach
    </ol>
</nav>
@endif