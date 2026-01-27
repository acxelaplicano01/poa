<?php

namespace App\Livewire\ProcesCompra;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class TipoProcesoCompras extends Component
{
    public function render()
    {
        return view('livewire.proces-compra.tipo-proceso-compras');
    }
}
