<x-elegant-delete-modal 
    wire:model="showDeleteModal"
    title="Confirmar Eliminación"
    message="¿Estás seguro de que deseas eliminar esta institución?"
    :entity="$institucionToDelete"
    confirm-method="delete"
    cancel-method="closeDeleteModal"
    confirm-text="Eliminar Institución"
    cancel-text="Cancelar"
/>