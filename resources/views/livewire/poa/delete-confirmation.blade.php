<x-elegant-delete-modal 
    wire:model="showDeleteModal"
    title="Confirmar Eliminación"
    message="¿Estás seguro de que deseas eliminar este POA?"
    :entity="$poaToDelete"
    :entityName="$poaToDelete?->name ?? 'POA'"
    :entityDetails="$poaToDelete ? ('Año: ' . $poaToDelete->anio . ($poaToDelete->poaDeptos()->count() > 0 ? ' - Tiene ' . $poaToDelete->poaDeptos()->count() . ' departamento(s) asociado(s)' : '')) : ''"
    confirm-method="delete"
    cancel-method="closeDeleteModal"
    confirm-text="Eliminar POA"
    cancel-text="Cancelar"
/>