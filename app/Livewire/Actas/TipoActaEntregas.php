<?php

namespace App\Livewire\Actas;

use App\Models\Actas\TipoActaEntrega;
use App\Services\LogService;
use Livewire\Component;
use Livewire\WithPagination;
use Log;

class TipoActaEntregas extends Component
{
    use WithPagination;

    // Propiedades para la tabla
    public $search = '';
    public $perPage = 10;
    public $sortField = 'id';
    public $sortDirection = 'asc';

    // Propiedades para el modal
    public $isOpen = false;
    public $confirmingDelete = false;
    public $tipoActaEntrega_id;
    public $tipo;
    public $isEditing = false;
    public $tipoAEliminar;

    // Escuchar eventos
    protected $listeners = ['refresh' => '$refresh'];

    // Reglas de validación
    protected $rules = [
        'tipo' => 'required|string|max:100|unique:tipo_acta_entrega,tipo'
    ];

    protected $validationAttributes = [
        'tipo' => 'tipo de acta',
    ];

    // Inicializar propiedades
    public function mount()
    {
        $this->tipoAEliminar = '';
    }

    // Método para ordenar columnas
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        
        $this->sortField = $field;
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isEditing = false;
        $this->openModal();
    }

    public function edit($id)
    {
        $tipoActaEntrega = TipoActaEntrega::findOrFail($id);
        $this->tipoActaEntrega_id = $id;
        $this->tipo = $tipoActaEntrega->tipo;
        $this->isEditing = true;
        
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function store()
    {
        try {
            // Validar los campos
            $this->validate();
             // Si estamos editando, ignorar la validación unique para el propio registro
        if ($this->isEditing) {
            $this->rules['tipo'] = 'required|string|max:100|unique:tipo_acta_entrega,tipo,'.$this->tipoActaEntrega_id;
        }

        TipoActaEntrega::updateOrCreate(['id' => $this->tipoActaEntrega_id], [
            'tipo' => $this->tipo,
        ]);
        
        LogService::activity(
            $this->isEditing ? 'actualizar' : 'crear',
            'Configuración',
            $this->isEditing ? 'Tipo de acta de entrega actualizado correctamente.' : 'Tipo de acta de entrega creado correctamente.',
            [
                'tipo_acta_entrega_id' => $this->tipoActaEntrega_id,
                'tipo' => $this->tipo,
                'user_id' => auth()->id(),
            ]
        );
        session()->flash('message', $this->isEditing ? 
            'Tipo de acta de entrega actualizado correctamente.' : 
            'Tipo de acta de entrega creado correctamente.');
        
        $this->closeModal();
        $this->resetInputFields();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Manejar errores de validación
            LogService::activity(
                $this->isEditing ? 'actualizar' : 'crear',
                'Configuración',
                'Error de validación al guardar tipo de acta de entrega',
                [
                    'tipo_acta_entrega_id' => $this->tipoActaEntrega_id,
                    'tipo' => $this->tipo,
                    'error' => $e->getMessage(),
                    'user_id' => auth()->id(),
                ],
                'error'
            );
            session()->flash('error', 'Error de validación: ' . $e->getMessage());
            return;
        }
    }

    public function resetInputFields()
    {
        $this->tipoActaEntrega_id = null;
        $this->tipo = '';
        $this->resetValidation();
    }

    public function confirmDelete($id)
    {
        try {
            $tipoActaEntrega = TipoActaEntrega::findOrFail($id);
            $this->tipoActaEntrega_id = $id;
            $this->tipoAEliminar = $tipoActaEntrega->tipo;
            $this->confirmingDelete = true;
            
            // Log para debug
            \Log::info('confirmDelete called', [
                'id' => $id,
                'tipo' => $tipoActaEntrega->tipo,
                'confirmingDelete' => $this->confirmingDelete
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in confirmDelete: ' . $e->getMessage());
            session()->flash('error', 'Error al preparar la eliminación.');
        }
    }

    public function cancelDelete()
    {
        $this->confirmingDelete = false;
        $this->tipoActaEntrega_id = null;
        $this->tipoAEliminar = '';
    }

    public function delete()
    {
        try {
            $tipoActaEntrega = TipoActaEntrega::findOrFail($this->tipoActaEntrega_id);
            
            // Verificar si tiene actas asociadas
            if ($tipoActaEntrega->actas()->count() > 0) {
                session()->flash('error', 'No se puede eliminar el tipo de acta de entrega porque tiene actas asociadas.');
                $this->confirmingDelete = false;
                return;
            }
            
            $tipoActaEntrega->delete();
            LogService::activity(
                'eliminar',
                'Configuración',
                'Tipo de acta de entrega eliminado correctamente.',
                [
                    'tipo_acta_entrega_id' => $this->tipoActaEntrega_id,
                    'tipo' => $this->tipoAEliminar,
                    'user_id' => auth()->id(),
                ]
            );
            session()->flash('message', 'Tipo de acta de entrega eliminado correctamente.');
        } catch (\Exception $e) {
            LogService::activity(
                'eliminar',
                'Configuración',
                'Error al eliminar tipo de acta de entrega',
                [
                    'tipo_acta_entrega_id' => $this->tipoActaEntrega_id,
                    'tipo' => $this->tipoAEliminar,
                    'error' => $e->getMessage(),
                    'user_id' => auth()->id(),
                ],
                'error'
            );
            session()->flash('error', 'Ocurrió un error al eliminar el tipo de acta de entrega.');
        }
        
        $this->confirmingDelete = false;
        $this->tipoActaEntrega_id = null;
    }

    public function render()
    {
        $tipoActaEntregas = TipoActaEntrega::query()
            ->when($this->search, function($query) {
                $query->where('tipo', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
            
        return view('livewire.actas.tipo-acta-entregas', [
            'tipoActaEntregas' => $tipoActaEntregas
        ])->layout('layouts.app');
    }
}