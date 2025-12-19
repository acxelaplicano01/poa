<?php

namespace App\Livewire\Revision;

use Illuminate\Support\Facades\Auth;
use App\Models\Actividad\Revision as RevisionModel;

use Livewire\Component;
use App\Models\Actividad\Actividad;
use App\Models\Tareas\Tarea;
use App\Models\Actividad\Indicador;
use App\Models\Planificacion\Planificacion;
use App\Models\Presupuestos\Presupuesto;
use App\Models\Actividad\Revision;

use Livewire\WithPagination;

class DetalleActividadRevision extends Component
{
    use WithPagination;

    public $actividadId;
    public $actividad;
    public $tab = 'tareas';

    // Tareas
    public $tareaId;
    public $tareaNombre;
    public $tareaDescripcion;
    public $searchTarea = '';
    public $perPageTarea = 10;
    public $sortFieldTarea = 'id';
    public $sortDirectionTarea = 'desc';
    public $showTareaModal = false;
    public $showTareaDeleteModal = false;
    public $tareaToDelete;
    public $errorMessage = '';
    public $showErrorModal = false;
    public $isEditingTarea = false;

    protected $rules = [
        'tareaNombre' => 'required|min:3|max:255',
        'tareaDescripcion' => 'nullable|max:1000',
    ];

    protected $messages = [
        'tareaNombre.required' => 'El nombre de la tarea es obligatorio.',
        'tareaNombre.min' => 'El nombre debe tener al menos 3 caracteres.',
        'tareaNombre.max' => 'El nombre no puede exceder 255 caracteres.',
        'tareaDescripcion.max' => 'La descripción no puede exceder 1000 caracteres.',
    ];

    protected $queryString = [
        'searchTarea' => ['except' => ''],
        'sortFieldTarea' => ['except' => 'id'],
        'sortDirectionTarea' => ['except' => 'desc'],
    ];

    public function mount($actividadId)
    {
        $this->actividadId = $actividadId;
        $this->cargarActividad();
    }

    public function cargarActividad()
    {
        $this->actividad = Actividad::with(['tipo', 'categoria', 'departamento'])
            ->findOrFail($this->actividadId);
    }

    public function sortByTarea($field)
    {
        if ($this->sortFieldTarea === $field) {
            $this->sortDirectionTarea = $this->sortDirectionTarea === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirectionTarea = 'asc';
        }
        $this->sortFieldTarea = $field;
    }

    public function updatingSearchTarea()
    {
        $this->resetPage();
    }

    public function resetTareaFields()
    {
        $this->tareaNombre = '';
        $this->tareaDescripcion = '';
        $this->tareaId = null;
        $this->resetValidation();
    }

    public function createTarea()
    {
        $this->resetTareaFields();
        $this->isEditingTarea = false;
        $this->openTareaModal();
    }

    public function openTareaModal()
    {
        $this->showTareaModal = true;
    }

    public function closeTareaModal()
    {
        $this->showTareaModal = false;
        $this->resetTareaFields();
    }

    public function storeTarea()
    {
        $this->validate();
        try {
            $tarea = Tarea::updateOrCreate(['id' => $this->tareaId], [
                'nombre' => $this->tareaNombre,
                'descripcion' => $this->tareaDescripcion,
                'idActividad' => $this->actividadId,
            ]);
            session()->flash('message',
                $this->tareaId
                    ? 'Tarea actualizada correctamente.'
                    : 'Tarea creada correctamente.'
            );
            $this->closeTareaModal();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->errorMessage = 'Error al guardar la tarea: ' . $e->getMessage();
            $this->showErrorModal = true;
        }
    }

    public function editTarea($id)
    {
        $tarea = Tarea::findOrFail($id);
        $this->tareaId = $id;
        $this->tareaNombre = $tarea->nombre;
        $this->tareaDescripcion = $tarea->descripcion;
        $this->isEditingTarea = true;
        $this->openTareaModal();
    }

    public function confirmDeleteTarea($id)
    {
        $this->tareaToDelete = Tarea::findOrFail($id);
        $this->showTareaDeleteModal = true;
    }

    public function deleteTarea()
    {
        try {
            $this->tareaToDelete->delete();
            session()->flash('message', 'Tarea eliminada correctamente.');
            $this->showTareaDeleteModal = false;
            $this->resetPage();
        } catch (\Exception $e) {
            $this->errorMessage = 'Error al eliminar la tarea: ' . $e->getMessage();
            $this->showTareaDeleteModal = false;
            $this->showErrorModal = true;
        }
    }

    public function closeTareaDeleteModal()
    {
        $this->showTareaDeleteModal = false;
        $this->tareaToDelete = null;
    }

    public function closeErrorModal()
    {
        $this->showErrorModal = false;
        $this->errorMessage = '';
    }

    public function cambiarTab($tab)
    {
        $this->tab = $tab;
    }

    public function render()
    {
        $tareas = Tarea::where('idActividad', $this->actividadId)
            ->when($this->searchTarea, function ($query) {
                $query->where('nombre', 'like', '%' . $this->searchTarea . '%')
                      ->orWhere('descripcion', 'like', '%' . $this->searchTarea . '%');
            })
            ->orderBy($this->sortFieldTarea, $this->sortDirectionTarea)
            ->paginate($this->perPageTarea);

        $indicadores = class_exists(Indicador::class) ? Indicador::where('idActividad', $this->actividadId)->get() : collect();
        $planificaciones = class_exists(Planificacion::class) ? Planificacion::where('idActividad', $this->actividadId)->get() : collect();
        $revisiones = RevisionModel::where('idActividad', $this->actividadId)->orderBy('created_at', 'desc')->get();

        return view('livewire.Revision.detalle-actividad-revision', [
            'actividad' => $this->actividad,
            'tareas' => $tareas,
            'indicadores' => $indicadores,
            'planificaciones' => $planificaciones,
            'revisiones' => $revisiones,
            'tab' => $this->tab,
        ]);
    }

    // --- Métodos de revisión de tareas ---
    public $revisionComentario = '';
    public $tareaRevisionId = null;
    public $showRevisionModal = false;
    public $revisionAccion = null;

    public function openRevisionModal($tareaId, $accion)
    {
        $this->tareaRevisionId = $tareaId;
        $this->revisionAccion = $accion; // 'APROBADO', 'RECHAZADO', 'REVISION'
        $this->revisionComentario = '';
        $this->showRevisionModal = true;
    }

    public function closeRevisionModal()
    {
        $this->showRevisionModal = false;
        $this->tareaRevisionId = null;
        $this->revisionAccion = null;
        $this->revisionComentario = '';
    }

    public function guardarRevisionTarea()
    {
        $tarea = Tarea::findOrFail($this->tareaRevisionId);
        $tarea->estado = $this->revisionAccion;
        $tarea->save();

        // Guardar comentario en tabla revisions
        if ($this->revisionComentario) {
            RevisionModel::create([
                'revision' => $this->revisionComentario,
                'tipo' => 'TAREA',
                'corregido' => false,
                'idActividad' => $this->actividadId,
                'created_by' => Auth::id(),
            ]);
        }

        $this->closeRevisionModal();
        $this->actualizarEstadoActividad();
        session()->flash('message', 'Estado de la tarea actualizado correctamente.');
    }

    // --- Lógica automática para estado de la actividad ---
    public function actualizarEstadoActividad()
    {
        $actividad = $this->actividad;
        $tareas = Tarea::where('idActividad', $this->actividadId)->get();
        if ($tareas->count() === 0) {
            return;
        }
        $allAprobadas = $tareas->every(fn($t) => $t->estado === 'APROBADO');
        $anyRechazada = $tareas->contains(fn($t) => $t->estado === 'RECHAZADO');
        $anyRevision = $tareas->contains(fn($t) => $t->estado === 'REVISION');

        if ($allAprobadas) {
            $actividad->estado = 'APROBADO';
        } elseif ($anyRechazada) {
            $actividad->estado = 'RECHAZADO';
        } elseif ($anyRevision) {
            $actividad->estado = 'REVISION';
        } else {
            $actividad->estado = 'REVISION';
        }
        $actividad->save();
        $this->cargarActividad();
    }
}
