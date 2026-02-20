<?php

namespace App\Livewire\Consola\Pei;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Poa\Pei;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class PlanEstrategicoInstitucional extends Component
{
    use WithPagination;

    public $showErrorModal = false;
    public $errorMessage = '';
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function closeErrorModal()
    {
        $this->showErrorModal = false;
        $this->errorMessage = '';
    }

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