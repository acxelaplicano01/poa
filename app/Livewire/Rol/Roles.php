<?php

namespace App\Livewire\Rol;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class Roles extends Component
{
    use WithPagination;
    public $isEditing = false;
    public $permissions;
    public $role;
    public $name;
    public $description;
    public $search = '';
    public $selectedPermissions = [];
    public $isOpen = false;
    public $confirmingDelete = false;
    public $IdAEliminar;
    public $nombreAEliminar;

    protected $rules = [
        'name' => 'required|unique:roles,name',
        'description' => 'required|string|max:255',
        'selectedPermissions' => 'required|array',
    ];

    public function mount()
    {
        $this->permissions = Permission::all();
    }

    public function render()
    {
        $roles = Role::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'DESC')
            ->paginate(5);

        return view('livewire.rol.roles', [
            'roles' => $roles
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isEditing = false;
        $this->permissions = Permission::all();
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|unique:roles,name' . ($this->role ? ',' . $this->role->id : ''),
            'description' => 'required|string|max:255',
            'selectedPermissions' => 'required|array',
        ]);

        if ($this->role) {
            $this->update();
        } else {
            $this->createRole();
        }
    }


    private function createRole()
    {
        try {
            $role = Role::create([
                'name' => $this->name,
                'description' => $this->description,
                'guard_name' => 'web'
            ]);

            $permissionIds = Permission::whereIn('id', $this->selectedPermissions)->pluck('id')->toArray();
            $role->syncPermissions($permissionIds);

            // Limpiar caché de permisos
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            // Si el usuario actual tiene este rol (caso de asignación inmediata), refrescar sesión
            $currentUser = auth()->user();
            if ($currentUser && $currentUser->hasRole($role->name)) {
                $currentUser->getPermissionsViaRoles();
                auth()->setUser($currentUser->fresh());
                session()->regenerate();
                $this->dispatch('user-permissions-updated');
            }

            session()->flash('message', 'Rol creado exitosamente.');
            $this->permissions = Permission::all();
            $this->resetInputFields();
            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear el rol: ' . $e->getMessage());
            Log::error('Error creating role: ' . $e->getMessage());
        }
    }

    public function edit(Role $role)
    {
        $this->role = $role;
        $this->name = $role->name;
        $this->description = $role->description;
        $this->selectedPermissions = $role->permissions->pluck('id')->toArray();
        $this->permissions = Permission::all();
        $this->isEditing = true;
        $this->isOpen = true;
    }

    public function update()
    {
        $validatedData = $this->validate([
            'name' => 'required',
            'description' => 'required|string|max:255',
            'selectedPermissions' => 'required|array',
            'selectedPermissions.*' => 'exists:permissions,id',
        ]);

        try {
            $role = Role::findOrFail($this->role->id);

            $role->update([
                'name' => $this->name,
                'description' => $this->description,
            ]);

            $permissionIds = Permission::whereIn('id', $validatedData['selectedPermissions'])->pluck('id')->toArray();
            $role->syncPermissions($permissionIds);

            // Limpiar caché de permisos
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            // Refrescar permisos para todos los usuarios con este rol
            $this->refreshUsersWithRole($role);

            session()->flash('message', 'Rol actualizado exitosamente');
            $this->resetInputFields();
            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar rol: ' . $e->getMessage());
            Log::error('Error updating role: ' . $e->getMessage());
        }
    }

    /**
     * Refresca los permisos para todos los usuarios que tienen este rol
     * y actualiza la sesión del usuario actual si le afecta el cambio
     */
    private function refreshUsersWithRole($role)
    {
        // Siempre limpiar caché de permisos en Spatie
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Comprobar si el usuario actual tiene este rol
        $currentUser = auth()->user();
        if ($currentUser && $currentUser->hasRole($role->name)) {
            // Refrescar el objeto del usuario para obtener los permisos actualizados
            $freshUser = $currentUser->fresh();
            auth()->setUser($freshUser);

            // Redirigir a la URL actual
            redirect()->route('roles');
        }
    }

    public function delete()
    {
        if ($this->confirmingDelete) {
            $role = Role::find($this->IdAEliminar);

            if (!$role) {
                session()->flash('error', 'Rol no encontrado.');
                $this->confirmingDelete = false;
                return;
            }

            try {
                $role->forceDelete();
                session()->flash('message', 'Rol eliminado correctamente!');
            } catch (\Exception $e) {
                session()->flash('error', 'Error al eliminar rol: ' . $e->getMessage());
                Log::error('Error deleting role: ' . $e->getMessage());
            }

            $this->confirmingDelete = false;
        }
    }

    public function confirmDelete($id)
    {
        $role = Role::find($id);

        if (!$role) {
            session()->flash('error', 'Rol no encontrado.');
            return;
        }

        if ($role->users()->exists()) {
            session()->flash('error', 'No se puede eliminar el rol: ' . $role->name . ', porque está enlazado a uno o más usuarios');
            return;
        }

        $this->IdAEliminar = $id;
        $this->nombreAEliminar = $role->name;
        $this->confirmingDelete = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->description = '';
        $this->selectedPermissions = [];
    }
}