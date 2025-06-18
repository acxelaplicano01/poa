<?php

namespace App\Livewire\Actividad;

use App\Models\Actividad\TipoActividad;
use App\Services\LogService;
use Livewire\Component;
use Livewire\WithPagination;
use Log;

class TipoActividades extends Component
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
    public $tipoActividad_id;
    public $tipo;
    public $isEditing = false;
    public $tipoAEliminar;

    // Escuchar eventos
    protected $listeners = ['refresh' => '$refresh'];

    // Reglas de validación
    protected $rules = [
        'tipo' => 'required|string|max:100|unique:tipo_actividads,tipo'
    ];

    protected $validationAttributes = [
        'tipo' => 'tipo de actividad',
    ];

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
        $tipoActividad = TipoActividad::findOrFail($id);
        $this->tipoActividad_id = $id;
        $this->tipo = $tipoActividad->tipo;
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
            // Si estamos editando, ignorar la validación unique para el propio registro
        if ($this->isEditing) {
            $this->rules['tipo'] = 'required|string|max:100|unique:tipo_actividads,tipo,'.$this->tipoActividad_id;
        }

        $this->validate();

        TipoActividad::updateOrCreate(['id' => $this->tipoActividad_id], [
            'tipo' => $this->tipo,
        ]);

        LogService::activity(
            $this->isEditing ? 'actualizar' : 'crear',
            'Configuración',
            $this->isEditing ? 'Tipo de actividad actualizado correctamente.' : 'Tipo de actividad creado correctamente.',
            [
                'tipo_actividad_id' => $this->tipoActividad_id,
                'tipo' => $this->tipo,
                'user_id' => auth()->id(),
            ]
        );

        session()->flash('message', $this->isEditing ? 'Tipo de actividad actualizado correctamente.' : 'Tipo de actividad creado correctamente.');
        
        $this->closeModal();
        $this->resetInputFields();
    } catch (\Illuminate\Validation\ValidationException $e) {
        LogService::activity(
            $this->isEditing ? 'actualizar' : 'crear',
            'Configuración',
            'Error de validación al guardar tipo de actividad',
            [
                'tipo_actividad_id' => $this->tipoActividad_id,
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
        $this->tipoActividad_id = null;
        $this->tipo = '';
        $this->resetValidation();
    }

    public function confirmDelete($id)
    {
        $tipoActividad = TipoActividad::findOrFail($id);
        $this->tipoActividad_id = $id;
        $this->tipoAEliminar = $tipoActividad->tipo;
        $this->confirmingDelete = true;
    }

    public function cancelDelete()
    {
        $this->confirmingDelete = false;
        $this->tipoActividad_id = null;
        $this->tipoAEliminar = '';
    }

    public function delete()
    {
        try {
            $tipoActividad = TipoActividad::findOrFail($this->tipoActividad_id);
            
            // Verificar si tiene actividades asociadas
            if ($tipoActividad->actividades()->count() > 0) {
                session()->flash('error', 'No se puede eliminar el tipo de actividad porque tiene actividades asociadas.');
                $this->confirmingDelete = false;
                return;
            }
            
            $tipoActividad->delete();
            LogService::activity(
                'eliminar',
                'Configuración',
                'Tipo de actividad eliminado correctamente.',
                [
                    'tipo_actividad_id' => $this->tipoActividad_id,
                    'tipo' => $this->tipoAEliminar,
                    'user_id' => auth()->id(),
                ]
            );
            session()->flash('message', 'Tipo de actividad eliminado correctamente.');
        } catch (\Exception $e) {
            LogService::activity(
                'eliminar',
                'Configuración',
                'Error al eliminar tipo de actividad',
                [
                    'tipo_actividad_id' => $this->tipoActividad_id,
                    'tipo' => $this->tipoAEliminar,
                    'error' => $e->getMessage(),
                    'user_id' => auth()->id(),
                ],
                'error'
            );
            session()->flash('error', 'Ocurrió un error al eliminar el tipo de actividad.');
        }
        
        $this->confirmingDelete = false;
        $this->tipoActividad_id = null;
    }

    public function render()
    {
        $tipoActividades = TipoActividad::query()
            ->when($this->search, function($query) {
                $query->where('tipo', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
            
        return view('livewire.actividad.tipo-actividades', [
            'tipoActividades' => $tipoActividades
        ])->layout('layouts.app');
    }
}