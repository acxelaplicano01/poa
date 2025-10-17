<?php

namespace App\Models\OrdenCombustible;
use App\Models\BaseModel;
use App\Models\Poa\Poa;
use App\Models\Requisicion\DetalleRequisicion;
use App\Models\Tareas\TareaHistorico;
use App\Models\Empleados\Empleado;

class OrdenCombustible extends BaseModel
{
    protected $table = 'orden_combustible';

    protected $fillable = [
        'correlativo',
        'monto',
        'monto_en_letras',
        'modelo_vehiculo',
        'lugar_salida',
        'lugar_destino',
        'placa',
        'recorrido_km',
        'fecha_actividad',
        'actividades_realizar',
        'idPoa',
        'idDetalleRequisicion',
        'idRecurso',
        'responsable',
        // Los campos de auditoría ya están en BaseModel
    ];

    public function poa()
    {
        return $this->belongsTo(Poa::class, 'idPoa');
    }

    public function detalleRequisicion()
    {
        return $this->belongsTo(DetalleRequisicion::class, 'idDetalleRequisicion');
    }

    public function recurso()
    {
        return $this->belongsTo(TareaHistorico::class, 'idRecurso');
    }

    public function responsableEmpleado()
    {
        return $this->belongsTo(Empleado::class, 'responsable');
    }
}