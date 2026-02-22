<x-elegant-delete-modal 
    wire:model="showDeleteModal"
    title="Confirmar Eliminación"
    message="¿Estás seguro de que deseas eliminar esta dimensión?"
    :entity="$dimensionToDelete"
    entityDetails="{{ $dimensionToDelete?->nombre ?? 'N/A' }} • {{ $dimensionToDelete?->descripcion ?? 'N/A' }}"
    confirm-method="delete"
    cancel-method="closeDeleteModal"
    confirm-text="Eliminar Dimensión"
    cancel-text="Cancelar"
/>
