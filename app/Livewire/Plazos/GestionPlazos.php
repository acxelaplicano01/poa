<?php

namespace App\Livewire\Plazos;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Plazos\PlazoPoa;
use App\Models\Poa\Poa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GestionPlazos extends Component
{
    use WithPagination;

    // Propiedades del formulario
    public $plazoId;
    public $tipo_plazo;
    public $nombre_plazo;
    public $fecha_inicio;
    public $fecha_fin;
    public $idPoa;
    public $activo = true;
    public $descripcion;

    // Control de modal
    public $modalOpen = false;
    public $modalDelete = false;
    public $plazoToDelete = null;
    public $isEditing = false;

    // Filtros
    public $filtroPoa = '';
    public $filtroTipo = '';
    public $filtroEstado = '';

    // Listas
    public $poas = [];
    public $tiposPlazos = [];

    protected function rules()
    {
        return [
            'tipo_plazo' => 'required|in:asignacion_nacional,asignacion_departamental,planificacion,requerimientos,seguimiento',
            'nombre_plazo' => 'nullable|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'idPoa' => 'required|exists:poas,id',
            'activo' => 'boolean',
            'descripcion' => 'nullable|string|max:500',
        ];
    }

    protected $messages = [
        'tipo_plazo.required' => 'El tipo de plazo es obligatorio',
        'fecha_inicio.required' => 'La fecha de inicio es obligatoria',
        'fecha_fin.required' => 'La fecha de fin es obligatoria',
        'fecha_fin.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio',
        'idPoa.required' => 'Debe seleccionar un POA',
    ];

    public function mount()
    {
        $this->cargarListas();
    }

    public function cargarListas()
    {
        // Cargar POAs disponibles
        $this->poas = Poa::with(['institucion', 'unidadEjecutora'])
            ->orderBy('anio', 'desc')
            ->get();

        // Tipos de plazos
        $this->tiposPlazos = [
            ['value' => 'asignacion_nacional', 'label' => 'Asignación Nacional'],
            ['value' => 'asignacion_departamental', 'label' => 'Asignación Departamental'],
            ['value' => 'planificacion', 'label' => 'Planificación'],
            ['value' => 'requerimientos', 'label' => 'Requerimientos'],
            ['value' => 'seguimiento', 'label' => 'Seguimiento'],
        ];
    }

    public function crear()
    {
        $this->reset(['plazoId', 'tipo_plazo', 'nombre_plazo', 'fecha_inicio', 'fecha_fin', 'idPoa', 'activo', 'descripcion']);
        $this->activo = true;
        $this->isEditing = false;
        $this->modalOpen = true;
    }

    public function editar($id)
    {
        $plazo = PlazoPoa::findOrFail($id);
        
        $this->plazoId = $plazo->id;
        $this->tipo_plazo = $plazo->tipo_plazo;
        $this->nombre_plazo = $plazo->nombre_plazo;
        $this->fecha_inicio = $plazo->fecha_inicio->format('Y-m-d');
        $this->fecha_fin = $plazo->fecha_fin->format('Y-m-d');
        $this->idPoa = $plazo->idPoa;
        $this->activo = $plazo->activo;
        $this->descripcion = $plazo->descripcion;
        
        $this->isEditing = true;
        $this->modalOpen = true;
    }

    public function guardar()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            // Si se está activando un plazo Y NO tiene nombre personalizado, 
            // desactivar otros del mismo tipo para el mismo POA (solo los que tampoco tienen nombre personalizado)
            if ($this->activo && empty($this->nombre_plazo)) {
                PlazoPoa::where('idPoa', $this->idPoa)
                    ->where('tipo_plazo', $this->tipo_plazo)
                    ->whereNull('nombre_plazo') // Solo desactivar plazos sin nombre personalizado
                    ->when($this->plazoId, function($query) {
                        $query->where('id', '!=', $this->plazoId);
                    })
                    ->update(['activo' => false]);
            }

            $data = [
                'tipo_plazo' => $this->tipo_plazo,
                'nombre_plazo' => $this->nombre_plazo,
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin' => $this->fecha_fin,
                'idPoa' => $this->idPoa,
                'activo' => $this->activo,
                'descripcion' => $this->descripcion,
            ];

            if ($this->isEditing) {
                $plazo = PlazoPoa::findOrFail($this->plazoId);
                $plazo->update($data);
                $mensaje = 'Plazo actualizado exitosamente';
            } else {
                $data['created_by'] = Auth::id();
                PlazoPoa::create($data);
                $mensaje = 'Plazo creado exitosamente';
            }

            DB::commit();
            
            session()->flash('message', $mensaje);
            $this->modalOpen = false;
            $this->reset(['plazoId', 'tipo_plazo', 'nombre_plazo', 'fecha_inicio', 'fecha_fin', 'idPoa', 'activo', 'descripcion']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al guardar el plazo: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->plazoToDelete = PlazoPoa::findOrFail($id);
        $this->modalDelete = true;
    }

    public function eliminar()
    {
        try {
            if ($this->plazoToDelete) {
                $this->plazoToDelete->delete();
                session()->flash('message', 'Plazo eliminado exitosamente');
                $this->modalDelete = false;
                $this->plazoToDelete = null;
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar el plazo: ' . $e->getMessage());
        }
    }

    public function toggleActivo($id)
    {
        DB::beginTransaction();
        try {
            $plazo = PlazoPoa::findOrFail($id);
            
            // Si se está activando y NO tiene nombre personalizado, desactivar otros del mismo tipo sin nombre personalizado
            if (!$plazo->activo && empty($plazo->nombre_plazo)) {
                PlazoPoa::where('idPoa', $plazo->idPoa)
                    ->where('tipo_plazo', $plazo->tipo_plazo)
                    ->whereNull('nombre_plazo') // Solo desactivar plazos sin nombre personalizado
                    ->where('id', '!=', $id)
                    ->update(['activo' => false]);
            }
            
            $plazo->activo = !$plazo->activo;
            $plazo->save();
            
            DB::commit();
            session()->flash('message', 'Estado del plazo actualizado');
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al cambiar el estado: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = PlazoPoa::with(['poa.institucion', 'poa.unidadEjecutora'])
            ->orderBy('created_at', 'desc');

        // Aplicar filtros
        if ($this->filtroPoa) {
            $query->where('idPoa', $this->filtroPoa);
        }

        if ($this->filtroTipo) {
            $query->where('tipo_plazo', $this->filtroTipo);
        }

        if ($this->filtroEstado) {
            $hoy = Carbon::now();
            switch ($this->filtroEstado) {
                case 'vigente':
                    $query->vigente();
                    break;
                case 'vencido':
                    $query->where('fecha_fin', '<', $hoy);
                    break;
                case 'proximo':
                    $query->where('fecha_inicio', '>', $hoy);
                    break;
                case 'inactivo':
                    $query->where('activo', false);
                    break;
            }
        }

        $plazos = $query->paginate(10);

        return view('livewire.plazos.gestion-plazos', [
            'plazos' => $plazos,
        ])->layout('layouts.app');
    }
}
