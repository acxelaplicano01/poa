 <!-- Modal de Confirmación para Eliminar -->
    <x-elegant-delete-modal 
        wire:model="showDeleteModal"
        title="Confirmar Eliminación"
        message="¿Estás seguro de que deseas eliminar todos los techos presupuestarios de esta Unidad Ejecutora?"
        entity-name="{{ $techoUeToDelete?->unidadEjecutora?->name ?? 'Unidad Ejecutora' }}"
        entity-details="Se eliminarán todas las asignaciones presupuestarias de la UE {{ $techoUeToDelete?->unidadEjecutora?->name ?? '' }} desde todas las fuentes de financiamiento."
        confirm-method="delete"
        cancel-method="closeDeleteModal"
        confirm-text="Eliminar Techos"
        cancel-text="Cancelar"
    />