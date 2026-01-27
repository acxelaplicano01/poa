<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requisicion\DetalleRequisicion;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\OrdenCombustible;
use Illuminate\Support\Facades\Auth;

class OrdenCombustiblePdfController extends Controller
{
    public function show($detalleId)
    {
        // Busca la orden de combustible asociada al detalle de requisición
        $orden = \DB::table('orden_combustible')
            ->where('idDetalleRequisicion', $detalleId)
            ->orderByDesc('id')
            ->first();

        if (!$orden) {
            abort(404, 'Orden de combustible no encontrada.');
        }

        // Cargar relaciones necesarias manualmente (ajusta según tus modelos)
        $detalleRequisicion = DetalleRequisicion::with([
            'presupuesto.tareaHistorico',
            'presupuesto.tarea',
            'requisicion.departamento' // Cambia 'depto' por 'departamento'
        ])->find($detalleId);

        // Simula modelos para la vista (ajusta si tienes modelos Eloquent)
        $orden = (object) $orden;
        $orden->detalleRequisicion = $detalleRequisicion;
        $orden->tareas_historico = $detalleRequisicion->presupuesto->tareaHistorico ?? null;
        $orden->empleado = $orden->responsable ? \App\Models\Empleados\Empleado::find($orden->responsable) : null;

        // Usuario que descarga
        $userDescarga = Auth::user();
        $userSolicitante = optional($detalleRequisicion->requisicion)->creador ?? null;

        $pdf = Pdf::loadView('pdf.orden-combustible', [
            'orden' => $orden,
            'userDescarga' => $userDescarga,
            'userSolicitante' => $userSolicitante,
        ]);

        return $pdf->stream('orden-combustible.pdf');
    }
}
