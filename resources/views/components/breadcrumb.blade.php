@props(['separator' => '/'])

@php
    // Obtener la ruta actual
    $currentRoute = request()->route() ? request()->route()->getName() : '';
    
    // Inicializar variables
    $breadcrumbItems = [];
    $moduleKey = null;
    $itemKey = null;
    
    // Buscar la ruta actual en la configuración
    foreach (config('rutas') as $mk => $moduleData) {
        if (isset($moduleData['items'])) {
            foreach ($moduleData['items'] as $ik => $item) {
                // Verificar si es una ruta del ítem
                if (isset($item['routes']) && is_array($item['routes']) && in_array($currentRoute, $item['routes'])) {
                    $moduleKey = $mk;
                    $itemKey = $ik;
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
            'url' => isset($module['route']) ? route($module['route']) : null
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
                            'url' => route($parentItem['route'])
                        ];
                        break;
                    }
                }
            }
            
            // Añadir el ítem actual
            $breadcrumbItems[] = [
                'label' => $item['titulo'],
                'url' => null // El ítem actual no tiene enlace
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
                    'url' => null
                ];
            }
        }
    } else {
        // Fallback: mostrar al menos el nombre de la ruta formateado
        $routeParts = explode('.', $currentRoute);
        if (count($routeParts) > 1) {
            $breadcrumbItems[] = [
                'label' => ucfirst($routeParts[count($routeParts) - 2]),
                'url' => null
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
                    'url' => null
                ];
            }
        } elseif (!empty($routeParts[0])) {
            $breadcrumbItems[] = [
                'label' => ucfirst($routeParts[0]),
                'url' => null
            ];
        }
    }
@endphp

@if(count($breadcrumbItems) > 0)
<nav {{ $attributes->merge(['class' => 'flex items-center py-3']) }} aria-label="Breadcrumb">
    <ol class="inline-flex items-center flex-wrap gap-1 md:gap-2">
        <!-- Elemento inicial - Dashboard -->
        <li class="inline-flex items-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-zinc-700 hover:text-indigo-600 dark:text-zinc-400 dark:hover:text-white">
                <svg class="w-4 h-4 mr-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                </svg>
                Inicio
            </a>
        </li>
        
        <!-- Elementos dinámicos del breadcrumb -->
        @foreach($breadcrumbItems as $index => $item)
            <!-- Separador entre elementos -->
            <li class="flex items-center">
                <span class="mx-1 text-sm text-zinc-500 dark:text-zinc-500">{{ $separator }}</span>
            </li>
            
            <li class="inline-flex items-center" aria-current="{{ $loop->last ? 'page' : 'false' }}">
                @if($loop->last || !isset($item['url']) || !$item['url'])
                    <!-- Último elemento (actual) o elemento sin URL -->
                    <span class="text-sm font-medium text-zinc-500 dark:text-zinc-500">
                        {{ $item['label'] }}
                    </span>
                @else
                    <!-- Elemento con enlace -->
                    <a href="{{ $item['url'] }}" class="inline-flex items-center text-sm font-medium text-zinc-700 hover:text-indigo-600 dark:text-zinc-400 dark:hover:text-white">
                        {{ $item['label'] }}
                    </a>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
@endif