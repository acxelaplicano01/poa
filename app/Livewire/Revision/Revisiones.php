<?php

namespace App\Livewire\Revision;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Departamento\Departamento;
use App\Models\Actividad\Actividad;
use Illuminate\Support\Facades\DB;

class Revisiones extends Component
{
	protected string $layout = 'layouts.app';
	use WithPagination;

	public $search = '';
	public $perPage = 10;
	public $sortField = 'name';
	public $sortDirection = 'asc';

	public function updatingSearch()
	{
		$this->resetPage();
	}

	public function verActividades($departamentoId)
	{
		// Aquí puedes emitir un evento, redirigir o mostrar un modal con las actividades del departamento
		$this->dispatch('ver-actividades', departamentoId: $departamentoId);
	}

	public function render()
	{
		// Buscar departamentos con actividades en estado de revisión
			$revisiones = Departamento::query()
				->withCount(['actividades as actividades_count' => function($q) {
					$q->where('estado', 'REVISION');
				}])
				->whereHas('actividades', function($q) {
					$q->where('estado', 'REVISION');
				})
				->when($this->search, function($q) {
					$q->where('name', 'like', '%'.$this->search.'%');
				})
				->orderBy($this->sortField, $this->sortDirection)
				->paginate($this->perPage);

			// Agregar accessor departamento para compatibilidad con la vista
			$revisiones->getCollection()->transform(function($item) {
				$item->departamento = $item;
				return $item;
			});

			return view('livewire.Revision.revision', [
				'revisiones' => $revisiones,
			])->layout('layouts.app');
	}
}
