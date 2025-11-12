<x-elegant-delete-modal 
    wire:model="showDeleteModal"
    title="Confirmar Eliminación"
    message="¿Estás seguro de que deseas eliminar este PEI?"
    :entity="$peiToDelete"
    entityDetails=" {{ $peiToDelete?->institucion->nombre ?? 'N/A' }} • {{ $peiToDelete?->periodo ?? 'N/A' }}"
    confirm-method="confirmDelete"
    cancel-method="closeDeleteModal"
    confirm-text="Eliminar Departamento"
    cancel-text="Cancelar"
/>
