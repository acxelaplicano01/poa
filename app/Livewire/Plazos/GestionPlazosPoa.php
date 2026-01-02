<?php

namespace App\Livewire\Plazos;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Poa\Poa;
use App\Models\Plazos\PlazoPoa;
use App\Services\LogService;
use Illuminate\Support\Facades\DB;

class GestionPlazosPoa extends Component
{
    public $idPoa;
    public $poa;
    public $plazosEstandar = [];
    public $plazosPersonalizados = [];
    
    // Control de modales
    public $modalOpen = false;
    public $modalDelete = false;
    public $isEditing = false;
    public $plazoToDelete = null;
    
    // Campos para plazo personalizado
    public $plazoId;
    public $tipo_plazo = '';
    public $nombre_plazo = '';
    public $fecha_inicio_form = '';
    public $fecha_fin_form = '';
    public $descripcion = '';
    public $activo_form = true;
    
    public $tiposPlazosEstandar = [
        'asignacion_nacional' => 'Asignación Nacional',
        'asignacion_departamental' => 'Asignación Departamental',
        'planificacion' => 'Planificación',
        'requerimientos' => 'Requerimientos',
        'seguimiento' => 'Seguimiento',
    ];
    
    protected $rules = [
        'tipo_plazo' => 'required|in:asignacion_nacional,asignacion_departamental,planificacion,requerimientos,seguimiento',
        'nombre_plazo' => 'required|string|max:255',
        'fecha_inicio_form' => 'required|date',
        'fecha_fin_form' => 'required|date|after:fecha_inicio_form',
        'descripcion' => 'nullable|string|max:1000',
    ];
    
    protected $messages = [
        'tipo_plazo.required' => 'El tipo de plazo es obligatorio',
        'tipo_plazo.in' => 'El tipo de plazo seleccionado no es válido',
        'nombre_plazo.required' => 'El nombre del plazo es obligatorio',
        'fecha_inicio_form.required' => 'La fecha de inicio es obligatoria',
        'fecha_inicio_form.date' => 'La fecha de inicio debe ser una fecha válida',
        'fecha_fin_form.required' => 'La fecha de fin es obligatoria',
        'fecha_fin_form.date' => 'La fecha de fin debe ser una fecha válida',
        'fecha_fin_form.after' => 'La fecha de fin debe ser posterior a la fecha de inicio',
    ];

    
    public function mount($idPoa = null)
    {
        // Obtener idPoa de parámetros de ruta o request
        $this->idPoa = $idPoa ?? request()->route('idPoa') ?? request()->get('idPoa');
        
        if (!$this->idPoa) {
            abort(404, 'POA no encontrado');
        }
        
        $this->loadPoa();
        $this->loadPlazos();
    }
    
    public function loadPoa()
    {
        // Obtener institución del usuario autenticado
        $user = auth()->user();
        $userInstitucionId = $user->empleado?->unidadEjecutora?->idInstitucion;

        $query = Poa::with(['institucion']);
        
        // Filtrar por institución del usuario si aplica
        if ($userInstitucionId) {
            $query->where('idInstitucion', $userInstitucionId);
        }
        
        $this->poa = $query->findOrFail($this->idPoa);
    }
    
    public function loadPlazos()
    {
        // Desactivar automáticamente plazos vencidos antes de cargarlos
        PlazoPoa::desactivarPlazosVencidos($this->idPoa);
        
        // Cargar plazos existentes del POA
        $plazosExistentes = PlazoPoa::where('idPoa', $this->idPoa)->get();
        
        // Inicializar plazos estándar (sin nombre_plazo personalizado)
        $this->plazosEstandar = [];
        foreach ($this->tiposPlazosEstandar as $tipo => $label) {
            // Buscar plazo estándar (que NO tiene nombre_plazo)
            $plazoExistente = $plazosExistentes->where('tipo_plazo', $tipo)
                                              ->whereNull('nombre_plazo')
                                              ->first();
            
            $this->plazosEstandar[$tipo] = [
                'id' => $plazoExistente->id ?? null,
                'tipo' => $tipo,
                'label' => $label,
                'fecha_inicio' => $plazoExistente && $plazoExistente->fecha_inicio ? date('Y-m-d', strtotime($plazoExistente->fecha_inicio)) : '',
                'fecha_fin' => $plazoExistente && $plazoExistente->fecha_fin ? date('Y-m-d', strtotime($plazoExistente->fecha_fin)) : '',
                'activo' => $plazoExistente ? (bool)$plazoExistente->activo : false,
                'existe' => $plazoExistente !== null,
            ];
        }
        
        // Cargar plazos personalizados (los que tienen nombre_plazo != null)
        $this->plazosPersonalizados = PlazoPoa::where('idPoa', $this->idPoa)
            ->whereNotNull('nombre_plazo')
            ->get();
    }
    
    public function updatedPlazosEstandar($value, $key)
    {
        // Solo detectar cambios, NO guardar automáticamente
        // El guardado se hará con el botón "Guardar" de cada plazo
    }
    
    public function guardarPlazoEstandar($tipo)
    {
        $plazo = $this->plazosEstandar[$tipo];
        
        // Validar que ambas fechas estén completas
        if (empty($plazo['fecha_inicio']) || empty($plazo['fecha_fin'])) {
            session()->flash('error', 'Debe completar ambas fechas antes de guardar');
            return;
        }
        
        // Validar que fecha_fin sea mayor que fecha_inicio
        if ($plazo['fecha_fin'] <= $plazo['fecha_inicio']) {
            session()->flash('error', 'La fecha de fin debe ser posterior a la fecha de inicio');
            return;
        }
        
        // Verificar si el plazo está vencido (no permitir editar)
        if ($plazo['existe'] && $plazo['id']) {
            $plazoExistente = PlazoPoa::find($plazo['id']);
            if ($plazoExistente && $plazoExistente->fecha_fin) {
                $now = \Carbon\Carbon::now();
                $fin = \Carbon\Carbon::parse($plazoExistente->fecha_fin);
                if ($now->gt($fin)) {
                    session()->flash('error', 'No se puede editar un plazo vencido. La fecha de fin ya ha pasado.');
                    $this->loadPlazos(); // Recargar datos originales
                    return;
                }
            }
        }
        
        try {
            DB::beginTransaction();
            
            $data = [
                'idPoa' => $this->idPoa,
                'tipo_plazo' => $tipo,
                'nombre_plazo' => null,
                'fecha_inicio' => $plazo['fecha_inicio'],
                'fecha_fin' => $plazo['fecha_fin'],
                'activo' => $plazo['activo'],
                'descripcion' => null,
            ];
            
            if ($plazo['existe'] && $plazo['id']) {
                // Actualizar existente
                PlazoPoa::where('id', $plazo['id'])->update($data);
                
                // Log de actualización
                LogService::activity(
                    'actualizar',
                    'plazos_poa',
                    'Plazo estándar actualizado: ' . $this->tiposPlazosEstandar[$tipo],
                    [
                        'plazo_id' => $plazo['id'],
                        'poa_id' => $this->idPoa,
                        'poa_anio' => $this->poa->anio ?? null,
                        'tipo_plazo' => $tipo,
                        'fecha_inicio' => $plazo['fecha_inicio'],
                        'fecha_fin' => $plazo['fecha_fin'],
                        'activo' => $plazo['activo'],
                    ],
                    'info'
                );
            } else {
                // Crear nuevo
                $data['created_by'] = auth()->id();
                $nuevo = PlazoPoa::create($data);
                $this->plazosEstandar[$tipo]['id'] = $nuevo->id;
                $this->plazosEstandar[$tipo]['existe'] = true;
                
                // Log de creación
                LogService::activity(
                    'crear',
                    'plazos_poa',
                    'Plazo estándar creado: ' . $this->tiposPlazosEstandar[$tipo],
                    [
                        'plazo_id' => $nuevo->id,
                        'poa_id' => $this->idPoa,
                        'poa_anio' => $this->poa->anio ?? null,
                        'tipo_plazo' => $tipo,
                        'fecha_inicio' => $plazo['fecha_inicio'],
                        'fecha_fin' => $plazo['fecha_fin'],
                        'activo' => $plazo['activo'],
                    ],
                    'info'
                );
            }
            
            DB::commit();
            
            session()->flash('success', 'Plazo guardado exitosamente');
            $this->loadPlazos(); // Recargar para mostrar estado actualizado
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error: ' . $e->getMessage());
            // Log de creación
            LogService::activity(
                'crear',
                'plazos_poa',
                'Plazo estándar creado: ' . $this->tiposPlazosEstandar[$tipo],
                [
                    'Intentó crearlo' => Auth::user()->name . ' (' . Auth::user()->email . ')',
                    'error' => $e->getMessage(),
                    'plazo_id' => $nuevo->id,
                    'poa_id' => $this->idPoa,
                    'poa_anio' => $this->poa->anio ?? null,
                    'tipo_plazo' => $tipo,
                    'fecha_inicio' => $plazo['fecha_inicio'],
                    'fecha_fin' => $plazo['fecha_fin'],
                    'activo' => $plazo['activo'],
                ],
                'error'
            );
            $this->loadPlazos(); // Recargar para mostrar valores anteriores
        }
    }
    
    public function toggleActivo($tipo)
    {
        $plazo = $this->plazosEstandar[$tipo];
        
        // Verificar si el plazo está vencido (fecha fin ya pasó)
        if ($plazo['existe'] && !empty($plazo['fecha_fin'])) {
            $now = \Carbon\Carbon::now();
            $fin = \Carbon\Carbon::parse($plazo['fecha_fin']);
            if ($now->gt($fin)) {
                session()->flash('error', 'No se puede modificar un plazo vencido. La fecha de fin ya ha pasado.');
                return;
            }
        }
        
        // Cambiar el estado en la propiedad
        $this->plazosEstandar[$tipo]['activo'] = !$this->plazosEstandar[$tipo]['activo'];
        
        // Guardar inmediatamente en la base de datos
        try {
            DB::beginTransaction();
            
            $plazo = $this->plazosEstandar[$tipo];
            
            // Si el plazo ya existe, actualizar
            if ($plazo['existe'] && $plazo['id']) {
                PlazoPoa::where('id', $plazo['id'])->update([
                    'activo' => $plazo['activo']
                ]);
            } else {
                // Si no existe y se está activando, crear el plazo solo si tiene fechas
                if ($plazo['activo'] && !empty($plazo['fecha_inicio']) && !empty($plazo['fecha_fin'])) {
                    $nuevo = PlazoPoa::create([
                        'idPoa' => $this->idPoa,
                        'tipo_plazo' => $tipo,
                        'nombre_plazo' => null,
                        'fecha_inicio' => $plazo['fecha_inicio'],
                        'fecha_fin' => $plazo['fecha_fin'],
                        'activo' => true,
                        'descripcion' => null,
                        'created_by' => auth()->id(),
                    ]);
                    
                    $this->plazosEstandar[$tipo]['id'] = $nuevo->id;
                    $this->plazosEstandar[$tipo]['existe'] = true;
                } else if (!$plazo['activo']) {
                    // Si se está desactivando pero no existe, no hacer nada
                    // Revertir el cambio en la UI
                    $this->plazosEstandar[$tipo]['activo'] = false;
                } else {
                    // Si se está activando pero no tiene fechas, mostrar error
                    $this->plazosEstandar[$tipo]['activo'] = false;
                    session()->flash('error', 'Debe configurar las fechas antes de activar el plazo');
                    DB::rollBack();
                    return;
                }
            }
            
            DB::commit();
            session()->flash('success', 'Estado del plazo actualizado');
            
        } catch (\Exception $e) {
            DB::rollBack();
            // Revertir el cambio en caso de error
            $this->plazosEstandar[$tipo]['activo'] = !$this->plazosEstandar[$tipo]['activo'];
            session()->flash('error', 'Error al actualizar el estado: ' . $e->getMessage());
        }
    }
    
    // ===== MÉTODOS PARA PLAZOS PERSONALIZADOS =====
    
    public function crear()
    {
        $this->reset(['plazoId', 'tipo_plazo', 'nombre_plazo', 'fecha_inicio_form', 'fecha_fin_form', 'activo_form', 'descripcion']);
        $this->activo_form = true;
        $this->isEditing = false;
        $this->modalOpen = true;
    }
    
    public function closeModal()
    {
        $this->modalOpen = false;
        $this->reset(['plazoId', 'tipo_plazo', 'nombre_plazo', 'fecha_inicio_form', 'fecha_fin_form', 'activo_form', 'descripcion']);
    }

    public function closeDelete()
    {
        $this->modalDelete = false;
    }

    public function editar($id)
    {
        $plazo = PlazoPoa::findOrFail($id);
        
        // Verificar si el plazo está vencido
        $now = \Carbon\Carbon::now();
        $fin = \Carbon\Carbon::parse($plazo->fecha_fin);
        if ($now->gt($fin)) {
            session()->flash('error', 'No se puede editar un plazo vencido. La fecha de fin ya ha pasado.');
            return;
        }
        
        $this->plazoId = $plazo->id;
        $this->tipo_plazo = $plazo->tipo_plazo;
        $this->nombre_plazo = $plazo->nombre_plazo;
        $this->fecha_inicio_form = $plazo->fecha_inicio ? date('Y-m-d', strtotime($plazo->fecha_inicio)) : '';
        $this->fecha_fin_form = $plazo->fecha_fin ? date('Y-m-d', strtotime($plazo->fecha_fin)) : '';
        $this->activo_form = $plazo->activo;
        $this->descripcion = $plazo->descripcion;


        
        $this->isEditing = true;
        $this->modalOpen = true;
    }
    
    public function guardar()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            // La misma lógica que en GestionPlazos:
            // Si se está activando un plazo Y NO tiene nombre personalizado, 
            // desactivar otros del mismo tipo para el mismo POA (solo los que tampoco tienen nombre personalizado)
            // PERO como aquí SIEMPRE tiene nombre personalizado, esta condición nunca se cumple
            // Los plazos personalizados pueden coexistir activos
            
            $data = [
                'idPoa' => $this->idPoa,
                'tipo_plazo' => $this->tipo_plazo,
                'nombre_plazo' => $this->nombre_plazo, // SIEMPRE tiene nombre
                'fecha_inicio' => $this->fecha_inicio_form,
                'fecha_fin' => $this->fecha_fin_form,
                'activo' => $this->activo_form,
                'descripcion' => $this->descripcion,
            ];

            if ($this->isEditing) {
                $plazo = PlazoPoa::findOrFail($this->plazoId);
                
                // Verificar si el plazo original está vencido
                $now = \Carbon\Carbon::now();
                $finOriginal = \Carbon\Carbon::parse($plazo->fecha_fin);
                if ($now->gt($finOriginal)) {
                    session()->flash('error', 'No se puede editar un plazo vencido. La fecha de fin original ya ha pasado.');
                    DB::rollBack();
                    return;
                }
                
                $plazo->update($data);
                
                // Log de actualización
                LogService::activity(
                    'actualizar',
                    'plazos_poa',
                    'Plazo personalizado actualizado: ' . $this->nombre_plazo,
                    [
                        'plazo_id' => $plazo->id,
                        'poa_id' => $this->idPoa,
                        'poa_anio' => $this->poa->anio ?? null,
                        'tipo_plazo' => $this->tipo_plazo,
                        'nombre_plazo' => $this->nombre_plazo,
                        'fecha_inicio' => $this->fecha_inicio_form,
                        'fecha_fin' => $this->fecha_fin_form,
                        'activo' => $this->activo_form,
                    ],
                    'info'
                );
                
                $mensaje = 'Plazo personalizado actualizado exitosamente';
                session()->flash('message', $mensaje);
            } else {
                $data['created_by'] = auth()->id();
                $nuevoPlazo = PlazoPoa::create($data);
                
                // Log de creación
                LogService::activity(
                    'crear',
                    'plazos_poa',
                    'Plazo personalizado creado: ' . $this->nombre_plazo,
                    [
                        'plazo_id' => $nuevoPlazo->id,
                        'poa_id' => $this->idPoa,
                        'poa_anio' => $this->poa->anio ?? null,
                        'tipo_plazo' => $this->tipo_plazo,
                        'nombre_plazo' => $this->nombre_plazo,
                        'fecha_inicio' => $this->fecha_inicio_form,
                        'fecha_fin' => $this->fecha_fin_form,
                        'activo' => $this->activo_form,
                    ], 
                    'info'
                );
                
                $mensaje = 'Plazo personalizado creado exitosamente';
                session()->flash('message', $mensaje);
            }

            DB::commit();
            
            session()->flash('success', $mensaje);
            $this->modalOpen = false;
            $this->reset(['plazoId', 'tipo_plazo', 'nombre_plazo', 'fecha_inicio_form', 'fecha_fin_form', 'activo_form', 'descripcion']);
            $this->loadPlazos(); // Recargar plazos
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al guardar el plazo: ' . $e->getMessage());
        }
    }
    
    public function confirmDelete($id)
    {
        $plazo = PlazoPoa::findOrFail($id);
        
        // Verificar si el plazo está vencido
        $now = \Carbon\Carbon::now();
        $fin = \Carbon\Carbon::parse($plazo->fecha_fin);
        if ($now->gt($fin)) {
            session()->flash('error', 'No se puede eliminar un plazo vencido. La fecha de fin ya ha pasado.');
            return;
        }
        
        $this->plazoToDelete = $plazo;
        $this->modalDelete = true;
    }

    public function eliminar()
    {
        try {
            if ($this->plazoToDelete) {
                // Verificar nuevamente si el plazo está vencido antes de eliminar
                $now = \Carbon\Carbon::now();
                $fin = \Carbon\Carbon::parse($this->plazoToDelete->fecha_fin);
                if ($now->gt($fin)) {
                    session()->flash('error', 'No se puede eliminar un plazo vencido. La fecha de fin ya ha pasado.');
                    $this->closeDelete();
                    return;
                }
                
                // Guardar información para el log antes de eliminar
                $plazoId = $this->plazoToDelete->id;
                $tipoPlazo = $this->plazoToDelete->tipo_plazo;
                $nombrePlazo = $this->plazoToDelete->nombre_plazo ?? $this->tiposPlazosEstandar[$tipoPlazo] ?? $tipoPlazo;
                
                $this->plazoToDelete->delete();
                
                // Log de eliminación
                LogService::activity(
                    'eliminar',
                    'plazos_poa',
                    'Plazo eliminado: ' . $nombrePlazo,
                    [
                        'plazo_id' => $plazoId,
                        'poa_id' => $this->idPoa,
                        'poa_anio' => $this->poa->anio ?? null,
                        'tipo_plazo' => $tipoPlazo,
                        'nombre_plazo' => $nombrePlazo,
                    ],
                    'info'
                );
                
                session()->flash('success', 'Plazo eliminado exitosamente');
                $this->closeDelete();
                $this->plazoToDelete = null;
                $this->loadPlazos(); // Recargar plazos
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar el plazo: ' . $e->getMessage());
        }
    }
    
    public function volver()
    {
        return redirect()->route('techonacional', ['idPoa' => $this->idPoa]);
    }
    
    public function render()
    {
        return view('livewire.plazos.gestion-plazos-poa')->layout('layouts.app');
    }
}
