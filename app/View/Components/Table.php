<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Table extends Component
{
    public $sortField;
    public $sortDirection;
    public $columns;
    public $showMobile = true;
    public $emptyMessage;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $sortField = null,
        $sortDirection = 'asc',
        $columns = [],
        $showMobile = true,
        $emptyMessage = 'No se encontraron registros'
    ) {
        $this->sortField = $sortField;
        $this->sortDirection = $sortDirection;
        $this->columns = $columns;
        $this->showMobile = $showMobile;
        $this->emptyMessage = $emptyMessage;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.table');
    }
}