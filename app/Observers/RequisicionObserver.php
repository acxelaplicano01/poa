<?php

namespace App\Observers;

use App\Models\Requisicion\Requisicion;
use App\Models\EjecucionPresupuestaria\EjecucionPresupuestaria;
use App\Models\EjecucionPresupuestaria\EjecucionPresupuestariaLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RequisicionObserver
{
    public function updated(Requisicion $requisicion)
    {
        // Verificar si cambió el estado
        if ($requisicion->isDirty('idEstado')) {
            $estadoAnterior = $requisicion->getOriginal('idEstado');
            $estadoNuevo = $requisicion->idEstado;
            
            $nombreEstadoNuevo = $requisicion->estado->estado ?? '';
            
            Log::info('Estado de requisición cambió', [
                'requisicion_id' => $requisicion->id,
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => $estadoNuevo,
                'nombre_estado' => $nombreEstadoNuevo
            ]);

            // Si el estado es "En Proceso de Compra", crear ejecución presupuestaria
            if ($nombreEstadoNuevo === 'En Proceso de Compra') {
                $this->crearEjecucionPresupuestaria($requisicion);
            }
            
            // Si el estado cambia a "Cancelado" y existe ejecución, marcarla como cancelada
            if ($nombreEstadoNuevo === 'Rechazado' || $nombreEstadoNuevo === 'Cancelado') {
                $this->cancelarEjecucionPresupuestaria($requisicion);
            }
            
            // Si el estado es "Completado" o "Finalizado", marcar ejecución como finalizada
            if ($nombreEstadoNuevo === 'Completado' || $nombreEstadoNuevo === 'Finalizado') {
                $this->finalizarEjecucionPresupuestaria($requisicion);
            }
        }
    }

    protected function crearEjecucionPresupuestaria(Requisicion $requisicion)
    {
        // Verificar si ya existe una ejecución para esta requisición
        $ejecucionExistente = EjecucionPresupuestaria::where('idRequisicion', $requisicion->id)->first();
        
        if ($ejecucionExistente) {
            Log::info('Ya existe una ejecución presupuestaria para esta requisición', [
                'requisicion_id' => $requisicion->id,
                'ejecucion_id' => $ejecucionExistente->id
            ]);
            return;
        }

        try {
            // Crear ejecución presupuestaria con estado "Parcialmente ejecutado" (ID: 1)
            $ejecucion = EjecucionPresupuestaria::create([
                'observacion' => 'Ejecución iniciada automáticamente al pasar requisición a proceso de compra',
                'fechaInicioEjecucion' => now(),
                'idRequisicion' => $requisicion->id,
                'idEstadoEjecucion' => 1, // Parcialmente ejecutado
                'created_by' => Auth::id(),
            ]);

            Log::info('Ejecución presupuestaria creada desde Observer', [
                'requisicion_id' => $requisicion->id,
                'ejecucion_id' => $ejecucion->id
            ]);

            // Crear log
            try {
                $log = EjecucionPresupuestariaLog::create([
                    'observacion' => 'Log generado por el sistema',
                    'log' => 'Ejecución creada',
                    'idEjecucionPresupuestaria' => $ejecucion->id,
                    'created_by' => Auth::id(),
                ]);

                Log::info('Log de ejecución presupuestaria creado', [
                    'ejecucion_id' => $ejecucion->id,
                    'log_id' => $log->id
                ]);
            } catch (\Exception $e) {
                Log::error('Error al crear log de ejecución', [
                    'ejecucion_id' => $ejecucion->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error al crear ejecución presupuestaria en Observer', [
                'requisicion_id' => $requisicion->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    protected function cancelarEjecucionPresupuestaria(Requisicion $requisicion)
    {
        $ejecucion = EjecucionPresupuestaria::where('idRequisicion', $requisicion->id)->first();
        
        if (!$ejecucion) {
            return;
        }

        try {
            // Actualizar estado a "Ejecución cancelada" (ID: 3)
            $ejecucion->update([
                'idEstadoEjecucion' => 3,
                'fechaFinEjecucion' => now(),
                'observacion' => ($ejecucion->observacion ?? '') . ' | Ejecución cancelada por rechazo/cancelación de requisición',
                'updated_by' => Auth::id(),
            ]);

            // Crear log
            EjecucionPresupuestariaLog::create([
                'observacion' => 'Log generado por el sistema',
                'log' => 'Ejecución modificada - Estado cambiado a cancelada',
                'idEjecucionPresupuestaria' => $ejecucion->id,
                'created_by' => Auth::id(),
            ]);

            Log::info('Ejecución presupuestaria cancelada', [
                'requisicion_id' => $requisicion->id,
                'ejecucion_id' => $ejecucion->id
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cancelar ejecución presupuestaria', [
                'requisicion_id' => $requisicion->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    protected function finalizarEjecucionPresupuestaria(Requisicion $requisicion)
    {
        $ejecucion = EjecucionPresupuestaria::where('idRequisicion', $requisicion->id)->first();
        
        if (!$ejecucion) {
            return;
        }

        try {
            // Actualizar estado a "Ejecución finalizada" (ID: 4)
            $ejecucion->update([
                'idEstadoEjecucion' => 4,
                'fechaFinEjecucion' => now(),
                'observacion' => ($ejecucion->observacion ?? '') . ' | Ejecución finalizada',
                'updated_by' => Auth::id(),
            ]);

            // Crear log
            EjecucionPresupuestariaLog::create([
                'observacion' => 'Log generado por el sistema',
                'log' => 'Ejecución modificada - Estado cambiado a finalizada',
                'idEjecucionPresupuestaria' => $ejecucion->id,
                'created_by' => Auth::id(),
            ]);

            Log::info('Ejecución presupuestaria finalizada', [
                'requisicion_id' => $requisicion->id,
                'ejecucion_id' => $ejecucion->id
            ]);

        } catch (\Exception $e) {
            Log::error('Error al finalizar ejecución presupuestaria', [
                'requisicion_id' => $requisicion->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
