<?php

namespace App\Livewire\Requisicion;

use App\Models\Requisicion\UnidadMedida;
use Livewire\Component;
use Livewire\WithPagination;

class UnidadMedidas extends Component
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
    public $unidadMedida_id;
    public $nombre;
    public $isEditing = false;
    public $unidadAEliminar;

    // Escuchar eventos
    protected $listeners = ['refresh' => '$refresh'];

    // Reglas de validación
    protected $rules = [
        'nombre' => 'required|string|max:100|unique:unidadmedidas,nombre'
    ];

    protected $validationAttributes = [
        'nombre' => 'nombre de unidad',
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
        $unidadMedida = UnidadMedida::findOrFail($id);
        $this->unidadMedida_id = $id;
        $this->nombre = $unidadMedida->nombre;
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
        // Si estamos editando, ignorar la validación unique para el propio registro
        if ($this->isEditing) {
            $this->rules['nombre'] = 'required|string|max:100|unique:unidadmedidas,nombre,'.$this->unidadMedida_id;
        }

        $this->validate();

        UnidadMedida::updateOrCreate(['id' => $this->unidadMedida_id], [
            'nombre' => $this->nombre,
        ]);

        session()->flash('message', $this->isEditing ? 
            'Unidad de medida actualizada correctamente.' : 
            'Unidad de medida creada correctamente.');
        
        $this->closeModal();
        $this->resetInputFields();
    }

    public function resetInputFields()
    {
        $this->unidadMedida_id = null;
        $this->nombre = '';
        $this->resetValidation();
    }

    public function confirmDelete($id)
    {
        $unidadMedida = UnidadMedida::findOrFail($id);
        $this->unidadMedida_id = $id;
        $this->unidadAEliminar = $unidadMedida->nombre;
        $this->confirmingDelete = true;
    }

    public function cancelDelete()
    {
        $this->confirmingDelete = false;
        $this->unidadMedida_id = null;
        $this->unidadAEliminar = '';
    }

    public function delete()
    {
        try {
            $unidadMedida = UnidadMedida::findOrFail($this->unidadMedida_id);
            
            // Verificar si tiene productos o requisiciones asociadas
            // Si tienes relaciones con otras tablas, debes verificarlas aquí
            // Por ejemplo:
            // if ($unidadMedida->productos()->count() > 0) {
            //    session()->flash('error', 'No se puede eliminar la unidad de medida porque tiene productos asociados.');
            //    $this->confirmingDelete = false;
            //    return;
            // }
            
            $unidadMedida->delete();
            session()->flash('message', 'Unidad de medida eliminada correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Ocurrió un error al eliminar la unidad de medida.');
        }
        
        $this->confirmingDelete = false;
        $this->unidadMedida_id = null;
    }

    public function render()
    {
        $unidadMedidas = UnidadMedida::query()
            ->when($this->search, function($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
            
        return view('livewire.requisicion.unidad-medidas', [
            'unidadMedidas' => $unidadMedidas
        ])->layout('layouts.app');
    }
}