@props([
    'disabled' => false,
    'options' => [],
    'placeholder' => 'Seleccionar...',
    'selected' => null,
    'textKey' => 'text',
    'valueKey' => 'value',
    'hasError' => false,
    'wireModel' => null
])

@php
    // Definir las clases comunes con soporte para modo oscuro y estados
    $baseClasses = 'border-zinc-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full';
    
    // Agregamos clases específicas para modo oscuro
    $darkModeClasses = 'dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300';
    
    // Agregamos clases para el estado de error
    $errorClasses = $hasError ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '';
    
    // Agregamos clases para el estado deshabilitado
    $disabledClasses = $disabled ? 'opacity-70 cursor-not-allowed' : '';
    
    // Combinamos todas las clases
    $classes = "{$baseClasses} {$darkModeClasses} {$errorClasses} {$disabledClasses}";
@endphp

<select 
    {{ $disabled ? 'disabled' : '' }} 
    {{ $wireModel ? "wire:model=$wireModel" : '' }} 
    {{ $attributes->merge(['class' => $classes]) }}
>
    @if($placeholder)
        <option value="">{{ $placeholder }}</option>
    @endif
    
    @foreach($options as $option)
        @php
            // Si options es un array de objetos o arrays asociativos
            $value = is_array($option) || is_object($option) ? data_get($option, $valueKey) : $option;
            $text = is_array($option) || is_object($option) ? data_get($option, $textKey) : $option;
            $isSelected = $selected == $value;
        @endphp
        <option value="{{ $value }}" {{ $isSelected ? 'selected' : '' }}>
            {{ $text }}
        </option>
    @endforeach
</select>