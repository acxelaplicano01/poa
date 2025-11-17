<?php

namespace App\Livewire\Empleado;

use App\Models\Departamento\Departamento;
use App\Models\Empleados\Empleado;
use App\Models\UnidadEjecutora\UnidadEjecutora;
use App\Services\LogService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Log;
use Auth;

class Empleados extends Component
{
    use WithPagination;

    // Propiedades para control de UI
    public $isOpen = false;
    public $isEditing = false;
    public $showDeleteModal = false;
    public $errorMessage = '';
    public $showErrorModal = false;
    public $perPage = 10; //numero de paginas por empleados
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
    public $unidadesEjecutoras = [];
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
            'sexo' => 'required|string|max:10|in:M,F', // M para Masculino, F para Femenino
            'selectedDepartamentos' => 'required|array|min:1',
            'idUnidadEjecutora' => 'required|exists:unidad_ejecutora,id', 
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
            'sexo.required' => 'El campo sexo es obligatorio',
            'sexo.in' => 'El valor seleccionado para sexo no es válido', 
            'idUnidadEjecutora.required' => 'Debe seleccionar una unidad ejecutora',
            'idUnidadEjecutora.exists' => 'La unidad ejecutora seleccionada no es válida',
        ];
    }

    // Inicialización del componente
    public function mount()
    {
        $this->loadUnidadesEjecutoras();
        $this->loadDepartamentos();
    }

    // Cargar unidades ejecutoras disponibles
    public function loadUnidadesEjecutoras()
    {
        $this->unidadesEjecutoras = UnidadEjecutora::select('id', 'name')->orderBy('name')->get()->toArray();
    }

    // Cargar departamentos disponibles (filtrados por unidad ejecutora)
    public function loadDepartamentos()
    {
        if ($this->idUnidadEjecutora) {
            $this->departamentos = Departamento::where('idUnidadEjecutora', $this->idUnidadEjecutora)
                ->select('id', 'name')
                ->orderBy('name')
                ->get()
                ->toArray();
        } else {
            $this->departamentos = [];
        }
    }

    // Listener para cuando cambie la unidad ejecutora
    public function updatedIdUnidadEjecutora()
    {
        $this->selectedDepartamentos = []; // Limpiar departamentos seleccionados
        $this->loadDepartamentos(); // Cargar departamentos de la nueva UE
    }

    // Renderizar la vista
    public function render()
    {
        // Obtener UE del usuario autenticado
        $user = auth()->user();
        $userUE = $user->empleado?->idUnidadEjecutora;

        $query = Empleado::query()
            ->with('departamentos')
            // Filtrar por UE del usuario
            ->when($userUE, function ($query) use ($userUE) {
                $query->where('idUnidadEjecutora', $userUE);
            })
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('apellido', 'like', '%' . $this->search . '%')
                      ->orWhere('num_empleado', 'like', '%' . $this->search . '%')
                      ->orWhere('dni', 'like', '%' . $this->search . '%')
                      ->orWhereHas('departamentos', function($subq) {
                          $subq->where('name', 'like', '%' . $this->search . '%');
                      });
                });
            });
        
        // Manejo de ordenamiento para campos complejos o relaciones
        if ($this->sortField === 'nombre') {
            $query->orderBy('nombre', $this->sortDirection)
                  ->orderBy('apellido', $this->sortDirection);
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }
        
        $empleados = $query->paginate($this->perPage);
        
        // Filtrar UEs disponibles (solo la del usuario)
        $unidadesEjecutoras = $userUE 
            ? UnidadEjecutora::where('id', $userUE)->orderBy('name')->get()
            : UnidadEjecutora::orderBy('name')->get();
        
        return view('livewire.empleado.empleado', [
            'empleados' => $empleados,
            'unidadesEjecutoras' => $unidadesEjecutoras,
        ])->layout('layouts.app');
    }

    // Abrir modal para crear
    public function create()
    {
        $this->isEditing = false;
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
            $this->fechaNacimiento = $empleado->fechaNacimiento;
            $this->direccion = $empleado->direccion;
            $this->idUnidadEjecutora = $empleado->idUnidadEjecutora; // Cargar unidad ejecutora
            
            // Obtener los departamentos asociados
            $this->selectedDepartamentos = $empleado->departamentos()->pluck('departamentos.id')->toArray();
            
            // Cargar departamentos de la unidad ejecutora seleccionada
            $this->loadDepartamentos();
            
            $this->openModal();
        } catch (\Exception $e) {
            LogService::activity(
                'actualizar',
                'Configuración',
                'Error al cargar empleado para edición',
                [
                    'Actualizado por' =>  Auth::user()->name . ' (' . Auth::user()->email . ')',
                    'Empleado' => $empleado->nombre . ' ' . $empleado->apellido . ' (ID: ' . $empleado->id . ')',
                    'error' => $e->getMessage(),
                ],
                'error'
            );
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
                    'fechaNacimiento' => $this->fechaNacimiento,
                    'direccion' => $this->direccion,
                    'sexo' => $this->sexo, 
                    'idUnidadEjecutora' => $this->idUnidadEjecutora,
                ]);
                
                // Sincronizar departamentos
                $empleado->departamentos()->sync($this->selectedDepartamentos);
                
                $accion = 'actualizado';
                LogService::activity(
                    'actualizar',
                    'Configuración',
                    "Empleado {$accion} correctamente",
                    [
                        'Actualizado por' =>  Auth::user()->name . ' (' . Auth::user()->email . ')',
                        'Empleado' => $empleado->nombre . ' ' . $empleado->apellido . ' (ID: ' . $empleado->id . ')',
                        'departamentos' => $this->selectedDepartamentos,
                    ]
                );
            } else {
                // Crear nuevo empleado
                $empleado = Empleado::create([
                    'dni' => $this->dni,
                    'num_empleado' => $this->num_empleado,
                    'nombre' => $this->nombre,
                    'apellido' => $this->apellido,
                    'telefono' => $this->telefono,
                    'fechaNacimiento' => $this->fechaNacimiento,
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
            LogService::activity(
                'crear',
                'Configuración',
                "Empleado {$accion} correctamente",
                [
                    'Creado por' =>  Auth::user()->name . ' (' . Auth::user()->email . ')',
                    'action' => $accion,
                    'Empleado' => $empleado->nombre . ' ' . $empleado->apellido . ' (ID: ' . $empleado->id . ')',
                    'departamentos' => $this->selectedDepartamentos,
                ]
            );
            session()->flash('message', "Empleado {$accion} correctamente con " . count($this->selectedDepartamentos) . " departamento(s).");
        } catch (\Exception $e) {
            DB::rollBack();
            LogService::activity(
                'crear',
                'Configuración',
                'Error al crear empleado',
                [
                    'Nombre' => $this->nombre,
                    'Apellido' => $this->apellido,
                    'error' => $e->getMessage(),
                ],
                'error'
            );
            $this->showError('Error al guardar el empleado: ' . $e->getMessage());
        }
    }

    // Confirmar eliminación
    public function confirmDelete($id)
    {
        $empleado = Empleado::findOrFail($id);
        $this->empleadoToDelete = $empleado;
        $this->showDeleteModal = true;
    }

    // Eliminar empleado
    public function delete()
    {
        try {
            $empleado = $this->empleadoToDelete;
            
            // Eliminar relaciones primero
            $empleado->departamentos()->detach();
            
            // Luego eliminar el empleado
            $empleado->delete();
            
            $this->closeDeleteModal();
            LogService::activity(
                'eliminar',
                'Configuración',
                "Empleado eliminado correctamente",
                [
                    'Eliminado por' => Auth::user()->name . ' (' . Auth::user()->email . ')',
                    'Empleado' => $empleado->nombre . ' ' . $empleado->apellido . ' (ID: ' . $empleado->id . ')',
                ]
            );
            session()->flash('message', 'Empleado eliminado correctamente.');
        } catch (\Exception $e) {
            LogService::activity(
                'eliminar',
                'Configuración',
                'Error al eliminar empleado',
                [
                    'Intentó eliminarlo' => Auth::user()->name . ' (' . Auth::user()->email . ')',
                    'Empleado' => $this->empleadoToDelete ? $this->empleadoToDelete->nombre . ' ' . $this->empleadoToDelete->apellido . ' (ID: ' . $this->empleadoToDelete->id . ')' : 'Desconocido',
                    'error' => $e->getMessage(),
                ],
                'error'
            );
            $this->showError('Error al eliminar el empleado: ' . $e->getMessage());
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->empleadoToDelete = null;
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

     // Método para ordenar por columna
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