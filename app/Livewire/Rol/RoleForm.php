<?php

namespace App\Livewire\Rol;

use App\Services\LogService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Exceptions\RoleAlreadyExists;
use Illuminate\Support\Facades\Log;

class RoleForm extends Component
{
    public $roleId;
    public $isEditing = false;
    public $permissions;
    public $role;
    public $name;
    public $description;
    public $selectedPermissions = [];

    protected $rules = [
        'name' => 'required|string|max:255|unique:roles,name',
        'description' => 'required|string|max:255',
        'selectedPermissions' => 'required|array',
    ];

    protected $messages = [
        'name.required' => 'El nombre del rol es obligatorio.',
        'name.max' => 'El nombre del rol no puede tener más de 255 caracteres.',
        'name.unique' => 'Ya existe un rol con este nombre.',
        'description.required' => 'La descripción del rol es obligatoria.',
        'description.max' => 'La descripción no puede tener más de 255 caracteres.',
        'selectedPermissions.required' => 'Debe seleccionar al menos un permiso.',
        'selectedPermissions.array' => 'Los permisos seleccionados no son válidos.',
    ];

    public function mount($roleId = null)
    {
        $this->permissions = Permission::all();
        
        if ($roleId) {
            $this->roleId = $roleId;
            $this->isEditing = true;
            $this->loadRole();
        }
    }

    public function loadRole()
    {
        $this->role = Role::with('permissions')->findOrFail($this->roleId);
        $this->name = $this->role->name;
        $this->description = $this->role->description;
        $this->selectedPermissions = $this->role->permissions->pluck('id')->toArray();
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'name' && $this->isEditing) {
            $this->rules['name'] = 'required|string|max:255|unique:roles,name,' . $this->roleId;
        }
        
        $this->validateOnly($propertyName);
    }

    public function store()
    {
        // Debug: log que se está ejecutando el método
        Log::info('RoleForm::store() - Método ejecutándose', [
            'name' => $this->name,
            'description' => $this->description,
            'isEditing' => $this->isEditing,
            'selectedPermissions_count' => count($this->selectedPermissions)
        ]);

        // Ajustar reglas de validación para edición
        if ($this->isEditing) {
            $this->rules['name'] = 'required|string|max:255|unique:roles,name,' . $this->roleId;
        }

        $this->validate();

        try {
            // Obtener permisos con el guard correcto
            $selectedPermissions = Permission::where('guard_name', 'web')
                ->whereIn('id', $this->selectedPermissions)
                ->get();

            if ($selectedPermissions->count() !== count($this->selectedPermissions)) {
                // Algunos permisos no se encontraron con el guard 'web'
                $missingIds = collect($this->selectedPermissions)->diff($selectedPermissions->pluck('id'));
                Log::warning('Algunos permisos no se encontraron con guard web: ' . $missingIds->implode(','));
                
                // Intentar encontrar los permisos con cualquier guard y cambiarlos
                $missingPermissions = Permission::whereIn('id', $missingIds)->get();
                foreach ($missingPermissions as $permission) {
                    $permission->update(['guard_name' => 'web']);
                }
                
                // Volver a obtener todos los permisos
                $selectedPermissions = Permission::where('guard_name', 'web')
                    ->whereIn('id', $this->selectedPermissions)
                    ->get();
            }

            if ($this->isEditing) {
                // Actualizar rol existente
                $this->role->update([
                    'name' => $this->name,
                    'description' => $this->description,
                ]);

                // Sincronizar permisos
                $this->role->syncPermissions($selectedPermissions);

                // Log de la actividad
                LogService::activity(
                    'actualizar',
                    'roles',
                    'Rol actualizado: ' . $this->name,
                    [
                        'role_id' => $this->role->id,
                        'name' => $this->name,
                        'description' => $this->description,
                        'permissions_count' => count($this->selectedPermissions),
                    ]
                );

                session()->flash('message', 'Rol actualizado exitosamente.');
            } else {
                // Crear nuevo rol
                $role = Role::create([
                    'name' => $this->name,
                    'description' => $this->description,
                    'guard_name' => 'web', // Especificar el guard correcto
                ]);

                // Asignar permisos
                $role->syncPermissions($selectedPermissions);

                // Log de la actividad
                LogService::activity(
                    'crear',
                    'roles',
                    'Rol creado: ' . $this->name,
                    [
                        'role_id' => $role->id,
                        'name' => $this->name,
                        'description' => $this->description,
                        'permissions_count' => count($this->selectedPermissions),
                    ]
                );

                session()->flash('message', 'Rol creado exitosamente.');
            }

            return redirect()->route('roles');

        } catch (RoleAlreadyExists $e) {
            // Error específico para rol duplicado
            $this->addError('name', 'Ya existe un rol con este nombre.');
            
        } catch (\Exception $e) {
            Log::error('Error al ' . ($this->isEditing ? 'actualizar' : 'crear') . ' rol: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'role_data' => [
                    'name' => $this->name,
                    'description' => $this->description,
                    'permissions' => $this->selectedPermissions
                ],
                'exception' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            session()->flash('error', 'Error al ' . ($this->isEditing ? 'actualizar' : 'crear') . ' el rol: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('roles');
    }

    public function render()
    {
        return view('livewire.rol.form')
            ->layout('layouts.app');
    }
}
