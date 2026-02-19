<?php

namespace App\Livewire\Requisicion;

use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Requisicion\Requisicion;
use App\Models\Departamento\Departamento;
use Illuminate\Support\Facades\DB;
use App\Models\Poa\Poa;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class SeguimientoRequisicion extends Component   
{
    public $showRecursosModal = false;
    public $recursosRequisicion = [];
    public $isEditing = false;
    public $requisicionToDelete = null;
    public $showErrorModal = false;
    public $errorMessage = '';
    public $estadoFiltro = 'Todos';
    public $showModal = false;
    public $correlativo;
    public $descripcion;
    public $observacion;
    public $idPoa;
    public $fechaSolicitud;
    public $fechaRequerido;
    public $requisicionId;
    public $showDeleteModal = false;
    public $showSumarioModal = false;
    public $recursosSeleccionados = [];
    public $isViewing = true;
    public $showDetalleRecursosModal = false;
    public $detalleRecursos = [];   
    public $departamentoSeleccionado = null;
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';

    public $poaYear = null;
    public $poaYears = [];
    public $mostrarSelector = false;
    public $departamentosUsuario = [];
    
    public $successMessage = ''; // Inicializa la variable para evitar errores

    public function sortBy($field)
    {
        // Validate the sort field against valid columns in the requisicion table
        $validColumns = ['id', 'correlativo', 'descripcion', 'fechaSolicitud', 'fechaRequerido']; // Add valid columns here
        $relatedFields = ['departamento']; // Add related fields here

        if (in_array($field, $validColumns)) {
            $this->sortField = $field;
        } elseif (in_array($field, $relatedFields)) {
            // Handle sorting by related fields explicitly
            $this->sortField = 'departamento.name'; // Example for sorting by departamento.name
        } else {
            $this->sortField = 'id'; // Default to a valid column
        }

        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    }


    protected string $layout = 'layouts.app';

    public function verRecursosRequisicion($id)
    {
        $requisicion = Requisicion::findOrFail($id);
        $this->requisicionId = $requisicion->id;
        $this->correlativo = $requisicion->correlativo;
        $this->descripcion = $requisicion->descripcion;
        $this->observacion = $requisicion->observacion;
        $this->idPoa = $requisicion->idPoa;
        $this->fechaSolicitud = $requisicion->fechaSolicitud;
        $this->fechaRequerido = $requisicion->fechaRequerido;
        $this->isEditing = true;
        $this->isViewing = false;
        $this->showSumarioModal = true;
        $this->recursosSeleccionados = $requisicion->detalleRequisiciones()->with(['presupuesto.tareaHistorico.procesoCompra', 'unidadMedida'])->get()->map(function($detalle) {
            $presupuesto = $detalle->presupuesto;
            $tareaHistorico = $presupuesto ? $presupuesto->tareaHistorico : null;
            $procesoCompra = $tareaHistorico ? $tareaHistorico->procesoCompra : null;
            return [
                'id' => $detalle->id,
                'nombre' => $presupuesto->recurso ?? '-',
                'actividad' => $tareaHistorico ? $tareaHistorico->nombre : '-',
                'proceso_compra' => $procesoCompra ? $procesoCompra->nombre_proceso : '-',
                'cantidad_seleccionada' => $detalle->cantidad,
                'unidad_medida' => $detalle->unidadMedida->nombre ?? '-',
                'precio_unitario' => $presupuesto->costounitario ?? 0,
                'total' => ($detalle->cantidad ?? 0) * ($presupuesto->costounitario ?? 0),
            ];
        })->toArray();
    }
    
    // Cuando se edita, no es solo visualización
    public function edit($id)
    {
        $this->isViewing = false;
        $this->verRecursosRequisicion($id);
        $this->isEditing = true;
    }

    // Quitar recurso del sumario
    public function quitarRecursoDelSumario($id)
    {
        $this->recursosSeleccionados = array_filter($this->recursosSeleccionados, function($recurso) use ($id) {
            return $recurso['id'] != $id;
        });
        $this->recursosSeleccionados = array_values($this->recursosSeleccionados);
    }

    // Guardar cambios de la edición de requisición
    public function guardarEdicionRequisicion()
    {
        $requisicion = Requisicion::findOrFail($this->requisicionId);
        $requisicion->descripcion = $this->descripcion;
        $requisicion->observacion = $this->observacion;
        $requisicion->fechaRequerido = $this->fechaRequerido;
        $requisicion->save();

        // Actualizar recursos 
        $idsSeleccionados = array_column($this->recursosSeleccionados, 'id');
        // Eliminar los recursos que ya no están
        $requisicion->detalleRequisiciones()->whereNotIn('id', $idsSeleccionados)->delete();
        // Actualizar cantidades de los recursos restantes
        foreach ($this->recursosSeleccionados as $recurso) {
            $detalle = $requisicion->detalleRequisiciones()->find($recurso['id']);
            if ($detalle) {
                $detalle->cantidad = $recurso['cantidad_seleccionada'];
                $detalle->save();
            }
        }

        $this->showSumarioModal = false;
        $this->isEditing = false;
        $this->successMessage = 'Requisición actualizada correctamente.'; // Actualiza el mensaje de éxito
    }

    // Mostrar modal de confirmación para eliminar una requisición
    public function confirmDelete($id)
    {
        $this->requisicionToDelete = Requisicion::find($id);
        $this->showErrorModal = false;
        $this->showRecursosModal = false;
        $this->showSumarioModal = false;
        $this->showModal = false;
        $this->showDeleteModal = true;
    }

        // Eliminar la requisición seleccionada
    public function delete()
    {
        if ($this->requisicionToDelete) {
            $this->requisicionToDelete->delete();
            $this->showDeleteModal = false;
            $this->requisicionToDelete = null;
            $this->successMessage = 'Requisición eliminada correctamente.'; // Actualiza el mensaje de éxito
        }
    }

    // Cerrar el modal de eliminación
    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->requisicionToDelete = null;
    }

    // Cerrar el modal sumario
    public function cerrarSumario()
    {
        $this->showSumarioModal = false;
        $this->isEditing = false;
    }
    
    // Mostrar modal de detalle de recursos
  public function verDetalleRecursos($id)
{
    $requisicion = Requisicion::with([
        'detalleRequisiciones.presupuesto',
        'estado'
    ])->findOrFail($id);

    $this->detalleRecursos = $requisicion->detalleRequisiciones->map(function ($detalle) use ($requisicion) {
        $cantidad = (float)($detalle->cantidad ?? 0);
        $precioUnitario = (float)($detalle->presupuesto->costounitario ?? 0);
        return [
            'recurso'         => $detalle->presupuesto->recurso ?? '-',
            'detalle_tecnico' => $detalle->presupuesto->detalle_tecnico ?? '-',
            'cantidad'        => $cantidad,
            'precio_unitario' => $precioUnitario,
            'total'           => $cantidad * $precioUnitario,
            'estado'          => $requisicion->estado->estado ?? 'Desconocido',
        ];
    })->toArray();

    $this->montoTotalRequisicion = collect($this->detalleRecursos)->sum('total');

    $this->montoEjecutadoRequisicion = DB::table('detalle_ejecucion_presupuestaria')
        ->whereIn('idDetalleRequisicion', $requisicion->detalleRequisiciones->pluck('id'))
        ->sum('monto_total_ejecutado');

    $this->estadoRequisicion = $requisicion->estado->estado ?? 'Desconocido';
    $this->showDetalleRecursosModal = true;
}

public function cerrarDetalleModal()
{
    $this->showDetalleRecursosModal = false;
    $this->detalleRecursos = [];
    $this->montoTotalRequisicion = 0;
    $this->montoEjecutadoRequisicion = 0;
    $this->estadoRequisicion = null;
}

    public function cerrarDetalleRecursosModal()
    {
        $this->showDetalleRecursosModal = false;
        $this->detalleRecursos = [];
    }


    public function render()
    {
        // Fetch departments for the user
        $this->departamentosUsuario = auth()->user() && auth()->user()->empleado
            ? auth()->user()->empleado->departamentos()->with('unidadEjecutora')->get()
            : collect();

        // Determine if the selector should be shown
        $this->mostrarSelector = $this->departamentosUsuario->count() > 1;

        // Set default department if not already selected
        if ($this->departamentoSeleccionado === null && $this->departamentosUsuario->isNotEmpty()) {
            $this->departamentoSeleccionado = $this->departamentosUsuario->first()->id;
        }

        // Build the query for requisitions
        $query = Requisicion::with(['departamento', 'estado'])
            ->when($this->estadoFiltro && $this->estadoFiltro !== 'Todos', function ($query) {
                $query->whereHas('estado', function ($q) {
                    $q->where('estado', $this->estadoFiltro);
                });
            })
            ->when($this->poaYear, function ($query) {
                $query->whereHas('poa', function ($q) {
                    $q->where('anio', $this->poaYear);
                });
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('correlativo', 'like', "%{$this->search}%")
                      ->orWhereHas('departamento', function ($q2) {
                          $q2->where('name', 'like', "%{$this->search}%");
                      });
                });
            })
            ->when($this->departamentoSeleccionado, function ($query) {
                $query->whereHas('departamento', function ($q) {
                    $q->where('id', $this->departamentoSeleccionado);
                });
            });

        if ($this->sortField === 'departamento.name') {
            $query->join('departamentos', 'requisicion.idDepartamento', '=', 'departamentos.id')
              ->orderBy('departamentos.name', $this->sortDirection);
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $requisiciones = $query->paginate($this->perPage ?? 10);

        $poas = Poa::activo()->orderByDesc('anio')->get();
        $this->poaYears = $poas->pluck('anio')->unique()->sort()->values();

        return view('livewire.seguimiento.Requisicion.requisiciones-lista', [
            'requisiciones' => $requisiciones,
            'poas' => $poas,
            'mostrarSelector' => $this->mostrarSelector,
            'departamentosUsuario' => $this->departamentosUsuario,
            'poaYears' => $this->poaYears,
        ]);
    }
}
