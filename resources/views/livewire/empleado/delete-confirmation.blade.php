<x-elegant-delete-modal 
    wire:model="showDeleteModal"
    title="Confirmar Eliminación"
    message="¿Estás seguro de que deseas eliminar este empleado?"
    :entity="$empleadoToDelete"
    confirm-method="delete"
    cancel-method="closeDeleteModal"
    confirm-text="Eliminar Empleado"
    cancel-text="Cancelar"
/>