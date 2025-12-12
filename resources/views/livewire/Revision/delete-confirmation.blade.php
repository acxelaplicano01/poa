<x-elegant-delete-modal 
    wire:model="showDeleteModal"
    title="Confirmar Eliminación"
    message="¿Estás seguro de que deseas eliminar esta revision?"
    :entity="$revisionToDelete"
    :entityName="$revisionToDelete?->nombre ?? 'Proceso de Compra'"
    :entityDetails="''"
    confirm-method="delete"
    cancel-method="closeDeleteModal"
    confirm-text="Eliminar Proceso"
    cancel-text="Cancelar"
/>