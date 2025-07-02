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
    public $showModal = false;
    public $showDeleteModal = false;
    public $techoDeptoToDelete = null;
    public $isEditing = false;
    public $techoDeptoId;

    // Propiedades del formulario
    public $monto = '';
    public $idDepartamento = '';
    public $idPoaDepto = '';
    public $idTechoUE = '';
    public $idGrupo = '';

    // Listados para los selects
    public $departamentos = [];
    public $poaDeptos = [];
    public $techoUes = [];
    public $grupoGastos = [];
    
    protected $rules = [
        'monto' => 'required|numeric|min:0',
        'idDepartamento' => 'required|exists:departamentos,id',
        'idPoaDepto' => 'required|exists:poa_deptos,id',
        'idTechoUE' => 'required|exists:techo_ues,id',
        'idGrupo' => 'nullable|exists:grupo_gastos,id',
    ];

    protected $messages = [
        'monto.required' => 'El monto es obligatorio.',
        'monto.numeric' => 'El monto debe ser un número.',
        'monto.min' => 'El monto debe ser mayor o igual a 0.',
        'idDepartamento.required' => 'El departamento es obligatorio.',
        'idDepartamento.exists' => 'El departamento seleccionado no existe.',
        'idPoaDepto.required' => 'El POA del departamento es obligatorio.',
        'idPoaDepto.exists' => 'El POA del departamento seleccionado no existe.',
        'idTechoUE.required' => 'El techo de la unidad ejecutora es obligatorio.',
        'idTechoUE.exists' => 'El techo de la unidad ejecutora seleccionado no existe.',
        'idGrupo.exists' => 'El grupo de gastos seleccionado no existe.',
    ];

    protected $queryString = ['idPoa', 'idUE'];

    public function mount($idPoa = null, $idUE = null)
    {
        $this->idPoa = $idPoa;
        $this->idUE = $idUE;
        
        if ($this->idPoa && $this->idUE) {
            $this->poa = Poa::findOrFail($this->idPoa);
            $this->unidadEjecutora = UnidadEjecutora::findOrFail($this->idUE);
            
            // Cargar listas para los selects
            $this->loadDepartamentos();
            $this->loadTechoUes();
            $this->grupoGastos = GrupoGasto::orderBy('nombre')->get();
        } else {
            session()->flash('error', 'Se requiere un POA y una Unidad Ejecutora para gestionar los techos por departamento.');
            return redirect()->route('asignacionpresupuestaria');
        }
    }
    
    private function loadDepartamentos()
    {
        $this->departamentos = Departamento::where('idUE', $this->idUE)->orderBy('name')->get();
    }
    
    private function loadPoaDeptos($idDepartamento = null)
    {
        if ($idDepartamento) {
            $this->poaDeptos = PoaDepto::where('idPoa', $this->idPoa)
                ->where('idDepartamento', $idDepartamento)
                ->get();
        } else {
            $this->poaDeptos = [];
        }
    }
    
    private function loadTechoUes()
    {
        $this->techoUes = TechoUe::where('idPoa', $this->idPoa)
            ->where('idUE', $this->idUE)
            ->with('fuente')
            ->get();
    }

    public function updatedIdDepartamento()
    {
        $this->loadPoaDeptos($this->idDepartamento);
        $this->idPoaDepto = '';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $techoDeptos = TechoDepto::with(['departamento', 'poaDepto', 'techoUE'])
            ->where('idPoa', $this->idPoa)
            ->where('idUE', $this->idUE)
            ->when($this->search, function ($query) {
                $query->whereHas('departamento', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('idDepartamento')
            ->paginate(10);

        return view('livewire.techo-deptos.gestion-techo-deptos', [
            'techoDeptos' => $techoDeptos,
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $techoDepto = TechoDepto::findOrFail($id);
        $this->techoDeptoId = $techoDepto->id;
        $this->monto = $techoDepto->monto;
        $this->idDepartamento = $techoDepto->idDepartamento;
        $this->loadPoaDeptos($this->idDepartamento);
        $this->idPoaDepto = $techoDepto->idPoaDepto;
        $this->idTechoUE = $techoDepto->idTechoUE;
        $this->idGrupo = $techoDepto->idGrupo;
        
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $techoDepto = TechoDepto::findOrFail($this->techoDeptoId);
            $techoDepto->update([
                'monto' => $this->monto,
                'idDepartamento' => $this->idDepartamento,
                'idPoaDepto' => $this->idPoaDepto,
                'idTechoUE' => $this->idTechoUE,
                'idGrupo' => $this->idGrupo,
            ]);
            
            session()->flash('message', 'Techo departamental actualizado correctamente.');
        } else {
            TechoDepto::create([
                'monto' => $this->monto,
                'idUE' => $this->idUE,
                'idPoa' => $this->idPoa,
                'idDepartamento' => $this->idDepartamento,
                'idPoaDepto' => $this->idPoaDepto,
                'idTechoUE' => $this->idTechoUE,
                'idGrupo' => $this->idGrupo,
            ]);
            
            session()->flash('message', 'Techo departamental creado correctamente.');
        }

        $this->closeModal();
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
        $this->idPoaDepto = '';
        $this->idTechoUE = '';
        $this->idGrupo = '';
        $this->poaDeptos = [];
        $this->isEditing = false;
    }

    public function backToPoa()
    {
        return redirect()->route('poas');
    }
}
