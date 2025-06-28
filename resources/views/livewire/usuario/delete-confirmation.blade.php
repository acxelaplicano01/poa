<x-elegant-delete-modal 
    wire:model="showDeleteModal"
    title="Confirmar Eliminación"
    message="¿Estás seguro de que deseas eliminar este usuario?"
    entity-name="{{ $nombreAEliminar }}"
    entity-details="Todos los datos y accesos del usuario serán eliminados permanentemente"
    confirm-method="delete"
    cancel-method="closeDeleteModal"
    confirm-text="Eliminar Usuario"
    cancel-text="Cancelar"
/>