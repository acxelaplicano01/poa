<x-elegant-delete-modal 
    wire:model="showDeleteModal"
    title="Confirmar Eliminación"
    message="¿Estás seguro de que deseas eliminar este objetivo?"
    :entity="$objetivoToDelete"
    entityDetails="{{ $objetivoToDelete?->nombre ?? 'N/A' }} • {{ $objetivoToDelete?->descripcion ?? 'N/A' }}"
    confirm-method="delete"
    cancel-method="closeDeleteModal"
    confirm-text="Eliminar Objetivo"
    cancel-text="Cancelar"
/>
