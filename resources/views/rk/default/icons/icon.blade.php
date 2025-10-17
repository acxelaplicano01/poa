@props([
    'icon' => null,
    'active' => false,
])

@if ($icon)
    @php
        $iconClass = $active ? 'w-5 h-5 dark:text-[var(--color-accent)]' : 'w-5 h-5 text-gray-500 dark:text-gray-400';

        try {
            $icono = svg($icon, $iconClass)->toHtml();
            echo $icono;
        } catch (Exception $e) {

            // si el texto del icono es distinto a heroicon-o-
            // Lanzar la excepción si el icono no se encuentra
            if (trim($icon) !== 'heroicon-o-') {
                throw $e;
            }
        }
    @endphp
@endif
