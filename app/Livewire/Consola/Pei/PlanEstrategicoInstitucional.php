<?php

namespace App\Livewire\Consola\Pei;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Poa\Pei;

class PlanEstrategicoInstitucional extends Component
{
    use WithPagination;

    public $showErrorModal = false;
    public $errorMessage = '';
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';

    public function render()
    {
        $peis = Pei::query()
            ->when($this->search, function ($query) {
                $query->where('nombre', 'like', "%{$this->search}%")
                      ->orWhere('descripcion', 'like', "%{$this->search}%");
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.consola.pei.plan-estrategico-institucional', [
            'peis' => $peis,
        ]);
    }
}