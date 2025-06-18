<?php

namespace App\Livewire\Usuario;

use App\Services\LogService;
use Auth;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class Usuarios extends Component
{
    use WithPagination;
    public $isEditing = false;
    public $name;
    public $email;
    public $password;
    public $user;
    public $search = '';
    public $perPage = 10; // Número de usuarios por página
    public $selectedRoles = [];
    public $roles;
    public $isOpen = false;
    public $showDeleteModal = false;
    public $confirmingDelete = false;
    public $IdAEliminar;
    public $nombreAEliminar;
    public $profile_photo_path;
    public $sortField = 'id';
    public $sortDirection = 'asc';

    protected $rules = [
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8',
        'selectedRoles' => 'required|array',
        'selectedRoles.*' => 'exists:roles,id',
    ];

    protected $listeners = ['userStored' => '$refresh'];

    public function mount()
    {
        $this->roles = Role::all();
    }

    public function render()
    {
        $query = User::query()
            ->with('roles')  // Pre-cargar relación de roles para mejor rendimiento
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            });

        // Aplicar ordenamiento dinámico    
        $query->orderBy($this->sortField, $this->sortDirection);

        // Obtener usuarios paginados
        $users = $query->paginate($this->perPage ?? 10);

        return view('livewire.Usuario.usuarios', [
            'users' => $users,
            'roles' => $this->roles
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->roles = Role::all();
        $this->isOpen = true;
        $this->isEditing = false;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email' . ($this->user ? ',' . $this->user->id : ''),
            'profile_photo_path' => 'nullable',
            'password' => 'nullable|min:8',
            'selectedRoles' => 'required|array',
            'selectedRoles.*' => 'exists:roles,id',
        ]);

        if ($this->user) {
            $this->update();
        } else {
            $this->createUser();
        }
    }

    private function createUser()
    {
        $this->validate();
        try {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'profile_photo_path' => $this->profile_photo_path,
                'password' => Hash::make($this->password),
            ]);


            $roleIds = Role::whereIn('id', $this->selectedRoles)->pluck('id')->toArray();

            $user->syncRoles($roleIds);
            // Registrar log de creación exitosa
            LogService::activity(
                'crear',
                'Configuración',
                "Se creó el usuario {$user->name}",
                [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    // No incluir password por seguridad
                ]
            );
            session()->flash('message', 'Usuario creado exitosamente.');
            $this->roles = Role::all();
            $this->reset();
            $this->isOpen = false;
        } catch (\Exception $e) {
            // Registrar log de error
            LogService::activity(
                'crear',
                'Configuración',
                'Error al crear usuario',
                [
                    'input_name' => $this->name,
                    'input_email' => $this->email,
                    'error' => $e->getMessage(),
                ],
                'error'
            );
            session()->flash('error', 'Error al crear el usuario: ' . $e->getMessage());
        }
    }



    public function edit(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->profile_photo_path = $user->profile_photo_path;
        $this->selectedRoles = $user->roles->pluck('id')->toArray();
        $this->roles = Role::all();
        $this->isOpen = true;
        $this->isEditing = true;
    }

    public function update()
    {
        $validatedData = $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'profile_photo_path' => 'nullable',
            'password' => 'nullable|min:8',
            'selectedRoles' => 'required|array',
            'selectedRoles.*' => 'exists:roles,id',
        ]);

        try {
            $user = User::findOrFail($this->user->id);
            $oldData = $this->user->only(['name', 'email']);

            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'profile_photo_path' => $this->profile_photo_path,
                'password' => $this->password ? Hash::make($this->password) : $user->password,
            ]);

            // Registrar log de actualización
            LogService::activity(
                'actualizar',
                'Configuración',
                "Se actualizó el usuario {$this->user->name}",
                [
                    'user_id' => $this->user->id,
                    'changes' => [
                        'old' => $oldData,
                        'new' => $user->only(['name', 'email', 'profile_photo_path']),
                    ]
                ]
            );

            $roleIds = Role::whereIn('id', $validatedData['selectedRoles'])->pluck('id')->toArray();


            $user->syncRoles($roleIds);

            session()->flash('message', 'Usuario actualizado.');
            $this->roles = Role::all();
            $this->reset();
            $this->closeModal();
            // Redirigir a la URL login
            redirect()->route('usuarios');

        } catch (\Exception $e) {
            // Registrar log de error en actualización
            LogService::activity(
                'actualizar',
                'Configuración',
                'Error al actualizar usuario',
                [
                    'user_id' => $this->user->id,
                    'attempted_changes' => [
                        'name' => $this->name,
                        'email' => $this->email,
                        'profile_photo_path' => $this->profile_photo_path,
                    ],
                    'error' => $e->getMessage(),
                ],
                'error'
            );
            session()->flash('error', 'Error al actualizar el usuario: ' . $e->getMessage());
        }
    }

    public function delete()
    {
        try {
            // Verificar si se está confirmando la eliminación
            if ($this->confirmingDelete) {
                $user = User::find($this->IdAEliminar);

                if (!$user) {
                    session()->flash('error', 'Usuario no encontrado.');
                    $this->confirmingDelete = false;
                    return;
                }

                $user->forceDelete();
                // Registrar log de eliminación
                LogService::activity(
                    'eliminar',
                    'Configuración',
                    "Se eliminó el usuario {$user->name}",
                    [
                        'deleted_user_id' => $user->id,
                        'deleted_user_email' => $user->email,
                    ]
                );
                // Limpiar caché de permisos
                app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
                // Refrescar permisos para todos los usuarios con este rol

                session()->flash('message', 'usuario eliminado correctamente!');
                $this->confirmingDelete = false;
            }
        } catch (\Exception $e) {
            LogService::activity(
                'eliminar',
                'Configuración',
                'Error al eliminar usuario',
                [
                    'user_id' => $this->IdAEliminar,
                    'deleted_by' => Auth::user()->email,
                    'error' => $e->getMessage(),
                ],
                'error'
            );
            session()->flash('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $user = User::find($id);

        if (!$user) {
            session()->flash('error', 'usuario no encontrado.');
            return;
        }

        $this->IdAEliminar = $id;
        $this->nombreAEliminar = $user->name . ' ' . $user->email;
        $this->confirmingDelete = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->profile_photo_path = '';
        $this->password = '';
        $this->selectedRoles = [];
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
}
