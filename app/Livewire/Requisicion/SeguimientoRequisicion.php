<?php

namespace App\Livewire\Requisicion;

use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Requisicion\Requisicion;
use App\Models\Departamento\Departamento;
use Illuminate\Support\Facades\DB;
use App\Models\Poa\Poa;

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

        // Actualizar recursos (eliminar los que se quitaron y actualizar cantidades)
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
        session()->flash('message', 'Requisición actualizada correctamente.');
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
            session()->flash('message', 'Requisición eliminada correctamente.');
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
        $requisicion = Requisicion::with(['detalleRequisiciones.presupuesto', 'detalleRequisiciones.unidadMedida'])->findOrFail($id);
        $this->detalleRecursos = $requisicion->detalleRequisiciones->map(function($detalle) {
            return [
                'recurso' => $detalle->presupuesto->recurso ?? '-',
                'detalle_tecnico' => $detalle->presupuesto->detalle_tecnico ?? '-',
                'cantidad' => $detalle->cantidad ?? '-',
                'precio_unitario' => $detalle->presupuesto->costounitario ?? '-',
                'total' => ($detalle->cantidad ?? 0) * ($detalle->presupuesto->costounitario ?? 0),
                'estado' => $detalle->entregado ? 'Entregado' : 'Pendiente',
                'ref_acta' => $detalle->referenciaActaEntrega ?? 'No Aplica',
            ];
        })->toArray();
        $this->showDetalleRecursosModal = true;
    }

    public function cerrarDetalleRecursosModal()
    {
        $this->showDetalleRecursosModal = false;
        $this->detalleRecursos = [];
    }


    public function render()
    {
        $query = Requisicion::with(['departamento', 'estado']);
        if ($this->estadoFiltro && $this->estadoFiltro !== 'Todos') {
            $query->whereHas('estado', function($q) {
                $q->where('estado', $this->estadoFiltro);
            });
        }
        $requisiciones = $query->orderByDesc('id')->paginate(10);

        // Agregar manualmente el departamento del creador a cada requisición
        foreach ($requisiciones as $requisicion) {
            $departamentoCreador = null;
            if (
                $requisicion->creador &&
                $requisicion->creador->empleado &&
                $requisicion->creador->empleado->departamentos->count()
            ) {
                $departamentoCreador = $requisicion->creador->empleado->departamentos->first();
            }
            $requisicion->departamento_creador = $departamentoCreador;
        }

        $poas = Poa::activo()->orderByDesc('anio')->get();
        return view('livewire.seguimiento.Requisicion.requisiciones-lista', [
            'requisiciones' => $requisiciones,
            'poas' => $poas,
        ])->layout($this->layout);
    }
}
