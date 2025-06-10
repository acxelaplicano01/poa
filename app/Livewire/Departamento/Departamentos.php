<?php

namespace App\Livewire\Departamento;

use Livewire\Component;

class Departamentos extends Component
{
    public function render()
    {
        return view('livewire.departamento.departamentos')->layout('layouts.app');
    }
}
