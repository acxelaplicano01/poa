<x-elegant-delete-modal 
    wire:model="showDeleteModal"
    title="Confirmar Eliminación"
    message="¿Estás seguro de que deseas eliminar este estado de requisición?"
    :entity="$estadoToDelete"
    confirm-method="delete"
    cancel-method="closeDeleteModal"
    confirm-text="Eliminar Estado"
    cancel-text="Cancelar"
/>