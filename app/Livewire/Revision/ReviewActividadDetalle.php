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

        // Cargar todas las revisiones incluyendo las eliminadas (soft deleted)
        $this->revisiones = $this->actividad->revisiones()->withTrashed()->orderBy('created_at', 'desc')->get()->toArray();
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
            
            // Crear revisión de tipo TAREA
            Revision::create([
                'idActividad' => $this->idActividad,
                'revision' => 'Tarea aprobada',
                'tipo' => 'TAREA',
                'corregido' => true,
            ]);

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

    public function rechazarTarea($tareaId)
    {
        try {
            DB::beginTransaction();

            $tarea = \App\Models\Tareas\Tarea::findOrFail($tareaId);
            
            // Crear revisión de tipo TAREA
            Revision::create([
                'idActividad' => $this->idActividad,
                'revision' => 'Tarea rechazada',
                'tipo' => 'TAREA',
                'corregido' => false,
            ]);

            // Cambiar estado de tarea a RECHAZADO
            $tarea->update(['estado' => 'RECHAZADO']);

            DB::commit();

            session()->flash('message', 'Tarea rechazada exitosamente');
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
        $this->showComentarioModal = true;
    }

    public function cerrarComentarioModal()
    {
        $this->showComentarioModal = false;
        $this->tipoComentario = '';
        $this->idElementoComentario = null;
        $this->textoComentario = '';
    }

    public function enviarComentario()
    {
        $this->validate([
            'textoComentario' => 'required|min:5|max:500',
        ], [
            'textoComentario.required' => 'El comentario es requerido',
            'textoComentario.min' => 'El comentario debe tener al menos 5 caracteres',
            'textoComentario.max' => 'El comentario no puede exceder 500 caracteres',
        ]);

        try {
            DB::beginTransaction();

            // Crear revisión del tipo correspondiente
            Revision::create([
                'idActividad' => $this->idActividad,
                'revision' => $this->textoComentario,
                'tipo' => $this->tipoComentario,
                'corregido' => false,
            ]);

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
