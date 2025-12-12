<x-elegant-delete-modal 
    wire:model="showDeleteModal"
    title="Confirmar Eliminación"
    message="¿Estás seguro de que deseas eliminar este proceso de compra?"
    :entity="$procesoToDelete"
    :entityName="$procesoToDelete?->nombre_proceso ?? 'Proceso de Compra'"
    :entityDetails="''"
    confirm-method="delete"
    cancel-method="closeDeleteModal"
    confirm-text="Eliminar Proceso"
    cancel-text="Cancelar"
/>