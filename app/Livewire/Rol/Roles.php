<?php

namespace App\Livewire\Rol;

use App\Services\LogService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class Roles extends Component
{
    use WithPagination;
    
    public $search = '';
    public $perPage = 10; // Número de roles por página
    public $showDeleteModal = false;
    public $errorMessage = '';
    public $showErrorModal = false;
    public $IdAEliminar;
    public $nombreAEliminar;

    // Campos para ordenamiento
    public $sortField = 'name';
    public $sortDirection = 'asc';

    public function render()
    {
        $query = Role::query();
        
        // Aplicar búsqueda
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }
        
        // Aplicar ordenamiento
        $query->orderBy($this->sortField, $this->sortDirection);
        
        // Obtener resultados paginados
        $roles = $query->paginate($this->perPage ?? 10);

        return view('livewire.rol.roles', [
            'roles' => $roles
        ])->layout('layouts.app');
    }

    public function confirmDelete($id)
    {
        $role = Role::findOrFail($id);
        $this->IdAEliminar = $id;
        $this->nombreAEliminar = $role->name;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            $role = Role::findOrFail($this->IdAEliminar);
            $roleName = $role->name;
            
            // Verificar si el rol tiene usuarios asignados
            if ($role->users()->count() > 0) {
                $this->showError('No se puede eliminar el rol porque tiene usuarios asignados.');
                $this->showDeleteModal = false;
                return;
            }

            $role->delete();

            // Log de la actividad
            LogService::activity(
                'eliminar',
                'roles',
                'Rol eliminado: ' . $roleName,
                [
                    'role_id' => $this->IdAEliminar,
                    'role_name' => $roleName,
                ]
            );

            session()->flash('message', 'Rol eliminado exitosamente.');
            $this->showDeleteModal = false;
            $this->IdAEliminar = null;
            $this->nombreAEliminar = null;

        } catch (\Exception $e) {
            Log::error('Error al eliminar rol: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'role_id' => $this->IdAEliminar,
                'exception' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            $this->showError('Error al eliminar el rol. Por favor, inténtelo de nuevo.');
            $this->showDeleteModal = false;
        }
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->IdAEliminar = null;
        $this->nombreAEliminar = null;
    }

    // Mostrar error en modal
    public function showError($message)
    {
        $this->errorMessage = $message;
        $this->showErrorModal = true;
    }

    // Ocultar modal de error
    public function hideError()
    {
        $this->showErrorModal = false;
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }
}
