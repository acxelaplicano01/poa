<?php

namespace App\Observers;

use App\Models\Poa\Poa;

class PoaObserver
{
    /**
     * Handle the Poa "updating" event.
     * Se ejecuta antes de actualizar el POA
     */
    public function updating(Poa $poa)
    {
        // Si el POA se está desactivando, desactivar todos sus plazos
        if ($poa->isDirty('activo') && !$poa->activo) {
            $poa->plazos()->update(['activo' => false]);
        }
    }

    /**
     * Handle the Poa "updated" event.
     * Se ejecuta después de actualizar el POA
     */
    public function updated(Poa $poa)
    {
        // Si el POA está inactivo, asegurar que todos los plazos estén inactivos
        if (!$poa->activo) {
            $poa->plazos()->where('activo', true)->update(['activo' => false]);
        }
    }
}
