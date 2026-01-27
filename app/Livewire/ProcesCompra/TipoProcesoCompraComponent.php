<?php

namespace App\Livewire\ProcesCompra;

use App\Models\ProcesoCompras\TipoProcesoCompra;
use Livewire\Component;
use Livewire\WithPagination;

class TipoProcesoCompraComponent extends Component
{
    use WithPagination;

    public $nombre;
    public $descripcion;
    public $monto_minimo = 0;
    public $monto_maximo;
    public $activo = true;
    public $tipoId;
    public $search = '';
    public $perPage = 10;
    public $sortField = 'monto_minimo';
    public $sortDirection = 'asc';
    public $isModalOpen = false;
    public $showDeleteModal = false;
    public $tipoToDelete;

    protected $rules = [
        'nombre' => 'required|min:3|max:100',
        'descripcion' => 'nullable|max:500',
        'monto_minimo' => 'required|numeric|min:0',
        'monto_maximo' => 'nullable|numeric|gt:monto_minimo',
        'activo' => 'boolean',
    ];

    protected $messages = [
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
        'monto_minimo.required' => 'El monto mínimo es obligatorio.',
        'monto_minimo.numeric' => 'El monto mínimo debe ser un número.',
        'monto_minimo.min' => 'El monto mínimo debe ser mayor o igual a 0.',
        'monto_maximo.numeric' => 'El monto máximo debe ser un número.',
        'monto_maximo.gt' => 'El monto máximo debe ser mayor que el monto mínimo.',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'monto_minimo'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function render()
    {
        $tipos = TipoProcesoCompra::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('descripcion', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.proces-compra.tipo-proceso-compras', [
            'tipos' => $tipos,
        ])->layout('layouts.app');
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function edit($id)
    {
        try {
            $tipo = TipoProcesoCompra::findOrFail($id);
            $this->tipoId = $tipo->id;
            $this->nombre = $tipo->nombre;
            $this->descripcion = $tipo->descripcion;
            $this->monto_minimo = $tipo->monto_minimo;
            $this->monto_maximo = $tipo->monto_maximo;
            $this->activo = $tipo->activo;
            $this->openModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al cargar el tipo de proceso: ' . $e->getMessage());
        }
    }

    public function store()
    {
        $this->validate();

        try {
            if ($this->tipoId) {
                // Actualizar
                $tipo = TipoProcesoCompra::findOrFail($this->tipoId);
                $tipo->update([
                    'nombre' => $this->nombre,
                    'descripcion' => $this->descripcion,
                    'monto_minimo' => $this->monto_minimo,
                    'monto_maximo' => $this->monto_maximo,
                    'activo' => $this->activo,
                ]);
                session()->flash('message', 'Tipo de proceso actualizado exitosamente.');
            } else {
                // Crear
                TipoProcesoCompra::create([
                    'nombre' => $this->nombre,
                    'descripcion' => $this->descripcion,
                    'monto_minimo' => $this->monto_minimo,
                    'monto_maximo' => $this->monto_maximo,
                    'activo' => $this->activo,
                ]);
                session()->flash('message', 'Tipo de proceso creado exitosamente.');
            }

            $this->closeModal();
            $this->resetInputFields();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->tipoToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            $tipo = TipoProcesoCompra::findOrFail($this->tipoToDelete);
            $tipo->delete();
            session()->flash('message', 'Tipo de proceso eliminado exitosamente.');
            $this->showDeleteModal = false;
            $this->tipoToDelete = null;
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar: ' . $e->getMessage());
            $this->showDeleteModal = false;
        }
    }

    public function toggleActivo($id)
    {
        try {
            $tipo = TipoProcesoCompra::findOrFail($id);
            $tipo->activo = !$tipo->activo;
            $tipo->save();
            session()->flash('message', 'Estado actualizado exitosamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al cambiar el estado: ' . $e->getMessage());
        }
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    public function resetInputFields()
    {
        $this->tipoId = null;
        $this->nombre = '';
        $this->descripcion = '';
        $this->monto_minimo = 0;
        $this->monto_maximo = null;
        $this->activo = true;
        $this->resetErrorBag();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }
}
