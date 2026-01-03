<?php

namespace App\Livewire\Cub;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Cubs extends Component
{
    public function render()
    {
        return view('livewire.cub.cubs');
    }
}
