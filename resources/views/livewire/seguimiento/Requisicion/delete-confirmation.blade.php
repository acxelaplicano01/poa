<x-elegant-delete-modal 
    wire:model="showDeleteModal"
    title="Confirmar Eliminación"
    message="¿Estás seguro de que deseas eliminar esta requisición?"
    :entity="$requisicionToDelete"
    entityDetails="{{ $requisicionToDelete ? ($requisicionToDelete->nombre . ' • ' . $requisicionToDelete->descripcion) : 'N/A' }}"
    confirm-method="delete"
    cancel-method="closeDeleteModal"
    confirm-text="Eliminar Requisición"
    cancel-text="Cancelar"
/>
