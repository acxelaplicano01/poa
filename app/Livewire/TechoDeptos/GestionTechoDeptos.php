<?php

namespace App\Livewire\TechoDeptos;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Poa\Poa;
use App\Models\UnidadEjecutora\UnidadEjecutora;
use App\Models\Departamento\Departamento;
use App\Models\TechoUes\TechoUe;
use App\Models\TechoUes\TechoDepto;
use App\Models\Poa\PoaDepto;
use App\Models\GrupoGastos\GrupoGasto;

class GestionTechoDeptos extends Component
{
    use WithPagination;

    public $idPoa;
    public $idUE;
    public $poa;
    public $unidadEjecutora;
    public $search = '';
    public $activeTab = 'resumen'; // Nueva propiedad para el tab activo
    public $showModal = false;
    public $showDeleteModal = false;
    public $techoDeptoToDelete = null;
    public $isEditing = false;
    public $techoDeptoId;

    // Propiedades del formulario
    public $monto = '';
    public $idDepartamento = '';
    public $idTechoUE = '';

    // Listados para los selects
    public $departamentos = [];
    public $techoUes = [];
    
    protected $rules = [
        'monto' => 'required|numeric|min:0',
        'idDepartamento' => 'required|exists:departamentos,id',
        'idTechoUE' => 'required|exists:techo_ues,id',
    ];

    protected $messages = [
        'monto.required' => 'El monto es obligatorio.',
        'monto.numeric' => 'El monto debe ser un número.',
        'monto.min' => 'El monto debe ser mayor o igual a 0.',
        'idDepartamento.required' => 'El departamento es obligatorio.',
        'idDepartamento.exists' => 'El departamento seleccionado no existe.',
        'idTechoUE.required' => 'El techo de la unidad ejecutora es obligatorio.',
        'idTechoUE.exists' => 'El techo de la unidad ejecutora seleccionado no existe.',
    ];

    // Definimos explícitamente cómo queremos que se procesen los parámetros en la URL
    protected $queryString = [
        'idPoa' => ['except' => ''],
        'idUE' => ['except' => '']
    ];

    public function mount()
    {
        // No recibimos los parámetros directamente en mount ya que los manejamos vía $queryString
        
        if ($this->idPoa && $this->idUE) {
            $this->poa = Poa::findOrFail($this->idPoa);
            $this->unidadEjecutora = UnidadEjecutora::findOrFail($this->idUE);
            
            // Cargar listas para los selects
            $this->loadDepartamentos();
            $this->loadTechoUes();
        } else {
            session()->flash('error', 'Se requiere un POA y una Unidad Ejecutora para gestionar los techos por departamento.');
            return redirect()->route('asignacionpresupuestaria');
        }
    }
    
    private function loadDepartamentos()
    {
        $this->departamentos = Departamento::where('idUnidadEjecutora', $this->idUE)->orderBy('name')->get();
    }
    
    private function loadTechoUes()
    {
        $this->techoUes = TechoUe::where('idPoa', $this->idPoa)
            ->where('idUE', $this->idUE)
            ->with('fuente')
            ->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function render()
    {
        // Obtener todos los departamentos de la UE
        $todosDepartamentos = Departamento::where('idUnidadEjecutora', $this->idUE)
            ->orderBy('name')
            ->get();

        // Obtener IDs de departamentos que ya tienen techos asignados
        $departamentosConTecho = TechoDepto::where('idPoa', $this->idPoa)
            ->where('idUE', $this->idUE)
            ->pluck('idDepartamento')
            ->unique();

        // Separar departamentos con y sin techos
        $departamentosSinTecho = $todosDepartamentos->whereNotIn('id', $departamentosConTecho);
        $departamentosConTechoData = $todosDepartamentos->whereIn('id', $departamentosConTecho);

        // Obtener techos departamentales con relaciones
        $techoDeptos = TechoDepto::with(['departamento', 'techoUE.fuente'])
            ->where('idPoa', $this->idPoa)
            ->where('idUE', $this->idUE)
            ->when($this->search, function ($query) {
                $query->whereHas('departamento', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('idDepartamento')
            ->paginate(10);

        // Calcular resumen del presupuesto
        $resumenPresupuesto = $this->getResumenPresupuesto();

        return view('livewire.techo-deptos.gestion-techo-deptos', [
            'techoDeptos' => $techoDeptos,
            'departamentosSinTecho' => $departamentosSinTecho,
            'departamentosConTecho' => $departamentosConTechoData,
            'resumenPresupuesto' => $resumenPresupuesto,
        ])->layout('layouts.app');
    }

    private function getResumenPresupuesto()
    {
        // Obtener todos los techos UE para esta UE y POA
        $techosUE = TechoUe::where('idPoa', $this->idPoa)
            ->where('idUE', $this->idUE)
            ->with('fuente')
            ->get();

        $resumen = [];
        foreach ($techosUE as $techoUE) {
            // Calcular el monto total asignado a departamentos desde este techo UE
            $montoAsignado = TechoDepto::where('idPoa', $this->idPoa)
                ->where('idUE', $this->idUE)
                ->where('idTechoUE', $techoUE->id)
                ->sum('monto');

            $montoDisponible = $techoUE->monto - $montoAsignado;
            $porcentajeUsado = $techoUE->monto > 0 ? ($montoAsignado / $techoUE->monto) * 100 : 0;

            $resumen[] = [
                'fuente' => $techoUE->fuente->nombre ?? 'Sin fuente',
                'montoTotal' => $techoUE->monto,
                'montoAsignado' => $montoAsignado,
                'montoDisponible' => $montoDisponible,
                'porcentajeUsado' => $porcentajeUsado,
                'estado' => $this->getEstadoPresupuesto($porcentajeUsado),
            ];
        }

        return collect($resumen);
    }

    private function getEstadoPresupuesto($porcentaje)
    {
        if ($porcentaje >= 100) {
            return ['clase' => 'bg-red-500', 'texto' => 'Agotado', 'color' => 'text-red-700'];
        } elseif ($porcentaje >= 80) {
            return ['clase' => 'bg-yellow-500', 'texto' => 'Crítico', 'color' => 'text-yellow-700'];
        } elseif ($porcentaje >= 50) {
            return ['clase' => 'bg-blue-500', 'texto' => 'En uso', 'color' => 'text-blue-700'];
        } else {
            return ['clase' => 'bg-green-500', 'texto' => 'Disponible', 'color' => 'text-green-700'];
        }
    }

    public function create()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function createForDepartment($departamentoId)
    {
        $this->resetForm();
        $this->idDepartamento = $departamentoId;
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $techoDepto = TechoDepto::findOrFail($id);
        $this->techoDeptoId = $techoDepto->id;
        $this->monto = $techoDepto->monto;
        $this->idDepartamento = $techoDepto->idDepartamento;
        $this->idTechoUE = $techoDepto->idTechoUE;
        
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        // Validar disponibilidad de presupuesto
        if (!$this->isEditing) {
            $disponibilidadValida = $this->validarDisponibilidadPresupuesto();
            if (!$disponibilidadValida) {
                return; // El error ya se muestra en el método de validación
            }
        }

        if ($this->isEditing) {
            $techoDepto = TechoDepto::findOrFail($this->techoDeptoId);
            
            // Validar disponibilidad en edición (excluyendo el monto actual)
            $montoAnterior = $techoDepto->monto;
            $diferenciaMonto = $this->monto - $montoAnterior;
            
            if ($diferenciaMonto > 0) {
                $disponibilidadValida = $this->validarDisponibilidadPresupuesto($diferenciaMonto);
                if (!$disponibilidadValida) {
                    return;
                }
            }
            
            $techoDepto->update([
                'monto' => $this->monto,
                'idDepartamento' => $this->idDepartamento,
                'idTechoUE' => $this->idTechoUE,
            ]);
            
            session()->flash('message', 'Techo departamental actualizado correctamente.');
        } else {
            // Crear o encontrar el PoaDepto para esta combinación POA-Departamento
            $poaDepto = PoaDepto::firstOrCreate([
                'idPoa' => $this->idPoa,
                'idDepartamento' => $this->idDepartamento,
            ], [
                'isActive' => true,
            ]);

            // Crear el TechoDepto con el PoaDepto asociado
            TechoDepto::create([
                'monto' => $this->monto,
                'idUE' => $this->idUE,
                'idPoa' => $this->idPoa,
                'idDepartamento' => $this->idDepartamento,
                'idPoaDepto' => $poaDepto->id,
                'idTechoUE' => $this->idTechoUE,
            ]);
            
            session()->flash('message', 'Techo departamental creado correctamente.');
        }

        $this->closeModal();
    }

    private function validarDisponibilidadPresupuesto($montoAValidar = null)
    {
        $montoAUsar = $montoAValidar ?? $this->monto;
        
        // Obtener el techo UE seleccionado
        $techoUE = TechoUe::find($this->idTechoUE);
        if (!$techoUE) {
            session()->flash('error', 'Techo UE no encontrado.');
            return false;
        }

        // Calcular el monto ya asignado desde este techo UE
        $montoAsignado = TechoDepto::where('idPoa', $this->idPoa)
            ->where('idUE', $this->idUE)
            ->where('idTechoUE', $this->idTechoUE)
            ->sum('monto');

        $montoDisponible = $techoUE->monto - $montoAsignado;

        if ($montoAUsar > $montoDisponible) {
            session()->flash('error', 
                'El monto excede el presupuesto disponible. ' .
                'Disponible: ' . number_format($montoDisponible, 2) . 
                ', Solicitado: ' . number_format($montoAUsar, 2)
            );
            return false;
        }

        return true;
    }

    public function confirmDelete($id)
    {
        $this->techoDeptoToDelete = TechoDepto::findOrFail($id);
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->techoDeptoToDelete) {
            $this->techoDeptoToDelete->delete();
            session()->flash('message', 'Techo departamental eliminado correctamente.');
            $this->closeDeleteModal();
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->techoDeptoToDelete = null;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    private function resetForm()
    {
        $this->techoDeptoId = null;
        $this->monto = '';
        $this->idDepartamento = '';
        $this->idTechoUE = '';
        $this->isEditing = false;
    }

    public function backToPoa()
    {
        return redirect()->route('asignacionpresupuestaria');
    }
}
