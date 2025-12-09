<x-elegant-delete-modal 
    wire:model="showDeleteModal"
    title="Confirmar Eliminación"
    message="¿Estás seguro de que deseas eliminar esta área?"
    :entity="$areaToDelete"
    entityDetails="{{ $areaToDelete ? ($areaToDelete->nombre . ' • ' . $areaToDelete->descripcion) : 'N/A' }}"
    confirm-method="delete"
    cancel-method="closeDeleteModal"
    confirm-text="Eliminar Área"
    cancel-text="Cancelar"
/>
