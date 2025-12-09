<x-elegant-delete-modal 
    wire:model="showDeleteModal"
    title="Confirmar Eliminación"
    message="¿Estás seguro de que deseas eliminar este resultado?"
    :entity="$resultadoToDelete"
    entityDetails="{{ $resultadoToDelete ? ($resultadoToDelete->nombre . ' • ' . $resultadoToDelete->descripcion) : 'N/A' }}"
    confirm-method="delete"
    cancel-method="closeDeleteModal"
    confirm-text="Eliminar Resultado"
    cancel-text="Cancelar"
/>
