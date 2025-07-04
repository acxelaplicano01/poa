<x-elegant-delete-modal 
    wire:model="showDeleteModal"
    title="Confirmar Eliminación"
    message="¿Estás seguro de que deseas eliminar todas las asignaciones presupuestarias de este departamento?"
    entity-name="{{ $techoDeptoToDelete?->departamento?->name ?? 'Departamento' }}"
    entity-details="Se eliminarán todas las asignaciones presupuestarias del departamento {{ $techoDeptoToDelete?->departamento?->name ?? '' }} desde todas las fuentes de financiamiento."
    confirm-method="delete"
    cancel-method="closeDeleteModal"
    confirm-text="Eliminar Asignaciones"
    cancel-text="Cancelar"
/>
