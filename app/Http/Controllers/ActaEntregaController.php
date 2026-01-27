<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Actas\ActaEntrega;
use App\Models\Requisicion\Requisicion;
use App\Models\Requisicion\DetalleRequisicion;
use App\Models\Actas\DetalleActaEntrega;
use App\Models\EjecucionPresupuestaria\DetalleEjecucionPresupuestaria;
use Barryvdh\DomPDF\Facade\Pdf;

class ActaEntregaController extends Controller
{
    public function descargarPdf($requisicionId)
    {
        try {
            \Log::info('=== INICIO DESCARGA ACTA PDF ===', ['requisicion_id' => $requisicionId]);

            // Verificar que la requisición existe
            $requisicion = Requisicion::with([
                'departamento',
                'estado',
                'creador.empleado',
                'detalleRequisiciones.presupuesto.unidadMedida'
            ])->find($requisicionId);

            if (!$requisicion) {
                \Log::error('Requisición no encontrada', ['requisicion_id' => $requisicionId]);
                return back()->with('error', 'Requisición no encontrada.');
            }

            \Log::info('Requisición encontrada', [
                'id' => $requisicion->id,
                'correlativo' => $requisicion->correlativo,
                'estado' => $requisicion->estado->estado ?? 'N/A'
            ]);

            // Verificar que existe el acta
            $actaEntrega = ActaEntrega::with([
                'tipoActaEntrega',
                'ejecucionPresupuestaria',
                'detalles.detalleRequisicion.presupuesto.unidadMedida',
                'detalles.detalleEjecucionPresupuestaria'
            ])->where('idRequisicion', $requisicionId)->first();

            if (!$actaEntrega) {
                \Log::error('Acta no encontrada', ['requisicion_id' => $requisicionId]);
                return back()->with('error', 'No se encontró el acta de entrega. Asegúrate de que la requisición esté finalizada.');
            }

            \Log::info('Acta encontrada', [
                'acta_id' => $actaEntrega->id,
                'correlativo' => $actaEntrega->correlativo,
                'detalles_count' => $actaEntrega->detalles->count()
            ]);

            // Preparar datos
            $data = [
                'acta' => $actaEntrega,
                'requisicion' => $requisicion,
                'detalles' => $actaEntrega->detalles,
            ];

            \Log::info('Generando PDF...');

            // Generar PDF
            $pdf = Pdf::loadView('pdf.acta-entrega', $data);
            $pdf->setPaper('letter', 'portrait');

            \Log::info('PDF generado exitosamente');

            $filename = 'Acta-Entrega-' . $actaEntrega->correlativo . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('=== ERROR AL GENERAR PDF ===', [
                'requisicion_id' => $requisicionId,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Error al generar el PDF: ' . $e->getMessage());
        }
    }

    public function generarIntermedia($idRequisicion)
    {
        $userId = auth()->id();
        $requisicion = Requisicion::findOrFail($idRequisicion);

        // Crear el acta intermedia
        $acta = ActaEntrega::create([
            'correlativo' => ActaEntrega::generarCorrelativo(), // tu lógica para correlativo
            'fecha_extendida' => now(),
            'idTipoActaEntrega' => 2, // Intermedia
            'idRequisicion' => $requisicion->id,
            'idEjecucionPresupuestaria' => $requisicion->idEjecucionPresupuestaria ?? null,
            'created_by' => $userId,
        ]);

        // Obtener recursos gestionados
        $recursosGestionados = DetalleRequisicion::where('idRequisicion', $requisicion->id)
            ->where('entregado', true)
            ->get();

        foreach ($recursosGestionados as $detalle) {
            DetalleActaEntrega::create([
                'log_cant_ejecutada' => $detalle->cantidad,
                'log_monto_unitario_ejecutado' => $detalle->presupuesto->costounitario ?? 0,
                'log_fechaEjecucion' => now(),
                'idActaEntrega' => $acta->id,
                'idRequisicion' => $requisicion->id,
                'idDetalleRequisicion' => $detalle->id,
                'idEjecucionPresupuestaria' => $requisicion->idEjecucionPresupuestaria ?? null,
                'idDetalleEjecucionPresupuestaria' => null,
                'created_by' => $userId,
            ]);
        }

        return redirect()->back()->with('message', 'Acta de entrega intermedia generada correctamente.');
    }

    public function descargarIntermediaPdf($requisicionId)
    {
        $requisicion = \App\Models\Requisicion\Requisicion::findOrFail($requisicionId);

        // Busca el acta intermedia (tipo 2) y sus detalles
        $actaEntrega = \App\Models\Actas\ActaEntrega::with([
            'detalles.detalleRequisicion.presupuesto'
        ])
        ->where('idRequisicion', $requisicionId)
        ->where('idTipoActaEntrega', 2)
        ->latest('id')
        ->first();

        // Si NO existe acta intermedia, la creamos automáticamente SOLO con los recursos ejecutados (detalle_ejecucion_presupuestaria)
        if (!$actaEntrega) {
            $userId = auth()->id();

            // Generar correlativo manualmente si no existe el método
            $ultimoActa = \App\Models\Actas\ActaEntrega::orderBy('id', 'desc')->first();
            $numero = $ultimoActa ? ($ultimoActa->id + 1) : 1;
            $anio = now()->format('Y');
            $correlativo = 'ACT-' . str_pad($numero, 6, '0', STR_PAD_LEFT) . '-' . $anio;

            // Buscar todos los detalles de ejecución presupuestaria de esta requisición
            $detallesEjecucion = \App\Models\EjecucionPresupuestaria\DetalleEjecucionPresupuestaria::whereIn(
                'idDetalleRequisicion',
                $requisicion->detalleRequisiciones()->pluck('id')->toArray()
            )->get();

            // Tomar el primer idEjecucion para el acta (puedes ajustar si necesitas lógica diferente)
            $idEjecucionPresupuestaria = $detallesEjecucion->first()->idEjecucion ?? null;

            $actaEntrega = \App\Models\Actas\ActaEntrega::create([
                'correlativo' => $correlativo,
                'fecha_extendida' => now(),
                'idTipoActaEntrega' => 2, // Intermedia
                'idRequisicion' => $requisicion->id,
                'idEjecucionPresupuestaria' => $idEjecucionPresupuestaria,
                'created_by' => $userId,
            ]);

            // Solo agrega los recursos que tienen ejecución (detalle_ejecucion_presupuestaria)
            foreach ($detallesEjecucion as $detalleEjecucion) {
                $detalleReq = $detalleEjecucion->detalleRequisicion;
                \App\Models\Actas\DetalleActaEntrega::create([
                    'log_cant_ejecutada' => $detalleEjecucion->cant_ejecutada,
                    'log_monto_unitario_ejecutado' => $detalleEjecucion->monto_unitario_ejecutado,
                    'log_fechaEjecucion' => $detalleEjecucion->fechaEjecucion,
                    'idActaEntrega' => $actaEntrega->id,
                    'idRequisicion' => $requisicion->id,
                    'idDetalleRequisicion' => $detalleEjecucion->idDetalleRequisicion,
                    'idEjecucionPresupuestaria' => $detalleEjecucion->idEjecucion,
                    'idDetalleEjecucionPresupuestaria' => $detalleEjecucion->id,
                    'created_by' => $userId,
                    // Puedes agregar observacion, referenciaActaEntrega, etc. si tu modelo lo permite
                    'observacion' => $detalleEjecucion->observacion ?? null,
                    'referenciaActaEntrega' => $detalleEjecucion->referenciaActaEntrega ?? null,
                ]);
            }

            // Recargar detalles para el PDF
            $actaEntrega->load('detalles.detalleRequisicion.presupuesto');
        }

        $detalles = $actaEntrega ? $actaEntrega->detalles : collect();

        // Recursos gestionados para el botón (solo los entregados)
        $recursosGestionados = $requisicion->detalleRequisiciones()
            ->where('entregado', '>', 0)
            ->get();

        $data = [
            'requisicion' => $requisicion,
            'acta' => $actaEntrega,
            'detalles' => $detalles,
            'recursosGestionados' => $recursosGestionados,
        ];

        $pdf = \PDF::loadView('pdf.acta-entrega-intermedia', $data);

        return $pdf->download('acta-entrega-intermedia-'.$requisicion->correlativo.'.pdf');
    }
}
