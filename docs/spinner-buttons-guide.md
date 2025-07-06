# Componentes de Botón con Spinner

## Componentes Disponibles

- `<x-spinner-button>` - Botón principal (azul)
- `<x-spinner-secondary-button>` - Botón secundario (gris)
- `<x-spinner-danger-button>` - Botón de peligro (rojo)

## Correcciones de Errores de Propiedades Nulas

Durante la implementación de los componentes de botón, también se han corregido errores relacionados con acceso a propiedades nulas en las siguientes vistas:

### Archivos Corregidos:

1. **resources/views/livewire/consola/pei/delete-confirmation.blade.php**
   - Corrección: Agregado operador null-safe `?->` para evitar errores cuando `$peiToDelete` es null
   - Cambio: `{{ $peiToDelete->periodo }}` → `{{ $peiToDelete?->periodo ?? 'N/A' }}`

2. **resources/views/livewire/consola/pei/plan-estrategico-institucional.blade.php**
   - Corrección: Agregado operador null coalescing `??` para propiedades de periodo
   - Cambio: `{{ $pei->periodo }}` → `{{ $pei->periodo ?? 'N/A' }}`

3. **resources/views/livewire/techo-deptos/gestion-techo-deptos.blade.php**
   - Corrección: Agregado operador null coalescing `??` para propiedades de POA y unidad ejecutora
   - Cambio: `{{ $poa->anio }}` → `{{ $poa->anio ?? 'N/A' }}`
   - Cambio: `{{ $unidadEjecutora->name }}` → `{{ $unidadEjecutora->name ?? 'N/A' }}`

### Recomendaciones Generales:

- Siempre usar operadores null-safe (`?->`) cuando se acceda a propiedades de objetos que pueden ser null
- Utilizar el operador null coalescing (`??`) para proporcionar valores por defecto
- Mostrar valores informativos como 'N/A' en lugar de errores al usuario

## Uso Básico

### Botón Principal con Spinner

```blade
<x-spinner-button 
    wire:click="save" 
    loadingTarget="save" 
    loadingText="Guardando...">
    Guardar
</x-spinner-button>
```

### Botón Secundario con Spinner

```blade
<x-spinner-secondary-button 
    wire:click="cancel" 
    loadingTarget="cancel" 
    loadingText="Cancelando...">
    Cancelar
</x-spinner-secondary-button>
```

### Botón de Peligro con Spinner

```blade
<x-spinner-danger-button 
    wire:click="delete" 
    loadingTarget="delete" 
    loadingText="Eliminando...">
    Eliminar
</x-spinner-danger-button>
```

## Propiedades

| Propiedad | Tipo | Valor por defecto | Descripción |
|-----------|------|-------------------|-------------|
| `loadingTarget` | string | `null` | Nombre del método Livewire que activa el spinner |
| `loadingText` | string | `'Cargando...'` (varía por tipo) | Texto que se muestra durante el loading |
| `type` | string | `'submit'` o `'button'` | Tipo del botón HTML |
| `disabled` | boolean | `false` | Si el botón está deshabilitado |
| `icon` | slot | `null` | Slot para el icono que será reemplazado por el spinner |

## Ejemplos Prácticos

### Botón Simple sin Icono

```blade
<x-spinner-button 
    wire:click="save" 
    loadingTarget="save" 
    loadingText="Guardando...">
    Guardar
</x-spinner-button>
```

### Botón con Icono que se Reemplaza por Spinner

```blade
<x-spinner-button 
    wire:click="sync" 
    loadingTarget="sync" 
    loadingText="Sincronizando...">
    <x-slot name="icon">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
    </x-slot>
    Sincronizar
</x-spinner-button>
```

### Botón de Agregar con Icono

```blade
<x-spinner-secondary-button 
    wire:click="addItem" 
    loadingTarget="addItem" 
    loadingText="Agregando...">
    <x-slot name="icon">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
    </x-slot>
    Agregar Item
</x-spinner-secondary-button>
```

### Botón de Eliminar con Icono

```blade
<x-spinner-danger-button 
    wire:click="delete({{ $id }})" 
    loadingTarget="delete({{ $id }})" 
    loadingText="Eliminando...">
    <x-slot name="icon">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
    </x-slot>
    Eliminar
</x-spinner-danger-button>
```

## Funcionalidad

1. **Estado Normal**: Muestra el contenido del slot (texto + iconos)
2. **Estado Loading**: 
   - Oculta el contenido normal (incluyendo iconos)
   - Muestra el spinner animado **en el lugar exacto del icono**
   - Muestra el texto de loading
   - Deshabilita el botón automáticamente
3. **Sin Loading Target**: Funciona como botón normal sin spinner

### Comportamiento de Iconos

- **Con Icono**: El spinner reemplaza exactamente el icono durante el loading
- **Sin Icono**: El spinner aparece antes del texto con margen adecuado
- **Posicionamiento**: El spinner mantiene el mismo tamaño y posición que el icono original
- **Transición**: Cambio suave entre icono normal y spinner

## Migración desde Implementación Manual

### Antes (Manual)
```blade
<x-button type="submit" wire:loading.attr="disabled" class="flex items-center">
    <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>
    <span wire:loading.remove wire:target="save">
        {{ $isEditing ? __('Actualizar') : __('Crear') }}
    </span>
    <span wire:loading wire:target="save">
        {{ $isEditing ? __('Actualizando...') : __('Creando...') }}
    </span>
</x-button>
```

### Después (Con Componente)
```blade
<x-spinner-button 
    type="submit" 
    loadingTarget="save" 
    :loadingText="$isEditing ? __('Actualizando...') : __('Creando...')">
    {{ $isEditing ? __('Actualizar') : __('Crear') }}
</x-spinner-button>
```

## Ventajas

- **Menos código**: Reduce líneas de código en un 70%
- **Consistencia**: Todos los botones con spinner se ven igual
- **Fácil mantenimiento**: Cambios centralizados en el componente
- **Menos errores**: No hay que recordar todas las clases y estructuras
- **Flexibilidad**: Soporte para iconos y texto personalizado
