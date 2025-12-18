<?php

namespace App\Livewire\Revision;

use Livewire\Component;
use App\Models\Actividad\Actividad;
use App\Models\Actividad\Revision;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewActividadDetalle extends Component
{
    public $idActividad;
    public $actividad;
    public $revisiones = [];
    public $showFormRevision = false;
    public $showFormDictamen = false;
    public $comentarioRevision = '';
    public $tipoDictamen = ''; // 'aceptar' o 'rechazar'
    public $comentarioDictamen = '';
    public $activeTab = 'informacion';
    public $showTareaModal = false;
    public $tareaSeleccionada = null;
    
    // Modales de comentarios
    public $showComentarioModal = false;
    public $tipoComentario = ''; // 'TAREA' o 'INDICADOR'
    public $idElementoComentario = null;
    public $textoComentario = '';
    
    // Modal de rechazo de tarea
    public $showRechazarTareaModal = false;
    public $tareaIdRechazo = null;
    public $comentarioRechazo = '';
    public $requiereCorreccion = true;
    
    protected $queryString = ['activeTab'];
    
    public function mount($id)
    {
        $this->idActividad = $id;
        $this->cargarActividad();
    }

    private function cargarActividad()
    {
        $this->actividad = Actividad::with([
            'indicadores.planificacions.mes.trimestre',
            'empleados',
            'tareas.presupuestos.fuente',
            'tareas.presupuestos.objetoGasto',
            'tareas.presupuestos.grupoGasto',
            'tareas.presupuestos.mes',
            'tareas.presupuestos.unidadMedida',
            'tareas.empleados',
            'revisiones'
        ])->findOrFail($this->idActividad);

        // Cargar todas las revisiones con los nombres de tareas e indicadores
        $revisiones = $this->actividad->revisiones()->withTrashed()->orderBy('created_at', 'desc')->get();
        
        $this->revisiones = $revisiones->map(function($revision) {
            $revisionArray = $revision->toArray();
            
            // Agregar el nombre del elemento según el tipo
            if ($revision->tipo === 'TAREA' && $revision->idElemento) {
                $tarea = \App\Models\Tareas\Tarea::find($revision->idElemento);
                $revisionArray['elemento_nombre'] = $tarea ? $tarea->nombre : 'Tarea no encontrada';
            } elseif ($revision->tipo === 'INDICADOR' && $revision->idElemento) {
                $indicador = \App\Models\Actividad\Indicador::find($revision->idElemento);
                $revisionArray['elemento_nombre'] = $indicador ? $indicador->nombre : 'Indicador no encontrado';
            } else {
                $revisionArray['elemento_nombre'] = null;
            }
            
            return $revisionArray;
        })->toArray();
    }

    public function openTareaModal($tareaId)
    {
        $this->tareaSeleccionada = $this->actividad->tareas->find($tareaId);
        $this->showTareaModal = true;
    }

    public function closeTareaModal()
    {
        $this->showTareaModal = false;
        $this->tareaSeleccionada = null;
    }

    public function aprobarTarea($tareaId)
    {
        try {
            DB::beginTransaction();

            $tarea = \App\Models\Tareas\Tarea::findOrFail($tareaId);
            
            // Cambiar estado de tarea a APROBADO
            $tarea->update(['estado' => 'APROBADO']);

            DB::commit();

            session()->flash('message', 'Tarea aprobada exitosamente');
            $this->cargarActividad();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function abrirModalRechazo($tareaId)
    {
        $this->tareaIdRechazo = $tareaId;
        $this->comentarioRechazo = '';
        $this->requiereCorreccion = true;
        $this->showRechazarTareaModal = true;
    }

    public function cerrarModalRechazo()
    {
        $this->showRechazarTareaModal = false;
        $this->tareaIdRechazo = null;
        $this->comentarioRechazo = '';
        $this->requiereCorreccion = true;
    }

    public function rechazarTarea()
    {
        $this->validate([
            'comentarioRechazo' => 'required|min:10|max:1000',
        ], [
            'comentarioRechazo.required' => 'El comentario es requerido',
            'comentarioRechazo.min' => 'El comentario debe tener al menos 10 caracteres',
            'comentarioRechazo.max' => 'El comentario no puede exceder 1000 caracteres',
        ]);

        try {
            DB::beginTransaction();

            $tarea = \App\Models\Tareas\Tarea::findOrFail($this->tareaIdRechazo);
            
            // Buscar si ya existe una revisión PENDIENTE (no corregida) para esta tarea
            $revision = Revision::where('idActividad', $this->idActividad)
                ->where('idElemento', $this->tareaIdRechazo)
                ->where('tipo', 'TAREA')
                ->where('corregido', false)
                ->first();
            
            if ($revision) {
                // Actualizar la revisión pendiente existente (mismo ciclo de corrección)
                $revision->update([
                    'revision' => $this->comentarioRechazo,
                    'corregido' => !$this->requiereCorreccion,
                ]);
            } else {
                // Crear nueva revisión (primer rechazo o nuevo ciclo después de corrección)
                Revision::create([
                    'idActividad' => $this->idActividad,
                    'idElemento' => $this->tareaIdRechazo,
                    'revision' => $this->comentarioRechazo,
                    'tipo' => 'TAREA',
                    'corregido' => !$this->requiereCorreccion,
                ]);
            }
            
            // Cambiar estado de tarea a RECHAZADO
            $tarea->update(['estado' => 'RECHAZADO']);
            
            DB::commit();

            session()->flash('message', 'Tarea rechazada exitosamente');
            $this->cerrarModalRechazo();
            $this->cargarActividad();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function abrirComentarioModal($tipo, $id)
    {
        $this->tipoComentario = $tipo;
        $this->idElementoComentario = $id;
        $this->textoComentario = '';
        $this->requiereCorreccion = true;
        $this->showComentarioModal = true;
    }

    public function cerrarComentarioModal()
    {
        $this->showComentarioModal = false;
        $this->tipoComentario = '';
        $this->idElementoComentario = null;
        $this->textoComentario = '';
        $this->requiereCorreccion = true;
    }

    public function enviarComentario()
    {
        $this->validate([
            'textoComentario' => 'required|min:10|max:1000',
        ], [
            'textoComentario.required' => 'El comentario es requerido',
            'textoComentario.min' => 'El comentario debe tener al menos 10 caracteres',
            'textoComentario.max' => 'El comentario no puede exceder 1000 caracteres',
        ]);

        try {
            DB::beginTransaction();

            // Buscar si ya existe una revisión PENDIENTE (no corregida) para este elemento
            $revision = Revision::where('idActividad', $this->idActividad)
                ->where('idElemento', $this->idElementoComentario)
                ->where('tipo', $this->tipoComentario)
                ->where('corregido', false)
                ->first();
            
            if ($revision) {
                // Actualizar la revisión pendiente existente (mismo ciclo de corrección)
                $revision->update([
                    'revision' => $this->textoComentario,
                    'corregido' => !$this->requiereCorreccion,
                ]);
            } else {
                // Crear nueva revisión (primer rechazo o nuevo ciclo después de corrección)
                Revision::create([
                    'idActividad' => $this->idActividad,
                    'idElemento' => $this->idElementoComentario,
                    'revision' => $this->textoComentario,
                    'tipo' => $this->tipoComentario,
                    'corregido' => !$this->requiereCorreccion,
                ]);
            }

            DB::commit();

            session()->flash('message', 'Comentario enviado exitosamente');
            $this->cerrarComentarioModal();
            $this->cargarActividad();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function enviarParaReformulacion()
    {
        $this->validate([
            'comentarioRevision' => 'required|min:10|max:1000',
        ], [
            'comentarioRevision.required' => 'El comentario es requerido',
            'comentarioRevision.min' => 'El comentario debe tener al menos 10 caracteres',
            'comentarioRevision.max' => 'El comentario no puede exceder 1000 caracteres',
        ]);

        try {
            DB::beginTransaction();

            // Crear revisión
            Revision::create([
                'idActividad' => $this->idActividad,
                'revision' => $this->comentarioRevision,
                'tipo' => 'REVISION',
                'corregido' => false,
            ]);

            // Cambiar estado de actividad a REFORMULACION
            $this->actividad->update(['estado' => 'REFORMULACION']);

            DB::commit();

            session()->flash('message', 'Actividad enviada a reformulación exitosamente');
            $this->comentarioRevision = '';
            $this->showFormRevision = false;
            $this->cargarActividad();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function emitirDictamen()
    {
        $this->validate([
            'tipoDictamen' => 'required|in:aceptar,rechazar',
            'comentarioDictamen' => 'required|min:10|max:1000',
        ], [
            'tipoDictamen.required' => 'Debe seleccionar aceptar o rechazar',
            'comentarioDictamen.required' => 'El comentario es requerido',
            'comentarioDictamen.min' => 'El comentario debe tener al menos 10 caracteres',
            'comentarioDictamen.max' => 'El comentario no puede exceder 1000 caracteres',
        ]);

        try {
            DB::beginTransaction();

            // Determinar estado según dictamen
            $nuevoEstado = $this->tipoDictamen === 'aceptar' ? 'APROBADO' : 'RECHAZADO';

            // Crear revisión de dictamen
            Revision::create([
                'idActividad' => $this->idActividad,
                'revision' => $this->comentarioDictamen,
                'tipo' => 'DICTAMEN',
                'corregido' => true,
            ]);

            // Cambiar estado de actividad
            $this->actividad->update(['estado' => $nuevoEstado]);

            DB::commit();

            session()->flash('message', 'Dictamen emitido exitosamente');
            $this->comentarioDictamen = '';
            $this->tipoDictamen = '';
            $this->showFormDictamen = false;
            $this->cargarActividad();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.Revision.review-actividad-detalle')->layout('layouts.app');
    }
}
