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
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
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
    public $errorMessage = '';
    public $showErrorModal = false;
    public $IdAEliminar;
    public $nombreAEliminar;
    public $profile_photo_path;
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $idEmpleado = null;

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
            ->with(['roles', 'empleado'])  // Pre-cargar relación de roles y empleado para mejor rendimiento
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
        
        // Obtener lista de empleados para el select
        // Excluir empleados que ya tienen usuario asignado (excepto el actual al editar)
        $empleados = \App\Models\Empleados\Empleado::orderBy('nombre')->orderBy('apellido')
            ->whereDoesntHave('user', function($query) {
                // Si estamos editando, permitir el empleado actual
                if ($this->user && $this->user->idEmpleado) {
                    $query->where('empleados.id', '!=', $this->user->idEmpleado);
                }
            })
            ->orWhere('id', $this->user->idEmpleado ?? null) // Incluir el empleado actual si existe
            ->get();

        return view('livewire.Usuario.usuarios', [
            'users' => $users,
            'roles' => $this->roles,
            'empleados' => $empleados
        ]);
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
            'idEmpleado' => 'nullable|exists:empleados,id',
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
                'idEmpleado' => $this->idEmpleado,
            ]);


            $roleIds = Role::whereIn('id', $this->selectedRoles)->pluck('id')->toArray();

            $user->syncRoles($roleIds);
            // Registrar log de creación exitosa
            LogService::activity(
                'crear',
                'Configuración',
                "Se creó el usuario {$user->name}",
                [
                    'Creado por' => Auth::user()->name . ' ' . '(' . Auth::user()->email . ')',
                    'Usuario Creado' => $user->name . ' (' . $user->email . ')',
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
                    'input_nombre' => $this->name,
                    'input_Correo' => $this->email,
                    'input_profile_photo_path' => $this->profile_photo_path,
                    'input_password' => $this->password,
                    'selectedRoles' => $this->selectedRoles,
                    'Creado por' => Auth::user()->name . ' ' . '(' . Auth::user()->email . ')',
                    'intentó_cambios' => [
                        'nombre' => $this->name,
                        'Correo' => $this->email,
                        'profile_photo_path' => $this->profile_photo_path,
                    ],
                    'error' => $e->getMessage(),
                ],
                'error'
            );
            $this->showError('Error al crear el usuario: ' . $e->getMessage());
        }
    }



    public function edit(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->profile_photo_path = $user->profile_photo_path;
        $this->idEmpleado = $user->idEmpleado;
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
            'idEmpleado' => 'nullable|exists:empleados,id',
        ]);

        try {
            $user = User::findOrFail($this->user->id);
            $oldData = $this->user->only(['name', 'email', 'profile_photo_path', 'idEmpleado']);

            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'profile_photo_path' => $this->profile_photo_path,
                'password' => $this->password ? Hash::make($this->password) : $user->password,
                'idEmpleado' => $this->idEmpleado,
            ]);

            // Registrar log de actualización
            LogService::activity(
                'actualizar',
                'Configuración',
                "Se actualizó el usuario {$this->user->name}",
                [
                    'Actualizado por' => Auth::user()->name . ' ' . '(' . Auth::user()->email . ')',
                    'cambios' => [
                        'anteriores' => $oldData,
                        'nuevos' => $user->only(['name', 'email', 'profile_photo_path', 'idEmpleado']),
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
                    'ID Usuario' => $this->user->id,
                    'intentó_cambios' => [
                        'nombre' => $this->name,
                        'Correo' => $this->email,
                        'profile_photo_path' => $this->profile_photo_path,
                    ],
                    'error' => $e->getMessage(),
                ],
                'error'
            );
            $this->showError('Error al actualizar el usuario: ' . $e->getMessage());
        }
    }

    public function delete()
    {
        try {
            $user = User::find($this->IdAEliminar);

            if (!$user) {
                $this->showError('Usuario no encontrado.');
                $this->closeDeleteModal();
                return;
            }

            $user->forceDelete();
            // Registrar log de eliminación
            LogService::activity(
                'eliminar',
                'Configuración',
                "Se eliminó el usuario {$user->name}",
                [
                    'Eliminado por' => Auth::user()->name . ' ' . '(' . Auth::user()->email . ')',
                    'Usuario eliminado' => $user->name . ' (' . $user->email . ')',
                    'ID Usuario' => $user->id,
                ]
            );
            // Limpiar caché de permisos
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            session()->flash('message', 'usuario eliminado correctamente!');
            $this->closeDeleteModal();
        } catch (\Exception $e) {
            LogService::activity(
                'eliminar',
                'Configuración',
                'Error al eliminar usuario',
                [
                    'Intento de eliminar por' => Auth::user()->name . ' ' . '(' . Auth::user()->email . ')',
                    'Usuario' => $this->nombreAEliminar,
                    'error' => $e->getMessage(),
                ],
                'error'
            );
            $this->showError('Error al eliminar el usuario: ' . $e->getMessage());
            $this->closeDeleteModal();
        }
    }

    public function confirmDelete($id)
    {
        $user = User::find($id);

        if (!$user) {
            $this->showError('Usuario no encontrado.');
            return;
        }

        $this->IdAEliminar = $id;
        $this->nombreAEliminar = $user->name . ' ' . $user->email;
        $this->showDeleteModal = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function closeDeleteModal()
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
