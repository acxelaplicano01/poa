<?php

namespace App\Models\Actividad;
use App\Models\BaseModel;
use App\Models\Actividad\TipoActividad;
use App\Models\Poa\Poa;
use App\Models\Departamento\Departamento;
use App\Models\UnidadEjecutora\UnidadEjecutora;
use App\Models\Resultados\Resultado;
use App\Models\Actividad\Indicador;
use App\Models\Actividad\Evento;
use App\Models\Actividad\Revision;
use App\Models\Actividad\MedioVerificacionActividad;
use App\Models\Empleados\Empleado;
use App\Models\Categoria\Categoria;

class Actividad extends BaseModel
{
    protected $table = 'actividads';

    protected $fillable = [
        'nombre',
        'descripcion',
        'correlativo',
        'resultadoActividad',
        'poblacion_objetivo',
        'medio_verificacion',
        'estado',
        'finalizada',
        'uploadedIntoSPI',
        'idPoa',
        'idPoaDepto',
        'idInstitucion',
        'idDeptartamento',
        'idUE',
        'idTipo',
        'idResultado',
        'idCategoria',
        'finalizada_at',
        'finalizada_by',
    ];

    public function tipo()
    {
        return $this->belongsTo(TipoActividad::class, 'idTipo');
    }

    public function poa()
    {
        return $this->belongsTo(Poa::class, 'idPoa');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'idDeptartamento');
    }

    public function unidadEjecutora()
    {
        return $this->belongsTo(UnidadEjecutora::class, 'idUE');
    }

    public function resultado()
    {
        return $this->belongsTo(Resultado::class, 'idResultado');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'idCategoria');
    }

    public function indicadores()
    {
        return $this->hasMany(Indicador::class, 'idActividad');
    }

    public function eventos()
    {
        return $this->hasMany(Evento::class, 'idActividad');
    }

    public function revisiones()
    {
        return $this->hasMany(Revision::class, 'idActividad')->withTrashed();
    }

    public function mediosVerificacion()
    {
        return $this->hasMany(MedioVerificacionActividad::class, 'idActividad');
    }

    public function empleados()
    {
        return $this->belongsToMany(Empleado::class, 'empleado_actividads', 'idActividad', 'idEmpleado')
            ->withTimestamps()
            ->withPivot(['descripcion', 'created_by', 'updated_by', 'deleted_by']);
    }

    public function tareas()
    {
        return $this->hasMany(\App\Models\Tareas\Tarea::class, 'idActividad');
    }

    /**
     * Accessor para generar el correlativo formateado
     * Formato: ANIO-CATEGORIA-SIGLAS_DEPTO-R-ID_DIMENSION-ID_RESULTADO-NUM_ACTIVIDAD
     * Ejemplo: 2024-CR-INTER-R-15-112-2
     */
    public function getCorrelativoFormateadoAttribute()
    {
        if (!$this->poa || !$this->categoria || !$this->departamento || !$this->resultado) {
            return $this->correlativo ?? 'N/A';
        }

        $correlativo = '';
        
        // 1. Año del POA
        $correlativo .= $this->poa->anio . '-';
        
        // 2. Categoría (1=CA, 2=JF, 3=AD)
        $categoriaId = $this->categoria->id;
        if ($categoriaId == 1) {
            $correlativo .= 'CA-';
        } elseif ($categoriaId == 2) {
            $correlativo .= 'JF-';
        } elseif ($categoriaId == 3) {
            $correlativo .= 'AD-';
        } else {
            $correlativo .= 'CR-'; // default
        }
        
        // 3. Siglas del departamento
        $correlativo .= ($this->departamento->siglas ?? 'DEPTO') . '-';
        
        // 4. Literal "R" (Resultado)
        $correlativo .= 'R-';
        
        // 5. ID de la dimensión del resultado
        $dimensionId = $this->resultado->area?->objetivo?->dimension?->id ?? '0';
        $correlativo .= $dimensionId . '-';
        
        // 6. ID del resultado
        $correlativo .= $this->resultado->id . '-';
        
        // 7. Número correlativo de la actividad
        $numeroActividad = $this->correlativo ?? '0';
        $correlativo .= $numeroActividad;
        
        return $correlativo;
    }
}