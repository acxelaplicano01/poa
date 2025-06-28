<x-elegant-delete-modal 
    wire:model="showDeleteModal"
    title="Confirmar Eliminación"
    message="¿Estás seguro de que deseas eliminar esta categoría?"
    :entity="$categoriaToDelete"
    confirm-method="delete"
    cancel-method="closeDeleteModal"
    confirm-text="Eliminar Categoría"
    cancel-text="Cancelar"
/>