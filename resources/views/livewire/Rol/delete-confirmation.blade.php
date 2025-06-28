<x-elegant-delete-modal 
    wire:model="showDeleteModal"
    title="Confirmar Eliminación"
    message="¿Estás seguro de que deseas eliminar este rol?"
    entity-name="{{ $nombreAEliminar }}"
    entity-details="Todos los permisos y asignaciones del rol serán eliminados"
    confirm-method="delete"
    cancel-method="cancelDelete"
    confirm-text="Eliminar Rol"
    cancel-text="Cancelar"
/>