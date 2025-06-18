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
            
            // Añadir el ítem actual
            $breadcrumbItems[] = [
                'label' => $item['titulo'],
                'url' => null, // El ítem actual no tiene enlace
                'icon' => $item['icono'] ?? null
            ];
            
            // Detectar acciones específicas (show, edit, create)
            $actionLabels = [
                'show' => 'Ver',
                'edit' => 'Editar',
                'create' => 'Crear',
            ];
            
            $routeParts = explode('.', $currentRoute);
            $lastPart = end($routeParts);
            
            if (isset($actionLabels[$lastPart])) {
                $breadcrumbItems[count($breadcrumbItems) - 1]['url'] = route($item['route']); // El ítem principal ahora tiene enlace
                $breadcrumbItems[] = [
                    'label' => $actionLabels[$lastPart],
                    'url' => null,
                    'icon' => null
                ];
            }
        }
    } else {
        // Fallback: mostrar al menos el nombre de la ruta formateado
        $routeParts = explode('.', $currentRoute);
        if (count($routeParts) > 1) {
            $breadcrumbItems[] = [
                'label' => ucfirst($routeParts[count($routeParts) - 2]),
                'url' => null,
                'icon' => null
            ];
            
            $actionLabels = [
                'show' => 'Ver',
                'edit' => 'Editar',
                'create' => 'Crear',
                'index' => 'Lista',
            ];
            
            $lastPart = end($routeParts);
            if (isset($actionLabels[$lastPart])) {
                $breadcrumbItems[] = [
                    'label' => $actionLabels[$lastPart],
                    'url' => null,
                    'icon' => null
                ];
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