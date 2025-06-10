<?php

namespace App\Livewire\Empleado;

use Livewire\Component;
use App\Models\Empleado;
use App\Models\Departamento;

class Empleados extends Component
{
    public $dni;
    public $nEmpleado;
    public $nombre;
    public $apellido;
    public $telefono;
    public $fechaNacimiento;
    public $direccion;
    public $selectedDepartamento = ''; // Para el select
    public $selectedDepartamentos = []; // Array de departamentos seleccionados
    public $isEditing = false; // Para saber si estamos editando o creando
    
    // Carga los departamentos disponibles
    public function mount()
    {
        $this->departamentos = Departamento::all();
    }
    
    // Añade un departamento seleccionado a la lista
    public function addDepartamento()
    {
        if (!empty($this->selectedDepartamento) && !in_array($this->selectedDepartamento, $this->selectedDepartamentos)) {
            $this->selectedDepartamentos[] = $this->selectedDepartamento;
            $this->selectedDepartamento = ''; // Limpia el select
        }
    }
    
    // Elimina un departamento de la lista de seleccionados
    public function removeDepartamento($index)
    {
        if (isset($this->selectedDepartamentos[$index])) {
            unset($this->selectedDepartamentos[$index]);
            $this->selectedDepartamentos = array_values($this->selectedDepartamentos);
        }
    }
    
    // Validación cuando se envía el formulario
    protected function rules()
    {
        return [
            'dni' => 'required|string|max:20|unique:empleados,dni',
            'nEmpleado' => 'required|string|max:20|unique:empleados,nEmpleado',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'telefono' => 'required|string|max:20',
            'fechaNacimiento' => 'required|date',
            'direccion' => 'required|string|max:255',
            'selectedDepartamentos' => 'required|array|min:1',
        ];
    }
    
    // Mensajes personalizados para validación
    protected function messages()
    {
        return [
            'selectedDepartamentos.required' => 'Debe seleccionar al menos un departamento.',
            'selectedDepartamentos.min' => 'Debe seleccionar al menos un departamento.',
        ];
    }
    
    // Almacenar el empleado con sus departamentos
    public function store()
    {
        $this->validate();
        
        // Crea el empleado
        $empleado = Empleado::create([
            'dni' => $this->dni,
            'n_empleado' => $this->nEmpleado,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'telefono' => $this->telefono,
            'fecha_nacimiento' => $this->fechaNacimiento,
            'direccion' => $this->direccion,
        ]);
        
        // Asocia los departamentos al empleado
        $empleado->departamentos()->attach($this->selectedDepartamentos);
        
        $this->closeModal();
        $this->emit('empleadoCreado');
        
        // Mensaje de éxito
        session()->flash('message', 'Empleado creado correctamente.');
    }
    
    public function closeModal()
    {
        $this->emit('closeModal');
        $this->reset();
    }
    
    public function render()
    {
        return view('livewire.empleado.create', [
            'departamentos' => Departamento::orderBy('nombre')->get()
        ])->layout('layouts.app');
    }
}