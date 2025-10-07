<?php

namespace App\Livewire\App\Logs;

use Livewire\Component;

class LatestLogs extends Component
{
    public $logs = [];

    public function mount()
    {
        $this->fetchLogs();
    }

    public function fetchLogs()
    {
        $issues = \App\Models\Issue::orderByDesc('created_at')->limit(20)->get();
        $this->logs = $issues->map(function($issue) {
            $date = $issue->created_at ? $issue->created_at->format('d/m/Y H:i:s') : '';
            
            return "[$date] [{$issue->channel}] {$issue->issueType}: {$issue->issueDescription}";
        })->toArray();
    }

    public function render()
    {
        return view('livewire.app.logs.latest-logs', [
            'logs' => $this->logs,
        ]);
    }
}
