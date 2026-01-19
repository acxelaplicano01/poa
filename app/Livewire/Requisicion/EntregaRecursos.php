<?php

namespace App\Livewire\Requisicion;

use Livewire\Component;
use App\Models\Requisicion\Requisicion;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class EntregaRecursos extends Component
{
    public $requisicionId;
    public $detalleRequisicion = [];
    public $recursosParaEntregar = [];

    public function mount($requisicionId)
    {
        $this->requisicionId = $requisicionId;
        $requisicion = Requisicion::with(['departamento', 'estado', 'creador.empleado', 'detalleRequisiciones.presupuesto'])->findOrFail($this->requisicionId);
        $empleado = $requisicion->creador && $requisicion->creador->empleado ? $requisicion->creador->empleado : null;
        $empleadoNombreCompleto = $empleado ? trim($empleado->nombres . ' ' . $empleado->apellidos) : ($requisicion->creador->name ?? '-');
        $this->detalleRequisicion = [
            'correlativo' => $requisicion->correlativo,
            'departamento' => $requisicion->departamento->name ?? '-',
            'descripcion' => $requisicion->descripcion ?? '-',
            'observacion' => $requisicion->observacion ?? '-',
            'creador' => $empleadoNombreCompleto,
            'estado' => $requisicion->estado->estado ?? '-',
            'fecha_presentado' => $requisicion->fechaSolicitud ?? '-',
            'fecha_requerido' => $requisicion->fechaRequerido ?? '-',
        ];
        $this->recursosParaEntregar = $requisicion->detalleRequisiciones->map(function($detalle) {
            $presupuesto = $detalle->presupuesto;
            return [
                'id' => $detalle->id,
                'recurso' => $presupuesto->recurso ?? '-',
                'detalle_tecnico' => $presupuesto->detalle_tecnico ?? '-',
                'observacion' => $detalle->observacion ?? '-',
                'factura' => $detalle->factura ?? '-',
                'fecha_ejecucion' => $detalle->fecha_ejecucion ?? '-',
                'cantidad' => $detalle->cantidad ?? '-',
                'monto_requerido' => ($detalle->cantidad ?? 0) * ($presupuesto->costounitario ?? 0),
                'entregado' => $detalle->entregado ?? 0,
                'monto_ejecutado' => ($detalle->entregado ?? 0) * ($presupuesto->costoejecucion ?? $presupuesto->costounitario ?? 0),
            ];
        })->toArray();
    }

    public function render()
    {
        return view('livewire.seguimiento.Requisicion.entrega-recursos', [
            'detalleRequisicion' => $this->detalleRequisicion,
            'recursosParaEntregar' => $this->recursosParaEntregar,
        ]);
    }
}
