<?php

namespace App\Livewire\Revision;


use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Departamento\Departamento;
use App\Models\Actividad\Actividad;
use App\Models\Poa\Poa;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Revisiones extends Component
{
	protected string $layout = 'layouts.app';
	use WithPagination;

	public $search = '';
	public $perPage = 10;
	public $sortField = 'name';
	public $sortDirection = 'asc';
	public $poaYear = null;
	public $poaYears = [];

	public $showActividades = false;
	public $departamentoId = null;

	public function updatingSearch()
	{
		$this->resetPage();
	}

	public function updatedPoaYear()
	{
		$this->resetPage();
	}

	public function verActividades($departamentoId)
	{
		$this->departamentoId = $departamentoId;
		$this->showActividades = true;
	}

	public function volverARevisiones()
	{
		$this->showActividades = false;
		$this->departamentoId = null;
	}



	public function mount()
	{
		$this->poaYears = Poa::orderBy('anio', 'desc')->pluck('anio')->unique()->toArray();
		if (empty($this->poaYear) && count($this->poaYears)) {
			$this->poaYear = $this->poaYears[0];
		}
	}

	public function render()
	{
		if ($this->showActividades && $this->departamentoId) {
			return view('livewire.Revision.actividades-revision-wrapper', [
				'departamentoId' => $this->departamentoId,
			]);
		}

		$revisiones = Departamento::query()
			->withCount(['actividades as actividades_count' => function($q) {
				$q->whereIn('estado', ['REVISION', 'APROBADO', 'RECHAZADO']);
				if ($this->poaYear) {
					$q->whereHas('poa', function($q2) {
						$q2->where('anio', $this->poaYear);
					});
				}
			}])
			->whereHas('actividades', function($q) {
				$q->whereIn('estado', ['REVISION', 'APROBADO', 'RECHAZADO']);
				if ($this->poaYear) {
					$q->whereHas('poa', function($q2) {
						$q2->where('anio', $this->poaYear);
					});
				}
			})
			->when($this->search, function($q) {
				$q->where('name', 'like', '%'.$this->search.'%');
			})
			->orderBy($this->sortField, $this->sortDirection)
			->paginate($this->perPage);

		$revisiones->getCollection()->transform(function($item) {
			$item->departamento = $item;
			return $item;
		});

		return view('livewire.Revision.revision', [
			'revisiones' => $revisiones,
			'poaYears' => $this->poaYears,
			'poaYear' => $this->poaYear,
		]);
	}
}
