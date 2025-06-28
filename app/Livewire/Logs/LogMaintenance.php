<?php

namespace App\Livewire\Logs;

use Livewire\Component;
use App\Models\ActivityLog;

class LogMaintenance extends Component
{
    public $days = 30;
    public $showDeleteModal = false;

    protected $rules = [
        'days' => 'required|integer|min:1',
    ];

    public function render()
    {
        return view('livewire.logs.log-maintenance');
    }

    public function confirmCleanup()
    {
        $this->validate();
        $this->showDeleteModal = true;
    }

    public function confirmDelete()
    {
        try {
            $cutoffDate = now()->subDays($this->days);
            $count = ActivityLog::whereDate('created_at', '<', $cutoffDate)->delete();
            
            session()->flash('message', "Se eliminaron {$count} registros de logs antiguos.");
            $this->closeDeleteModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar los logs: ' . $e->getMessage());
            $this->closeDeleteModal();
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
    }
}
