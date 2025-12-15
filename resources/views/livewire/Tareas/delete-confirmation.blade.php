<x-elegant-delete-modal 
    wire:model="showDeleteModal"
    title="Confirmar Eliminación"
    message="¿Estás seguro de que deseas eliminar este recurso?"
    :entity="$recursoToDelete"
    :entityName="$recursoToDelete?->nombre ?? 'Recurso'"
    :entityDetails="''"
    confirm-method="delete"
    cancel-method="closeDeleteModal"
    confirm-text="Eliminar este recurso"
    cancel-text="Cancelar"
/>