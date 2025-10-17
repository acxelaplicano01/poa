<?php

namespace App\Livewire\Cub;

use Livewire\Component;

class Cubs extends Component
{
    public function render()
    {
        return view('livewire.cub.cubs')->layout('layouts.app');
    }
}
