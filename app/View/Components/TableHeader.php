<?php
// App/View/Components/TableHeader.php

namespace App\View\Components;

use Illuminate\View\Component;

class TableHeader extends Component
{
    public $sortField;
    public $sortDirection;
    public $field;
    public $sortable;

    public function __construct(
        $sortField = null,
        $sortDirection = 'asc',
        $field = null,
        $sortable = true
    ) {
        $this->sortField = $sortField;
        $this->sortDirection = $sortDirection;
        $this->field = $field;
        $this->sortable = $sortable;
    }

    public function render()
    {
        return view('components.table-header');
    }
}