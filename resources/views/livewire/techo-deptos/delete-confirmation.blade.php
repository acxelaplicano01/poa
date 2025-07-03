<x-elegant-delete-modal 
    wire:model="showDeleteModal"
    title="Confirmar Eliminación"
    message="¿Estás seguro de que deseas eliminar esta asignación presupuestaria?"
    entity-name="{{ $techoDeptoToDelete?->departamento?->name ?? 'Techo Departamental' }}"
    entity-details="Se eliminará la asignación de {{ number_format($techoDeptoToDelete?->monto ?? 0, 2) }} del departamento {{ $techoDeptoToDelete?->departamento?->name ?? '' }}"
    confirm-method="delete"
    cancel-method="closeDeleteModal"
    confirm-text="Eliminar Asignación"
    cancel-text="Cancelar"
/>
