<?php

namespace App\Livewire\App\Logs;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class LatestLogs extends Component
{
    public $logs = [];
    public $error = '';

    protected $listeners = ['refreshLogs' => 'loadLogs'];

    public function mount()
    {
        $this->loadLogs();
    }

    public function loadLogs()
    {
        try {
            $this->logs = DB::connection('external_logs')
                ->table('logs')
                ->orderByDesc('created_at')
                ->limit(50)
                ->get();
            $this->error = '';
        } catch (\Exception $e) {
            $this->logs = [];
            $this->error = __('Could not load logs.');
        }
    }

    public function render()
    {
        return view('livewire.app.logs.latest-logs');
    }
}
