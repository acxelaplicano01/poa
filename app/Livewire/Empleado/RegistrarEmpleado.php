<?php

namespace App\Livewire\Empleado;

use Livewire\Component;
use App\Models\Empleados\Empleado;
use App\Models\UnidadEjecutora\UnidadEjecutora;
use App\Models\Departamento\Departamento;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('layouts.guest')]
class RegistrarEmpleado extends Component
{
    // Campos del formulario
    public $dni;
    public $num_empleado;
    public $nombre;
    public $apellido;
    public $direccion;
    public $telefono;
    public $fechaNacimiento;
    public $sexo = 'M';
    public $idUnidadEjecutora;
    public $empleado_id;
    public $selectedDepartamentos = [];

    // Listas para selects
    public $unidadesEjecutoras = [];
    public $departamentos = [];

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

    public function mount()
    {
        // Cargar unidades ejecutoras
        $this->loadUnidadesEjecutoras();
        
        // Los departamentos se cargarán cuando se seleccione una UE
        $this->departamentos = [];

        // Prellenar datos del usuario si están disponibles
        $user = auth()->user();
        $this->nombre = explode(' ', $user->name)[0] ?? '';
        $this->apellido = substr($user->name, strlen($this->nombre) + 1) ?? '';
    }

    // Cargar unidades ejecutoras disponibles
    public function loadUnidadesEjecutoras()
    {
        $this->unidadesEjecutoras = UnidadEjecutora::select('id', 'name')
            ->orderBy('name')
            ->get()
            ->toArray();
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

    public function addDepartamento($departamentoId)
    {
        if (!in_array($departamentoId, $this->selectedDepartamentos)) {
            $this->selectedDepartamentos[] = $departamentoId;
        }
    }

    public function removeDepartamento($index)
    {
        unset($this->selectedDepartamentos[$index]);
        $this->selectedDepartamentos = array_values($this->selectedDepartamentos);
    }

    public function guardar()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Crear el empleado
            $empleado = Empleado::create([
                'dni' => $this->dni,
                'num_empleado' => $this->num_empleado,
                'nombre' => $this->nombre,
                'apellido' => $this->apellido,
                'direccion' => $this->direccion,
                'telefono' => $this->telefono,
                'fechaNacimiento' => $this->fechaNacimiento,
                'sexo' => $this->sexo,
                'idUnidadEjecutora' => $this->idUnidadEjecutora,
            ]);

            // Asociar el empleado al usuario actual
            $user = auth()->user();
            $user->idEmpleado = $empleado->id;
            $user->save();

            // Asociar departamentos seleccionados usando la relación del modelo
            if (!empty($this->selectedDepartamentos)) {
                $empleado->departamentos()->attach($this->selectedDepartamentos, [
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);
            }

            DB::commit();

            session()->flash('success', '¡Registro completado! Bienvenido al sistema.');
            
            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al registrar empleado: ' . $e->getMessage());
            session()->flash('error', 'Ocurrió un error al guardar tus datos. Por favor intenta nuevamente.');
        }
    }

    public function render()
    {
        return view('livewire.empleado.registrar-empleado');
    }
}
