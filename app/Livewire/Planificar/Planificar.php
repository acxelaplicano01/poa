<?php

namespace App\Livewire\Planificar;

use Livewire\Component;

class Planificar extends Component
{
    public function render()
    {
        return view('livewire.Planificar.planificar')->layout('layouts.app');
    }
}
