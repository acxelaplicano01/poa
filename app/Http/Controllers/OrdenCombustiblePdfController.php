<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requisicion\DetalleRequisicion;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\OrdenCombustible;
use Illuminate\Support\Facades\Auth;

class OrdenCombustiblePdfController extends Controller
{
    private function prepararDatos($detalleId): array
    {
        $orden = \DB::table('orden_combustible')
            ->where('idDetalleRequisicion', $detalleId)
            ->orderByDesc('id')
            ->first();

        if (!$orden) {
            abort(404, 'Orden de combustible no encontrada.');
        }

        $detalleRequisicion = DetalleRequisicion::with([
            'presupuesto.tareaHistorico',
            'presupuesto.tarea',
            'requisicion.departamento'
        ])->find($detalleId);

        $orden = (object) $orden;
        $orden->detalleRequisicion = $detalleRequisicion;
        $orden->tareas_historico = $detalleRequisicion->presupuesto->tareaHistorico ?? null;
        $orden->empleado = $orden->responsable
            ? \App\Models\Empleados\Empleado::find($orden->responsable)
            : null;

        return [
            'orden' => $orden,
            'userDescarga' => Auth::user(),
            'userSolicitante' => optional($detalleRequisicion->requisicion)->creador ?? null,
        ];
    }

    public function show($detalleId)
    {
        $pdf = Pdf::loadView('pdf.orden-combustible', $this->prepararDatos($detalleId));

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="orden-combustible-'.$detalleId.'.pdf"');
    }

   public function download($detalleId)
    {
        $orden = \DB::table('orden_combustible')
            ->where('idDetalleRequisicion', $detalleId)
            ->orderByDesc('id')
            ->first();

        if (!$orden) {
            abort(404, 'Orden de combustible no encontrada.');
        }

        $detalleRequisicion = DetalleRequisicion::with([
            'presupuesto.tareaHistorico',
            'presupuesto.tarea',
            'requisicion.departamento'
        ])->find($detalleId);

        $orden = (object) $orden;
        $orden->detalleRequisicion = $detalleRequisicion;
        $orden->tareas_historico = $detalleRequisicion->presupuesto->tareaHistorico ?? null;
        $orden->empleado = $orden->responsable ? \App\Models\Empleados\Empleado::find($orden->responsable) : null;

        $userDescarga = Auth::user();
        $userSolicitante = optional($detalleRequisicion->requisicion)->creador ?? null;

        $pdf = Pdf::loadView('pdf.orden-combustible', [
            'orden' => $orden,
            'userDescarga' => $userDescarga,
            'userSolicitante' => $userSolicitante,
        ]);
        
        return $pdf->download('orden-combustible-' . $detalleId . '.pdf');
    }
}
