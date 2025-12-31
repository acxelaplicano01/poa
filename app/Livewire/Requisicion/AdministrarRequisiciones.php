<?php

namespace App\Livewire\Requisicion;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Requisicion\Requisicion;
use App\Models\Departamento\Departamento;
use App\Models\Poa\Poa;

class AdministrarRequisiciones extends Component
{
    use WithPagination;

    public $search = '';
    public $anio = '';
    public $departamento = '';
    public $estado = '';
    public $perPage = 10;
    public $sortField = 'id';
    public $sortDirection = 'desc';

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function actualizarEstado($id, $nuevoEstado)
    {
        $requisicion = Requisicion::findOrFail($id);
        $requisicion->idEstado = $nuevoEstado;
        $requisicion->save();
        session()->flash('message', 'Estado actualizado correctamente.');
    }

    public function verDetalleRequisicion($id)
    {
        $requisicion = Requisicion::with(['departamento', 'estado', 'detalleRequisiciones.presupuesto'])
            ->findOrFail($id);

        $recursos = [];
        $monto_total = 0;
        foreach ($requisicion->detalleRequisiciones as $detalle) {
            $presupuesto = $detalle->presupuesto;
            $total = ($detalle->cantidad ?? 0) * ($presupuesto->costounitario ?? 0);
            $monto_total += $total;
            $recursos[] = [
                'nombre' => $presupuesto->recurso ?? '-',
                'detalle_tecnico' => $presupuesto->detalle_tecnico ?? '-',
                'cantidad' => $detalle->cantidad ?? '-',
                'precio_unitario' => $presupuesto->costounitario ?? 0,
                'total' => $total,
            ];
        }

        $fechaPresentado = $requisicion->fechaSolicitud;
        $fechaRequerido = $requisicion->fechaRequerido;
        if ($fechaPresentado && !($fechaPresentado instanceof \Carbon\Carbon)) {
            $fechaPresentado = \Carbon\Carbon::parse($fechaPresentado);
        }
        if ($fechaRequerido && !($fechaRequerido instanceof \Carbon\Carbon)) {
            $fechaRequerido = \Carbon\Carbon::parse($fechaRequerido);
        }
        $this->detalleRequisicion = [
            'correlativo' => $requisicion->correlativo,
            'departamento' => $requisicion->departamento->name ?? '-',
            'fecha_presentado' => $fechaPresentado ? $fechaPresentado->format('M d, Y') : '',
            'fecha_requerido' => $fechaRequerido ? $fechaRequerido->format('M d, Y') : '',
            'estado' => $requisicion->estado->estado ?? '-',
            'recursos' => $recursos,
            'monto_total' => $monto_total,
        ];
        $this->showDetalleModal = true;
        $this->observacionModal = '';
        
    }

    public function cerrarDetalleModal()
    {
        $this->showDetalleModal = false;
        $this->detalleRequisicion = [];
        $this->observacionModal = '';
    }

    public function marcarComoRecibido()
    {
        // Aquí puedes implementar la lógica para marcar como recibido
        session()->flash('message', 'Requisición marcada como Recibida.');
    }

    public function marcarComoRechazado()
    {
        // Aquí puedes implementar la lógica para marcar como rechazado
        $this->cerrarDetalleModal();
        session()->flash('message', 'Requisición marcada como Rechazada.');
    }

    public function render()
    {
        $anios = Poa::select('anio')->distinct()->orderByDesc('anio')->pluck('anio');
        $departamentos = Departamento::orderBy('name')->get();
        $estados = [
            'Todos', 'Presentado', 'Recibido', 'En Proceso de Compra', 'Aprobado', 'Rechazado', 'Finalizado'
        ];

        $query = Requisicion::with(['departamento', 'estado'])
            ->when($this->search, function($q) {
                $q->where('correlativo', 'like', "%{$this->search}%")
                  ->orWhereHas('departamento', function($q2) {
                      $q2->where('name', 'like', "%{$this->search}%");
                  });
            })
            ->when($this->anio, function($q) {
                $q->whereHas('poa', function($q2) {
                    $q2->where('anio', $this->anio);
                });
            })
            ->when($this->departamento && $this->departamento !== 'Todos', function($q) {
                $q->whereHas('departamento', function($q2) {
                    $q2->where('id', $this->departamento);
                });
            })
            ->when($this->estado && $this->estado !== 'Todos', function($q) {
                $q->whereHas('estado', function($q2) {
                    $q2->where('estado', $this->estado);
                });
            });

        $requisiciones = $query->orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage);

        return view('livewire.seguimiento.Requisicion.administrar-requisiciones', [
            'requisiciones' => $requisiciones,
            'anios' => $anios,
            'departamentos' => $departamentos,
            'estados' => $estados,
            'detalleRecursos' => $this->detalleRecursos ?? [],
        ])->layout('layouts.app');
    }
}
