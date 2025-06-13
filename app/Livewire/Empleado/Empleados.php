<?php

namespace App\Livewire\Empleado;

use App\Models\Departamento\Departamentos;
use App\Models\Empleados\Empleado;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Empleados extends Component
{
    use WithPagination;

    // Propiedades para control de UI
    public $isOpen = false;
    public $isEditing = false;
    public $confirmingDelete = false;
    public $errorMessage = '';
    public $showErrorModal = false;
    public $perPage = 10;
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';

    // Propiedades para datos del formulario
    public $empleado_id;
    public $dni;
    public $num_empleado;
    public $nombre;
    public $apellido;
    public $sexo; 
    public $telefono;
    public $fechaNacimiento;
    public $direccion;
    public $selectedDepartamentos = [];
    public $departamentos = [];
    public $empleadoToDelete;
    public $idUnidadEjecutora;

    // Reglas de validación
    protected function rules()
    {
        $idRule = $this->empleado_id ? ','.$this->empleado_id : '';
        
        return [
            'dni' => 'required|string|max:20|unique:empleados,dni'.$idRule,
            'num_empleado' => 'required|string|max:20|unique:empleados,num_empleado'.$idRule,
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'telefono' => 'required|string|max:20',
            'fechaNacimiento' => 'required|date',
            'direccion' => 'required|string|max:255',
            'sexo' => 'required|string|max:10', 
            'selectedDepartamentos' => 'required|array|min:1',
            'idUnidadEjecutora' => 'nullable|exists:unidad_ejecutora,id', 
        ];
    }

    // Mensajes personalizados de validación
    protected function messages()
    {
        return [
            'dni.required' => 'El DNI es obligatorio',
            'dni.unique' => 'Este DNI ya está registrado',
            'num_empleado.required' => 'El número de empleado es obligatorio',
            'num_empleado.unique' => 'Este número de empleado ya está registrado',
            'nombre.required' => 'El nombre es obligatorio',
            'apellido.required' => 'El apellido es obligatorio',
            'telefono.required' => 'El teléfono es obligatorio',
            'fechaNacimiento.required' => 'La fecha de nacimiento es obligatoria',
            'fechaNacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida',
            'direccion.required' => 'La dirección es obligatoria',
            'selectedDepartamentos.required' => 'Debe seleccionar al menos un departamento',
            'selectedDepartamentos.min' => 'Debe seleccionar al menos un departamento',
            'sexo.required' => 'El sexo es obligatorio',  
            'idUnidadEjecutora.required' => 'Debe seleccionar una unidad ejecutora',
            'idUnidadEjecutora.exists' => 'La unidad ejecutora seleccionada no es válida',
        ];
    }

    // Inicialización del componente
    public function mount()
    {
        $this->idUnidadEjecutora = 1;
        $this->loadDepartamentos();
    }

    // Cargar departamentos disponibles
    public function loadDepartamentos()
    {
        $this->departamentos = Departamentos::select('id', 'name')->orderBy('name')->get()->toArray();
    }

    // Renderizar la vista
    public function render()
    {
        $empleados = Empleado::where(function($query) {
                if ($this->search) {
                    $query->where('nombre', 'like', '%'.$this->search.'%')
                        ->orWhere('apellido', 'like', '%'.$this->search.'%')
                        ->orWhere('dni', 'like', '%'.$this->search.'%')
                        ->orWhere('num_empleado', 'like', '%'.$this->search.'%');
                }
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.empleado.empleado', [
            'empleados' => $empleados,
        ])->layout('layouts.app');
    }

    // Abrir modal para crear
    public function create()
    {
        $this->isEditing = false;
        $this->idUnidadEjecutora = 1;
        $this->resetInputFields();
        $this->openModal();
    }

    // Abrir modal para editar
    public function edit($id)
    {
        try {
            $this->isEditing = true;
            $this->empleado_id = $id;
            
            $empleado = Empleado::findOrFail($id);
            
            $this->dni = $empleado->dni;
            $this->num_empleado = $empleado->num_empleado;
            $this->nombre = $empleado->nombre;
            $this->apellido = $empleado->apellido;
            $this->sexo = $empleado->sexo; 
            $this->telefono = $empleado->telefono;
            $this->fechaNacimiento = $empleado->fecha_nacimiento;
            $this->direccion = $empleado->direccion;
            $this->idUnidadEjecutora = $empleado->idUnidadEjecutora; // Cargar unidad ejecutora
            
            // Obtener los departamentos asociados
            $this->selectedDepartamentos = $empleado->departamentos()->pluck('departamentos.id')->toArray();
            
            $this->openModal();
        } catch (\Exception $e) {
            $this->showError('Error al cargar el empleado: ' . $e->getMessage());
        }
    }

    // Guardar o actualizar empleado
    public function store()
    {
        try {
            $validatedData = $this->validate();
            
            DB::beginTransaction();
            
            if ($this->isEditing) {
                // Actualizar empleado existente
                $empleado = Empleado::findOrFail($this->empleado_id);
                $empleado->update([
                    'dni' => $this->dni,
                    'num_empleado' => $this->num_empleado,
                    'nombre' => $this->nombre,
                    'apellido' => $this->apellido,
                    'telefono' => $this->telefono,
                    'fecha_nacimiento' => $this->fechaNacimiento,
                    'direccion' => $this->direccion,
                    'sexo' => $this->sexo, 
                    'idUnidadEjecutora' => $this->idUnidadEjecutora,
                ]);
                
                // Sincronizar departamentos
                $empleado->departamentos()->sync($this->selectedDepartamentos);
                
                $accion = 'actualizado';
            } else {
                // Crear nuevo empleado
                $empleado = Empleado::create([
                    'dni' => $this->dni,
                    'num_empleado' => $this->num_empleado,
                    'nombre' => $this->nombre,
                    'apellido' => $this->apellido,
                    'telefono' => $this->telefono,
                    'fecha_nacimiento' => $this->fechaNacimiento,
                    'direccion' => $this->direccion,
                    'sexo' => $this->sexo,  
                    'idUnidadEjecutora' => $this->idUnidadEjecutora,
                ]);
                // Asociar departamentos
                $empleado->departamentos()->attach($this->selectedDepartamentos);
                
                $accion = 'creado';
            }
            
            DB::commit();
            
            $this->closeModal();
            
            session()->flash('message', "Empleado {$accion} correctamente con " . count($this->selectedDepartamentos) . " departamento(s).");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->showError('Error al guardar el empleado: ' . $e->getMessage());
        }
    }

    // Confirmar eliminación
    public function confirmDelete($id)
    {
        $this->empleadoToDelete = $id;
        $this->confirmingDelete = true;
    }

    // Eliminar empleado
    public function delete()
    {
        try {
            $empleado = Empleado::findOrFail($this->empleadoToDelete);
            
            // Eliminar relaciones primero
            $empleado->departamentos()->detach();
            
            // Luego eliminar el empleado
            $empleado->delete();
            
            $this->confirmingDelete = false;
            session()->flash('message', 'Empleado eliminado correctamente.');
        } catch (\Exception $e) {
            $this->showError('Error al eliminar el empleado: ' . $e->getMessage());
        }
    }

    // Añadir departamento a la selección
    public function addDepartamento($departamentoId)
    {
        if (!in_array($departamentoId, $this->selectedDepartamentos)) {
            $this->selectedDepartamentos[] = $departamentoId;
        }
    }

    // Eliminar departamento de la selección
    public function removeDepartamento($index)
    {
        if (isset($this->selectedDepartamentos[$index])) {
            unset($this->selectedDepartamentos[$index]);
            $this->selectedDepartamentos = array_values($this->selectedDepartamentos);
        }
    }

    // Ordenar resultados
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    // Reiniciar paginación al buscar
    public function updatingSearch()
    {
        $this->resetPage();
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

    // Abrir modal
    public function openModal()
    {
        $this->isOpen = true;
    }

    // Cerrar modal
    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetValidation();
    }

    // Reiniciar campos del formulario
    public function resetInputFields()
    {
        $this->empleado_id = null;
        $this->dni = '';
        $this->num_empleado = '';
        $this->nombre = '';
        $this->apellido = '';
        $this->sexo = ''; 
        $this->telefono = '';
        $this->fechaNacimiento = '';
        $this->direccion = '';
        $this->selectedDepartamentos = [];
        $this->idUnidadEjecutora = null;
        $this->resetValidation();
    }
}